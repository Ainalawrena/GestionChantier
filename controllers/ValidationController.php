<?php
require __DIR__ . '/../models/ValidationModel.php';

class ValidationController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ValidationModel($pdo);
    }

    public function valider() {
        $data = [
            'statut_validation' => $_POST['statut_validation'],
            'id_avancement'     => $_POST['id_avancement'],
            'id_utilisateur'    => $_SESSION['user_id']
        ];

        $this->model->valider($data);

        header('Location: index.php?page=dashboard&action=dashboardArchitecte&id_chantier=' . $_POST['id_chantier'] . '#avancements');
        exit;
    }

    public function detailHistorique()
    {
        $id_avancement = $_GET['id_avancement'];
    
        $historique = $this->model->getDetailHistorique($id_avancement);
    
        header('Content-Type: application/json');
    
        echo json_encode($historique);
        exit;
    }
}