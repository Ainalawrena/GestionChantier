<?php
require_once __DIR__ . '/NotificationModel.php';
class AvancementModel {
    private $pdo; // connexion BDD
    private $notifModel;

    public function __construct($pdo) {
        $this->pdo = $pdo; // reçoit la connexion
        $this->notifModel = new NotificationModel($pdo);
    }  

    public function getAvancementChantier($id_chantier) {
        $sql = "SELECT t.nom , t.ordre, t.statut, t.pourcentage,
                t.date_debut_prevu, t.date_fin_prevu,
                u.nom AS nom_ouvrier
                FROM tache t
                JOIN utilisateur u ON t.id_utilisateur = u.id_user
                WHERE t.id_chantier = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_chantier]);
        $avancement = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $avancement;
    }

    public function dernierAvancementValide($id_tache)
    {
        $sql = "SELECT a.id_avancement, v.id_validation
                FROM avancement_tache a
                LEFT JOIN validation v ON a.id_avancement = v.id_avancement
                WHERE a.id_tache = ?
                ORDER BY a.id_avancement DESC
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ouvrier soumet un avancement
    public function ajouterAvancement($data) 
    {
        
        $dernier = $this->dernierAvancementValide($data['id_tache']);
    
        // Seulement si un avancement existe ET n'est pas validé
        if ($dernier && $dernier['id_validation'] === null) {
            return [
                'succes' => false,
                'message' => 'Votre dernier avancement n\'est pas encore validé.'
            ];
        }
    
        $sql = "INSERT INTO avancement_tache (pourcentage, commentaire, id_tache)
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['pourcentage'],
            $data['commentaire'],
            $data['id_tache']
        ]);
    
        $id_avancement = $this->pdo->lastInsertId('avancement_tache_id_avancement_seq');
    
        $sql2 = "INSERT INTO modifier (id_avancement, id_utilisateur, date_mise_a_jour)
                 VALUES (?, ?, CURRENT_DATE)";
        $this->pdo->prepare($sql2)->execute([
            $id_avancement,
            $data['id_utilisateur']
        ]);
    
    
        $sqlChantierAv = "SELECT id_chantier FROM tache WHERE id_tache = ?";
        $stmtChantierAv = $this->pdo->prepare($sqlChantierAv);
        $stmtChantierAv->execute([$data['id_tache']]);
        $chantierRowAv = $stmtChantierAv->fetch(PDO::FETCH_ASSOC);
        $id_chantier = $chantierRowAv['id_chantier'] ?? null;

        // Trouve l'architecte du chantier
        $sqlArchi = "SELECT ac.id_utilisateur FROM affectation_chantier ac
                     JOIN role r ON ac.id_role = r.id_role
                     JOIN tache t ON t.id_chantier = ac.id_chantier
                     WHERE t.id_tache = ? AND r.libelle = 'Architecte'";
        $stmt = $this->pdo->prepare($sqlArchi);
        $stmt->execute([$data['id_tache']]);
        $architecte = $stmt->fetch(PDO::FETCH_ASSOC);
        // Récupère le nom de l'ouvrier et le nom de la tâche pour un message clair
        $sqlInfos = "SELECT u.nom AS nom_ouvrier, t.nom AS nom_tache
                     FROM utilisateur u, tache t
                     WHERE u.id_user = ? AND t.id_tache = ?";
        $stmtInfos = $this->pdo->prepare($sqlInfos);
        $stmtInfos->execute([$data['id_utilisateur'], $data['id_tache']]);
        $infos = $stmtInfos->fetch(PDO::FETCH_ASSOC);

        if ($architecte) {
            $this->notifModel->creer(
                $architecte['id_utilisateur'],
                'Nouvel avancement soumis',
                "L'ouvrier « {$infos['nom_ouvrier']} » a soumis un avancement sur la tâche « {$infos['nom_tache']} ».",
                'avancement',
                "index.php?page=dashboard&action=dashboardArchitecte&id_chantier={$id_chantier}"
            );
        }

        return [
            'succes' => true,
            'message' => 'Avancement soumis avec succès !'
        ];
    }

     // Architecte voit les avancements à valider
    public function getAvancementsAValider($id_chantier) {
        $sql = "SELECT a.id_avancement, a.pourcentage, a.commentaire,
                t.nom AS nom_tache, t.id_tache,
                u.nom AS nom_ouvrier,
                m.date_mise_a_jour
                FROM avancement_tache a
                JOIN tache t ON a.id_tache = t.id_tache
                JOIN modifier m ON a.id_avancement = m.id_avancement
                JOIN utilisateur u ON m.id_utilisateur = u.id_utilisateur
                LEFT JOIN validation v ON a.id_avancement = v.id_avancement
                WHERE t.id_chantier = ?
                AND v.id_validation IS NULL
                ORDER BY m.date_mise_a_jour ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_chantier]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Historique d'une tâche
    public function getHistorique($id_tache) {
        $sql = "SELECT a.pourcentage, a.commentaire,
                m.date_mise_a_jour,
                u.nom AS nom_ouvrier,
                v.statut_validation,
                v.date_validation
                FROM avancement_tache a
                JOIN modifier m ON a.id_avancement = m.id_avancement
                JOIN utilisateur u ON m.id_utilisateur = u.id_utilisateur
                LEFT JOIN validation v ON a.id_avancement = v.id_avancement
                WHERE a.id_tache = ?
                ORDER BY m.date_mise_a_jour DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTousAvancements() {
        $sql = "SELECT 
                a.id_avancement, a.pourcentage, a.commentaire,
                t.nom AS nom_tache, t.id_tache,
                c.nom AS nom_chantier, c.id_chantier,
                u.nom AS nom_ouvrier,
                m.date_mise_a_jour,
                v.statut_validation, v.date_validation,
                va.nom AS nom_architecte
                FROM avancement_tache a
                JOIN tache t ON a.id_tache = t.id_tache
                JOIN chantier c ON t.id_chantier = c.id_chantier
                JOIN modifier m ON a.id_avancement = m.id_avancement
                JOIN utilisateur u ON m.id_utilisateur = u.id_user
                LEFT JOIN validation v ON a.id_avancement = v.id_avancement
                LEFT JOIN utilisateur va ON v.id_utilisateur = va.id_user
                ORDER BY m.date_mise_a_jour DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>