<?php
require __DIR__ . '/../models/ChantierModel.php';
require __DIR__ . '/../models/UtilisateurModel.php';

class ChantierController {
    private $model;
    private $utilisateurModel;

    public function __construct($pdo) {
        $this->model = new ChantierModel($pdo);
        $this->utilisateurModel = new UtilisateurModel($pdo);
    } 

    public function choice(){
        if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=auth&action=loginForm');
        exit;
    }

   
        $id_user = $_SESSION['user_id'];
        $chantiers = $this->model->getMesChantiers($id_user);
        if($_SESSION['role'] === 'Administrateur'){
            require "../views/admin/DashboardAdmin.php";
        }
        require "../views/global/choice.php";
    }

    public function nouveauChantier(){  
        $modeles = $this->model->getModeles();
        $utilisateurs = $this->utilisateurModel->getUtilisateurs(); 
        require "../views/chef/nouveauChantier.php";
    }

    public function enregistrerNouveauChantier(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'nom'                 => trim($_POST['nom']      ?? ''),
                'date_debut_prevu'    => trim($_POST['debuut']    ?? ''),
                'date_fin_prevu'      => trim($_POST['fin']  ?? ''),
                'statut'              => trim($_POST['statut']    ?? ''),
                'id_modele'  => $_POST['id_modele']        ?? ''
            ];
            $equipe = $_POST['equipe'] ?? [];
            $id_chantier = $this->model->creerChantier($data,$equipe,$_SESSION['user_id']);

            header("Location: index.php?page=dashboard&action=chef&new=$id_chantier");
            exit;
        }
    }


    //Concernant affectation des membres
    public function ajouterMembre() {
        $id_chantier = $_POST['id_chantier'];
        $id_user     = $_POST['id_user'];
        $id_role     = $_POST['roleid_role'];

        $this->model->ajouterMembre($id_chantier, $id_user, $id_role);

        header('Location: index.php?page=dashboard&action=dashboardChef&id_chantier=' . $id_chantier);
        exit;
    }

    public function retirerMembre() {
        $id_chantier = $_GET['id_chantier'];
        $id_user     = $_GET['id_user'];

        $this->model->retirerMembre($id_chantier, $id_user);

        header('Location: index.php?page=dashboard&action=dashboardChef&id_chantier=' . $id_chantier);
        exit;
    }
}