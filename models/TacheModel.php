<?php

class TacheModel {
    private $pdo; // connexion BDD

    public function __construct($pdo) {
        $this->pdo = $pdo; // reçoit la connexion
    }
    
    public function getJalonsTache($id_tache) {
        $sql = "SELECT j.ordre, j.nom, j.pourcentage
                FROM jalon j
                WHERE j.id_tache = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
        $jalons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $jalons;
    }
    public function commencerTache($id_tache) {
        if (!$this->canStart($id_tache)) {
        return false;
        }
        try {
            // 1. Démarrer la transaction
            //Tant que la transaction est ouverte, les modifications (INSERT/UPDATE/DELETE) restent locales à la transaction jusqu'à commit() ; elles ne sont pas visibles par les autres connexions.
            $this->pdo->beginTransaction();
            $sql = "UPDATE affectation_tache SET date_debut_reelle = CURRENT_DATE WHERE id_tache = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_tache]);

            $sql2 = "UPDATE tache SET statut = 'en cours' WHERE id_tache = ?";
            $stmt2 = $this->pdo->prepare($sql2);
            $stmt2->execute([$id_tache]);

            // 4. Valider les changements
            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            // En cas d'erreur, on annule tout pour ne pas corrompre la BDD
            $this->pdo->rollBack();
            // Pour votre projet académique, ce die() vous affichera la vraie erreur PostgreSQL à l'écran
            die("Erreur PostgreSQL lors du changement de statut : " . $e->getMessage());
        }
        return true;
    }   

    public function creerTache($id_chantier,$data){
        $sql = "INSERT INTO tache (nom, ordre, statut,date_debut_prevue,date_fin_prevue,id_chantier)
                VALUES (:nom, :ordre, :statut, :debut, :fin, :id_chantier)";

        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([
            ':nom'       => $data['nom'],
            ':ordre'      => $data['ordre'],
            ':statut'    => $data['statut'],
            ':debut'     =>!empty($data['date_debut_prevue']) ? $data['date_debut_prevue'] : null,
            ':fin'       =>!empty($data['date_fin_prevue'])   ? $data['date_fin_prevue']   : null,
            ':id_chantier' => $id_chantier
        ]);

        if ($res) {
            // Retourner l'id inséré (PDO lastInsertId)
            try {
                $id = $this->pdo->lastInsertId();
            } catch (Exception $e) {
                $id = null;
            }
            return $id;
        }
        return false;
    }

    public function modifierTache($id_chantier,$data){
        $sql = "UPDATE tache SET nom=:nom, ordre=:ordre, statut=:statut, date_debut_prevue=:debut, date_fin_prevue=:fin
                WHERE id_chantier = :id_chantier AND id_tache = :id_tache";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom'       => $data['nom'],
            ':ordre'      => $data['ordre'],
            ':statut'    => $data['statut'],
            ':debut'     =>!empty($data['date_debut_prevue']) ? $data['date_debut_prevue'] : null,
            ':fin'       =>!empty($data['date_fin_prevue'])   ? $data['date_fin_prevue']   : null,
            ':id_chantier' => $id_chantier,
            ':id_tache' => $data['id_tache']
        ]);
    }

    public function supprimerTache($id_tache){
        $sql = "DELETE FROM tache WHERE id_tache = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_tache]);
    }

   public function getTacheById($id_tache)
    {
        $sql = "
            SELECT t.*, u.nom AS nom_ouvrier
            FROM tache t
            LEFT JOIN utilisateur u
                ON t.id_utilisateur = u.id_user
            WHERE t.id_tache = ?
        ";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
    
        $tache = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Charger les dépendances
        $sqlDep = "
            SELECT t2.id_tache, t2.nom
            FROM dependance_tache d
            JOIN tache t2
                ON d.id_tache_precedente = t2.id_tache
            WHERE d.id_tache = ?
        ";
    
        $stmtDep = $this->pdo->prepare($sqlDep);
        $stmtDep->execute([$id_tache]);
    
        $tache['dependances'] =
            $stmtDep->fetchAll(PDO::FETCH_ASSOC);
    
        return $tache;
    }


    public function getDependancesTache($id_tache) {
        $sql = "SELECT t.id_tache, t.nom
                FROM dependance_tache d
                JOIN tache t ON d.id_tache_precedente = t.id_tache
                WHERE d.id_tache = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
        $dependances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dependances;
    }

    public function ajouterDependance($id_tache, $id_tache_precedente) {
        $sql = "INSERT INTO dependance_tache (id_tache, id_tache_precedente) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_tache, $id_tache_precedente]);
    }

    public function supprimerDependance($id_tache, $id_tache_precedente) {
        $sql = "DELETE FROM dependance_tache WHERE id_tache = ? AND id_tache_precedente = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_tache, $id_tache_precedente]);
    }

    public function setDependances($id_tache, $dependances) {
        try {
            $this->pdo->beginTransaction();

            // Supprimer toutes les dépendances existantes
            $sqlDel = "DELETE FROM dependance_tache WHERE id_tache = ?";
            $this->pdo->prepare($sqlDel)->execute([$id_tache]);

            // Insérer les nouvelles dépendances
            if (!empty($dependances) && is_array($dependances)) {
                $sqlIns = "INSERT INTO dependance_tache (id_tache, id_tache_precedente) VALUES (?, ?)";
                $stmtIns = $this->pdo->prepare($sqlIns);
                foreach ($dependances as $id_prev) {
                    // éviter self-dependance
                    if ($id_prev == $id_tache) continue;
                    $stmtIns->execute([$id_tache, $id_prev]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // Méthodes d'aide pour la logique métier
    public function canStart($id_tache) {
        $sql = "SELECT COUNT(*) FROM dependance_tache d
                JOIN tache t ON d.id_tache_precedente = t.id_tache
                WHERE d.id_tache = ? AND t.statut != 'termine'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
        return (int)$stmt->fetchColumn() === 0;
    }

    public function getBlockingDependencies($id_tache) {
        $sql = "SELECT t.id_tache, t.nom, t.statut
                FROM dependance_tache d
                JOIN tache t ON d.id_tache_precedente = t.id_tache
                WHERE d.id_tache = ? AND t.statut != 'termine'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function maxDependencyEnd($id_tache) {
        $sql = "SELECT MAX(COALESCE(t.date_fin_reelle, t.date_fin_prevue)) 
                FROM dependance_tache d
                JOIN tache t ON d.id_tache_precedente = t.id_tache
                WHERE d.id_tache = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
        return $stmt->fetchColumn(); // format date or null
    }

}