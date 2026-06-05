<?php
require __DIR__ . '/../models/IncidentModel.php';

class IncidentController {
    private $model;

    public function __construct($pdo) {
        $this->model = new IncidentModel($pdo);
    }

    public function declarer() {
        $data = [
            'description' => $_POST['description'],
            'gravite'     => $_POST['gravite'],
            'impact'      => $_POST['impact'] ?? '',
            'id_tache'    => $_POST['id_tache']
        ];

        $this->model->declarerIncident($data);

        header('Location: index.php?page=dashboard&action=dashboardOuvrier&id_chantier=' . $_POST['id_chantier']);
        exit;
    }
}