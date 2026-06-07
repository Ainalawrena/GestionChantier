<?php

class ChantierModel {
    private $pdo; // connexion BDD

    public function __construct($pdo) {
        $this->pdo = $pdo; // reçoit la connexion
    }  
    

    //==========================GETTERS============================================================

    public function getById($id_chantier) {
        $stmt = $this->pdo->prepare("SELECT * FROM chantier WHERE id_chantier = ?");
        $stmt->execute([$id_chantier]);
        $chantier = $stmt->fetch(PDO::FETCH_ASSOC);
        return $chantier;
    }

    public function getRoleChantier($id_chantier) {
        $id_utilisateur = $_SESSION['user_id'];
        $stmt = $this->pdo->prepare("SELECT r.libelle 
            FROM role r
            JOIN affectation_chantier ac ON r.id_role = ac.id_role
            WHERE ac.id_utilisateur = ? AND  ac.id_chantier = ?");

        $stmt->execute([$id_utilisateur, $id_chantier]);
        $roleChantier = $stmt->fetch(PDO::FETCH_ASSOC);
        return $roleChantier;
    }

    public function getMesChantiers($id_user){
        $sql = "SELECT c.id_chantier, c.nom, r.libelle
        FROM chantier c
        JOIN affectation_chantier a ON c.id_chantier = a.id_chantier
        JOIN role r ON a.id_role = r.id_role
        WHERE a.id_utilisateur = :id_user";
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([':id_user' => $id_user]);
        $chantiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $chantiers;
    }

    public function getModeles(){
        $sql = "SELECT * FROM modele";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $modele = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $modele;
    }

    public function getTachesModele($id_modele){
        $sql = "SELECT * FROM tache_modele WHERE id_modele = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_modele]);
        $tachesModele = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tachesModele;
    }

    public function getTache($id_chantier){
        $sql = "SELECT t.*, u.nom AS nom_ouvrier
            FROM tache t
            LEFT JOIN utilisateur u ON t.id_utilisateur = u.id_user
            WHERE t.id_chantier = ?
            ORDER BY t.ordre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_chantier]);
        $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $taches;
    }

    public function getOuvriers($id_chantier){
        $stmt = $this->pdo->prepare("SELECT u.id_user, u.nom, u.email, r.libelle, COUNT(t.id_tache) AS nb_taches
            FROM affectation_chantier ac
            JOIN utilisateur u ON ac.id_utilisateur = u.id_user
            LEFT JOIN tache t ON t.id_utilisateur = u.id_user
                AND t.id_chantier = ac.id_chantier
            JOIN role r ON r.id_role = ac.id_role
            WHERE ac.id_chantier = ?
            GROUP BY u.id_user, u.nom, u.email, r.libelle");
        $stmt->execute([$id_chantier]);
        $ouvriers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $ouvriers;
    }

    public function getTachesOuvrier($id_chantier, $id_utilisateur) {
        $sql = "SELECT t.*,
                COALESCE(
                    (SELECT a.pourcentage 
                     FROM avancement_tache a 
                     WHERE a.id_tache = t.id_tache 
                     ORDER BY a.id_avancement DESC 
                     LIMIT 1)
                , 0) AS dernier_soumis
                FROM tache t
                JOIN affectation_tache at ON t.id_tache = at.id_tache
                WHERE t.id_chantier = ?
                AND at.id_utilisateur = ?
                ORDER BY t.ordre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_chantier, $id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //=================================CREATION TACHE=====================================================

    public function creerChantier($data,$equipe,$idchef){
        $statut = "en attente";
        $sql = "INSERT INTO chantier (nom, date_debut_prevu, date_fin_prevu, statut, id_modele) 
             VALUES (:nom, :debut, :fin, :statut, :id_modele)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'       => $data['nom'],
            ':debut'     => $data['date_debut_prevu'],
            ':fin'       => $data['date_fin_prevu'],
            ':statut'    => $statut,
            ':id_modele' => $data['id_modele']
        ]);

        $id_chantier = $this->pdo->lastInsertId('chantier_id_chantier_seq'); 
        // chef chantier
        $sql2 = "INSERT INTO affectation_chantier
            (id_utilisateur, id_chantier, id_role)
            VALUES (?, ?, 2)";

        $this->pdo->prepare($sql2)->execute([$idchef, $id_chantier]);

        foreach($equipe as $id_user)
        {
            if($id_user == $idchef) continue;

            $sql3 = "INSERT INTO affectation_chantier
                    (id_utilisateur, id_chantier, id_role)
                    VALUES (?, ?, 3)";

            $this->pdo->prepare($sql3)->execute([$id_user, $id_chantier]);
        }

        $sql4 = "SELECT * FROM tache_modele
            WHERE id_modele = ?";

        $stmtTM = $this->pdo->prepare($sql4);
        $stmtTM->execute([$data['id_modele']]);

        $tachesModele = $stmtTM->fetchAll(PDO::FETCH_ASSOC);

        foreach($tachesModele as $tachem)
        {
            $sql5 = "INSERT INTO tache
                    (nom, ordre, statut, pourcentage,
                     id_chantier,
                     id_tache_modele)

                    VALUES (?, ?, 'en attente', 0, ?, ?)";

            $this->pdo->prepare($sql5)->execute([
                $tachem['nom'],
                $tachem['ordre'],
                $id_chantier,
                $tachem['id_tache_modele']
            ]);
        }
        return $id_chantier;
    }

   
    

    //==============================AJOUT ET RETIRE MEMBRE EQUIPE==================================
    public function ajouterMembre($id_chantier, $id_user,$id_role) {
        $sql = "INSERT INTO affectation_chantier (id_utilisateur, id_chantier, id_role)
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_user, $id_chantier,$id_role]);
    }

    public function retirerMembre($id_chantier, $id_user) {
        $sql = "DELETE FROM affectation_chantier 
                WHERE id_utilisateur = ? AND id_chantier = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt = $stmt->execute([$id_user, $id_chantier]);

        $sql2 = "UPDATE tache 
                SET id_utilisateur = NULL 
                WHERE id_utilisateur = ? AND id_chantier = ?";
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute([$id_user, $id_chantier]);

        $sql3 = "DELETE FROM affectation_tache 
                WHERE id_utilisateur = ? AND id_tache IN (
                    SELECT id_tache FROM tache WHERE id_chantier = ?
                )";
        $stmt3 = $this->pdo->prepare($sql3);
        $stmt3->execute([$id_user, $id_chantier]);

        return $stmt;
    }
 
    public function affecterTache($id_chantier, $id_user, $id_tache) {
        $sql = "UPDATE tache 
                SET id_utilisateur = ? 
                WHERE id_tache = ? AND id_chantier = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt =  $stmt->execute([$id_user, $id_tache, $id_chantier]);

        $sql2 = "INSERT INTO affectation_tache (id_utilisateur, id_tache) VALUES (?, ?)";
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute([$id_user, $id_tache]);

        return $stmt;
    }


}

?>