<?php
require_once '../models/UtilisateurModel.php';

class UtilisateurController {
    private $model;

    public function __construct($pdo) {
        $this->model = new UtilisateurModel($pdo);
    }

    public function loginForm(){
        require '../views/auth/login.html';
    }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $login    = trim($_POST['login']    ?? '');
            $password =      $_POST['password'] ?? '';

            if (empty($login) || empty($password)) {
                $erreur = "Champs vides.";
                require '../views/auth/login.html';
                return;
            }

            $utilisateur = $this->model->login($login);
            $role = SessionManager::getRole();
            $id_user = SessionManager::getUserId();

            if (!$utilisateur) {
                $erreur = "Utilisateur introuvable.";
                require '../views/auth/login.html';
                return;
            }

            if (!password_verify($password, $utilisateur['password_hash'])) {
                $erreur = "Mot de passe incorrect.";
                require '../views/auth/login.html';
                return;
            }

            SessionManager::login($utilisateur);
            switch($utilisateur['role']) {
                case 'Administrateur':
                    header('Location: index.php?page=dashboard&action=dashboardAdmin');
                break;
                default:
                    header('Location: index.php?page=chantier&action=choice');
        }
            exit;
        }
    }
    
    public function registerForm(){
        require '../views/auth/registrer.html';
    }

    public function registrer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'nom'      => trim($_POST['nom']      ?? ''),
                'email'    => trim($_POST['email']    ?? ''),
                'adresse'  => trim($_POST['adresse']  ?? ''),
                'login'    => trim($_POST['login']    ?? ''),
                'password' => $_POST['password']      ?? '',
                'id_role'  => $_POST['id_role']        ?? ''
            ];

            // Validation simple
            if (empty($data['nom']) || empty($data['email']) || empty($data['login']) || empty($data['password'])) {
                $erreur = "Veuillez remplir tous les champs obligatoires.";
                require __DIR__ . '/../views/auth/registrer.html';
                return;
            }

            // Crée l'utilisateur
            $this->model->creer($data);
            $utilisateur = $this->model->login($data['login']);
            SessionManager::login($utilisateur);

            //Redirection selon le rôle de l'utilisateur
            switch ($utilisateur['role']) {
                case 'Administrateur':
                    header('Location: index.php?page=dashboard&action=dashboardAdmin');
                    break;
                default:
                    header('Location: index.php?page=chantier&action=choice');
            }
            exit;
        }
    }

    public function logout() {
        SessionManager::logout();
    }

    public function creerUtilisateurAdmin() {
        $data = [
            'nom'      => trim($_POST['nom']),
            'email'    => trim($_POST['email']),
            'adresse'  => trim($_POST['adresse'] ?? ''),
            'login'    => trim($_POST['login']),
            'password' => $_POST['password'],
            'id_role'  => $_POST['id_role']
        ];

        $this->model->creer($data);

        header('Location: index.php?page=dashboard&action=dashboardAdmin');
        exit;
    }

    public function modifierUtilisateur() {
        $data = [
            'id_user' => $_POST['id_user'],
            'nom'     => trim($_POST['nom']),
            'email'   => trim($_POST['email']),
            'adresse' => trim($_POST['adresse'] ?? ''),
            'login'   => trim($_POST['login']),
            'id_role' => $_POST['id_role']
        ];

        $this->model->modifier($data);

        if (!empty($_POST['password'])) {
            $this->model->changerMotDePasse($data['id_user'], $_POST['password']);
        }

        header('Location: index.php?page=dashboard&action=dashboardAdmin');
        exit;
    }

    public function supprimerUtilisateur() {
        $id_user = $_GET['id_user'];
        $this->model->supprimer($id_user);

        header('Location: index.php?page=dashboard&action=dashboardAdmin');
        exit;
    }
}