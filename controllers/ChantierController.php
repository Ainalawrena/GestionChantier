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

    public function choice() {
        SessionManager::requireLogin();

        $id_user = $_SESSION['user_id'];
        $chantiers = $this->model->getMesChantiers($id_user);
        $role      = SessionManager::getRole();
        
        require "../views/global/choice.php";
    }

    public function nouveauChantier(){  
        $modeles = $this->model->getModeles();
        $utilisateurs = $this->utilisateurModel-> getUtilisateursSaufConnecte(); 
        require "../views/chef/nouveauChantier.php";
    }

    public function enregistrerNouveauChantier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'              => trim($_POST['nom']      ?? ''),
                'date_debut_prevu' => trim($_POST['debut']    ?? ''),
                'date_fin_prevu'   => trim($_POST['fin']       ?? ''),
                'id_modele'        => $_POST['id_modele']      ?? ''
            ];

            //  Validation des dates
            $aujourdhui = new DateTime();
            $aujourdhui->setTime(0, 0, 0);

            $debut = DateTime::createFromFormat('Y-m-d', $data['date_debut_prevu']);
            $fin   = DateTime::createFromFormat('Y-m-d', $data['date_fin_prevu']);

            // Champs vides
            if (!$debut || !$fin) {
                $_SESSION['erreur'] = "Veuillez renseigner des dates valides.";
                header('Location: index.php?page=chantier&action=nouveauChantier');
                exit;
            }

            // Date de début dans le passé
            if ($debut < $aujourdhui) {
                $_SESSION['erreur'] = "La date de début ne peut pas être dans le passé.";
                header('Location: index.php?page=chantier&action=nouveauChantier');
                exit;
            }

            // Date de fin avant date de début
            if ($fin <= $debut) {
                $_SESSION['erreur'] = "La date de fin doit être après la date de début.";
                header('Location: index.php?page=chantier&action=nouveauChantier');
                exit;
            }

            // Durée minimum d'un mois
            $dureeMinimum = clone $debut;
            $dureeMinimum->modify('+1 month');

            if ($fin < $dureeMinimum) {
                $_SESSION['erreur'] = "La durée du chantier doit être d'au moins 1 mois.";
                header('Location: index.php?page=chantier&action=nouveauChantier');
                exit;
            }

            $equipe = $_POST['equipe'] ?? [];
            $id_chantier = $this->model->creerChantier($data, $equipe, $_SESSION['user_id']);
            header("Location: index.php?page=dashboard&action=dashboardChef&id_chantier=$id_chantier#chantier");
            exit;
        }
    }


    //Concernant affectation des membres
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

    public function affecterTache() {
        $id_chantier = $_POST['id_chantier'];
        $id_user     = $_POST['id_user'];
        $id_tache    = $_POST['id_tache'];

        $this->model->affecterTache($id_chantier, $id_user, $id_tache);

        header('Location: index.php?page=dashboard&action=dashboardChef&id_chantier=' . $id_chantier . '#ouvriers');
        exit;
    }

}