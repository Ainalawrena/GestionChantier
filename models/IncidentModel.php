<?php
class IncidentModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getIncidents($id_chantier) {
        $sql = "SELECT i.*, t.nom AS nom_tache
                FROM incident i
                JOIN tache t ON i.id_tache = t.id_tache
                WHERE t.id_chantier = ?
                ORDER BY i.date_incident DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_chantier]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function declarerIncident($data) {
        $sql = "INSERT INTO incident (description, gravite, impact, date_incident, statut, id_tache)
                VALUES (?, ?, ?, CURRENT_DATE, 'ouvert', ?)";
        $this->pdo->prepare($sql)->execute([
            $data['description'],
            $data['gravite'],
            $data['impact'],
            $data['id_tache']
        ]);

        // Si grave → bloque la tâche
        if ($data['gravite'] === 'critique') {
            $sql2 = "UPDATE tache SET statut = 'bloque' WHERE id_tache = ?";
            $this->pdo->prepare($sql2)->execute([$data['id_tache']]);
        }
    }

    public function resoudre($data) {
        $sql = "UPDATE incident SET
                statut = 'resolu',
                solution = ?,
                date_resolution = CURRENT_DATE
                WHERE id_incident = ?";
        $this->pdo->prepare($sql)->execute([
            $data['solution'],
            $data['id_incident']
        ]);
    }
}
?>