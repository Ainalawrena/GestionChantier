<?php
require __DIR__ . '/../models/AvancementModel.php';

class AvancementController {
    private $model;

    public function __construct($pdo) {
        $this->model = new AvancementModel($pdo);
    }

    public function ajouter() {
           

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=loginForm');
            exit;
        }

        // 1. Préparation des données pour le modèle
        $data = [
            //  On récupère la valeur du select qui s'appelle 'id_jalon' 
            // et qui contient le pourcentage grâce au JavaScript
            'pourcentage'    => isset($_POST['id_jalon']) ? (int)$_POST['id_jalon'] : 0,
            'commentaire'    => $_POST['commentaire'] ?? '',
            'id_tache'       => isset($_POST['id_tache']) ? (int)$_POST['id_tache'] : 0,
            'id_utilisateur' => (int)$_SESSION['user_id']
        ];

        // 2. Appel du modèle pour insertion en base de données
        $result = $this->model->ajouterAvancement($data);

        // 3. Gestion des messages flash selon le retour du modèle
        if ($result && isset($result['succes']) && !$result['succes']) {
            $_SESSION['erreur'] = $result['message'];
        } else {
            $_SESSION['succes'] = 'Avancement soumis avec succès à l\'architecte !';
        }

        // 4. Redirection propre vers le chantier actuel
        $id_chantier = isset($_POST['id_chantier']) ? (int)$_POST['id_chantier'] : 0;
        header('Location: index.php?page=dashboard&action=dashboardOuvrier&id_chantier=' . $id_chantier);
        exit;
    }
}
