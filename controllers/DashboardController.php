<?php
require __DIR__ . '/../models/ChantierModel.php';
require __DIR__ . '/../models/UtilisateurModel.php';
require __DIR__ . '/../models/AvancementModel.php';
require __DIR__ . '/../models/ValidationModel.php';
require __DIR__ . '/../models/IncidentModel.php';
require __DIR__ . '/../models/TacheModel.php'; 

class DashboardController {
    private $pdo; 
    private $chantierModel;
    private $utilisateurModel;
    private $avancementModel;
    private $validationModel;
    private $incidentModel;
    private $tacheModel; 

    public function __construct($pdo) {
        $this->pdo = $pdo; 
        $this->chantierModel    = new ChantierModel($pdo);
        $this->utilisateurModel = new UtilisateurModel($pdo);
        $this->avancementModel  = new AvancementModel($pdo);
        $this->validationModel  = new ValidationModel($pdo);
        $this->incidentModel    = new IncidentModel($pdo);
        $this->tacheModel       = new TacheModel($pdo); // Initialisé proprement dès le départ
    } 

    public function ouvrirChantier() {
        $id_chantier = $_GET['id_chantier'] ?? 0;
        $chantier     = $this->chantierModel->getById($id_chantier);
        $role = $this->chantierModel->getRoleChantier($id_chantier);        

        $role = $role['libelle'];

        if($role === 'Chef de chantier') {
            header("Location: index.php?page=dashboard&action=dashboardChef&id_chantier=$id_chantier");
        }
        elseif ($role === 'Ouvrier') {
            header("Location: index.php?page=dashboard&action=dashboardOuvrier&id_chantier=$id_chantier");
        } 
        elseif ($role === 'Architecte') {
            header("Location: index.php?page=dashboard&action=dashboardArchitecte&id_chantier=$id_chantier");
        } 
        elseif ($role === 'Administrateur') {
            header("Location: index.php?page=dashboard&action=dashboardAdmin&id_chantier=$id_chantier");

        }else {
            header('Location: index.php?page=chantier&action=choice');
        }
        
        // switch ($role) {
        //     case 'Chef de chantier':
        //         header("Location: index.php?page=dashboard&action=dashboardChef&id_chantier=$id_chantier");
        //         break;
        //     case 'Ouvrier':
        //         echo "=======================================================Redirection vers dashboardArchitecte avec id_chantier=$id_chantier"; // Debug

        //         header("Location: index.php?page=dashboard&action=dashboardOuvrier&id_chantier=$id_chantier");
        //         break;
        //     case 'Architecte':
        //         header("Location: index.php?page=dashboard&action=dashboardArchitecte&id_chantier=$id_chantier");
        //         break;
        //     case 'Administrateur':
        //         header("Location: index.php?page=dashboard&action=dashboardAdmin&id_chantier=$id_chantier");
        //         break;
        //     default:
        //         header('Location: index.php?page=chantier&action=choice');
        // }
        // exit;
    }

    public function dashboardChef() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=loginForm');
            exit;
        }

        $role        = $_SESSION['role'];
        $id_chantier = $_GET['id_chantier'] ?? 0;

        $chantier     = $this->chantierModel->getById($id_chantier);
        $taches       = $this->chantierModel->getTache($id_chantier);
        $tachesModele = $this->chantierModel->getTachesModele($chantier['id_modele']);
        $ouvriers     = $this->chantierModel->getOuvriers($id_chantier);
        $tousUtilisateurs    = $this->utilisateurModel->getUtilisateurs();
        $incidents    = $this->incidentModel->getIncidents($id_chantier);

        require __DIR__ . '/../views/chef/DashboardChef.php';
    }

    public function dashboardOuvrier()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=loginForm');
            exit;
        }

        $id_chantier = $_GET['id_chantier'] ?? 0;
        $role        = $_SESSION['role'];
        $ouvriers    = $this->chantierModel->getOuvriers($id_chantier);
        $chantier    = $this->chantierModel->getById($id_chantier);
        $mestaches   = $this->chantierModel->getTachesOuvrier($id_chantier, $_SESSION['user_id']);
        $incidents   = $this->incidentModel->getIncidents($id_chantier);

        // CORRECTION : Le code est devenu extrêmement propre grâce au constructeur
        foreach ($mestaches as $key => $tache) {
            $jalons = $this->tacheModel->getJalonsTache($tache['id_tache']);
            $mestaches[$key]['jalons'] = $jalons;
        }

        require __DIR__ . '/../views/ouvrier/DashboardOuvrier.php';
    }

    public function dashboardArchitecte(){
        // À implémenter plus tard pour la démo de validation groupée
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=loginForm');
            exit;
        }

        $id_chantier = $_GET['id_chantier'] ?? 0;
        $role        = $_SESSION['role'];
        $ouvriers    = $this->chantierModel->getOuvriers($id_chantier);
        $chantier    = $this->chantierModel->getById($id_chantier);
        $avancementsAValider = $this->validationModel->getAvancementsAValider($id_chantier);
  
        require __DIR__ . '/../views/architecte/DashboardArchitecte.php';

    }

    public function dashboardAdmin(){
        // À implémenter plus tard pour la démo de validation groupée
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=loginForm');
            exit;
        }

        $id_chantier = $_GET['id_chantier'] ?? 0;
        $role        = $_SESSION['role'];
        $ouvriers    = $this->chantierModel->getOuvriers($id_chantier);
        $chantier    = $this->chantierModel->getById($id_chantier);
        $avancementsAValider = $this->validationModel->getAvancementsAValider($id_chantier);
  
        require __DIR__ . '/../views/admin/DashboardAdmin.php';
    }
}
?>
