<?php
require __DIR__ . '/../models/AffectationModel.php';

class AffectationController {
    private $model;

    public function __construct($pdo) {
        $this->model = new AffectationModel($pdo);
    }

    public function ajouterMembre() {
        $id_chantier = $_POST['id_chantier'];
        $id_user     = $_POST['id_user'];
        $id_role     = $_POST['roleid_role'];

        $this->model->ajouterMembre($id_chantier, $id_user, $id_role);

        header('Location: index.php?page=dashboard&action=dashboardChef&id_chantier=' . $id_chantier . '#ouvriers');
        exit;
    }

    public function retirerMembre() {
        $id_chantier = $_GET['id_chantier'];
        $id_user     = $_GET['id_user'];

        $this->model->retirerMembre($id_chantier, $id_user);

        header('Location: index.php?page=dashboard&action=dashboardChef&id_chantier=' . $id_chantier . '#ouvriers');
        exit;
    }
}