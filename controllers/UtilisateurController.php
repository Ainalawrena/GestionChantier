<?php
require_once '../models/UtilisateurModel.php';

class UtilisateurController {
    private $model;

    public function __construct($pdo) {
        $this->model = new UtilisateurModel($pdo);
    }

    // méthodes ici
    public function loginForm(){
        require '../views/auth/login.html';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $login    = trim($_POST['login']    ?? '');
            $password =      $_POST['password'] ?? '';

            if (empty($login) || empty($password)) {
                $erreur = "Champs vides.";
                require '../views/auth/login.html';
                return;
            }

            $utilisateur = $this->model->login($login);
            // Dans le Controller
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
           
            header('Location: index.php?page=chantier&action=choice');
            exit;
        }
    }
    
    public function registerForm(){
        require '../views/auth/registrer.html';
    }

    public function registrer(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'nom'      => trim($_POST['nom']      ?? ''),
                'email'    => trim($_POST['email']    ?? ''),
                'adresse'  => trim($_POST['adresse']  ?? ''),
                'login'    => trim($_POST['login']    ?? ''),
                'password' => $_POST['password']      ?? '',
                'id_role'  => $_POST['id_role']        ?? ''
            ];

            $this->model->creer($data);

            header('Location: index.php?page=auth&action=loginForm');
            exit;
        }
    }


}