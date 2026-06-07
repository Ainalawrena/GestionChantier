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

    public function creerTache(){
        $id_chantier = $_POST['id_chantier'];
        $data = [
                'nom'                 => trim($_POST['nom']      ?? ''),
                'ordre'                 => trim($_POST['ordre']      ?? ''),
                'statut'                 => trim($_POST['satut']      ?? ''),
                'date_debut_prevue'    => trim($_POST['date_debut_prevue']    ?? ''),
                'date_fin_prevue'      => trim($_POST['date_fin_prevue']  ?? '')
            ];
        $this->model->creerTache($id_chantier,$data);
        header('Location: index.php?page=dashboard&action=dashboardChef&id_chantier=' . $id_chantier);
        exit;
    }

    public function modifierTache(){
        $id_chantier = $_POST['id_chantier'];
        $data = [
                'nom'                 => trim($_POST['nom']      ?? ''),
                'ordre'                 => trim($_POST['ordre']      ?? ''),
                'statut'                 => trim($_POST['satut']      ?? ''),
                'date_debut_prevue'    => trim($_POST['date_debut_prevue']    ?? ''),
                'date_fin_prevue'      => trim($_POST['date_fin_prevue']  ?? ''),
                'id_tache'            => trim($_POST['id_tache']  ?? '')
            ];
        $this->model->modifierTache($id_chantier,$data);
        header('Location: index.php?page=dashboard&action=dashboardChef&id_chantier=' . $id_chantier);
        exit;
    }

    public function supprimerTache(){
        $id_chantier = $_GET['id_chantier'];
        $id_tache = $_GET['id_tache'];
        $this->model->supprimerTache($id_tache);
        header('Location: index.php?page=dashboard&action=dashboardChef&id_chantier=' . $id_chantier);
        exit;
    }

    public function detailTache() {
        $id_tache = $_GET['id_tache'] ?? 0;
        $tache    = $this->model->getTacheById($id_tache);
        header('Content-Type: application/json');
        echo json_encode($tache);
        exit;
    }
}
?>
