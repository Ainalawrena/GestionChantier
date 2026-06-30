<?php
class AdminModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getStatsGlobales() {
        $stats = [];

        // Total chantiers
        $stats['total_chantiers'] = $this->pdo->query(
            "SELECT COUNT(*) FROM chantier"
        )->fetchColumn();

        // Chantiers en cours
        $stats['chantiers_en_cours'] = $this->pdo->query(
            "SELECT COUNT(*) FROM chantier WHERE statut = 'en cours'"
        )->fetchColumn();

        // Chantiers en retard
        $stats['chantiers_retard'] = $this->pdo->query(
            "SELECT COUNT(*) FROM chantier 
             WHERE date_fin_prevu < CURRENT_DATE 
             AND statut != 'termine'"
        )->fetchColumn();

        // Chantiers terminés
        $stats['chantiers_termines'] = $this->pdo->query(
            "SELECT COUNT(*) FROM chantier WHERE statut = 'termine'"
        )->fetchColumn();

        // Total utilisateurs par rôle
        $sql = "SELECT r.libelle, COUNT(u.id_user) as nb
                FROM role r
                LEFT JOIN utilisateur u ON u.id_role = r.id_role
                GROUP BY r.libelle";
        $stats['utilisateurs_par_role'] = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $stats['total_utilisateurs'] = $this->pdo->query(
            "SELECT COUNT(*) FROM utilisateur"
        )->fetchColumn();

        // Incidents ouverts
        $stats['incidents_ouverts'] = $this->pdo->query(
            "SELECT COUNT(*) FROM incident WHERE statut = 'ouvert'"
        )->fetchColumn();

        // Incidents critiques
        $stats['incidents_critiques'] = $this->pdo->query(
            "SELECT COUNT(*) FROM incident WHERE gravite = 'critique' AND statut = 'ouvert'"
        )->fetchColumn();

        // Avancements en attente de validation
        $stats['avancements_a_valider'] = $this->pdo->query(
            "SELECT COUNT(*) FROM avancement_tache a
             LEFT JOIN validation v ON a.id_avancement = v.id_avancement
             WHERE v.id_validation IS NULL"
        )->fetchColumn();

        // Total tâches
        $stats['total_taches'] = $this->pdo->query(
            "SELECT COUNT(*) FROM tache"
        )->fetchColumn();

        $stats['taches_terminees'] = $this->pdo->query(
            "SELECT COUNT(*) FROM tache WHERE statut = 'termine'"
        )->fetchColumn();

        // Progression moyenne globale (tous chantiers)
        $stats['progression_moyenne'] = (int) $this->pdo->query(
            "SELECT COALESCE(ROUND(AVG(pourcentage)), 0) FROM tache"
        )->fetchColumn();

        // 5 derniers incidents
        $sql = "SELECT i.*, t.nom AS nom_tache, c.nom AS nom_chantier
                FROM incident i
                JOIN tache t ON i.id_tache = t.id_tache
                JOIN chantier c ON t.id_chantier = c.id_chantier
                ORDER BY i.date_incident DESC
                LIMIT 5";
        $stats['derniers_incidents'] = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        // 5 chantiers les plus actifs (le plus de tâches en cours)
        $sql = "SELECT c.nom, c.id_chantier, COUNT(t.id_tache) as nb_taches_actives
                FROM chantier c
                JOIN tache t ON t.id_chantier = c.id_chantier
                WHERE t.statut = 'en cours'
                GROUP BY c.nom, c.id_chantier
                ORDER BY nb_taches_actives DESC
                LIMIT 5";
        $stats['chantiers_actifs'] = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }
}