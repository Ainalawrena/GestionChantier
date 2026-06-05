<?php
require __DIR__ . '/../models/TacheModel.php';

class TacheController {
    private $model;

    public function __construct($pdo) {
        $this->model = new TacheModel($pdo);
    }

    public function getJalons() {
        $id_tache = $_GET['id_tache'] ?? 0;
        $jalons = $this->model->getJalonsTache($id_tache);
        header('Content-Type: application/json');
        echo json_encode($jalons);
        exit;
    }

    public function commencer() {
        // 1. On récupère les paramètres depuis l'URL (GET)
        $id_tache    = $_GET['id_tache'] ?? 0;
        $id_chantier = $_GET['id_chantier'] ?? 0;

        if ($id_tache > 0) {
            // 2. Mise à jour du statut en 'en cours'
            $this->model->commencerTache($id_tache);
        }

        // 3. Redirection sécurisée avec le bon ID de chantier
        header('Location: index.php?page=dashboard&action=dashboardOuvrier&id_chantier=' . $id_chantier);
        exit;
    }
}
?>
