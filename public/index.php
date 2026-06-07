<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/SessionManager.php';

$handler = new SessionManager($pdo);

session_set_save_handler($handler, true);
session_start();


$page   = $_GET['page']   ?? 'home';
$action = $_GET['action'] ?? 'index';

switch($page) {

    case 'home':
        require __DIR__ . '/index.html';
        break;

    case 'auth':
        require __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController($pdo);
        $controller->$action();
        break;

    case 'chantier':
        require __DIR__ . '/../controllers/ChantierController.php';
        $controller = new ChantierController($pdo);
        $controller->$action();
        break;

    case 'tache':
        require __DIR__ . '/../controllers/TacheController.php';
        $controller = new TacheController($pdo);
        $controller->$action();
        break;

    case 'dashboard':
        require __DIR__ . '/../controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->$action();
        break;

    case 'avancement':
        require __DIR__ . '/../controllers/AvancementController.php';
        $controller = new AvancementController($pdo);
        $controller->$action();
        break;

    case 'incident':
        require __DIR__ . '/../controllers/IncidentController.php';
        $controller = new IncidentController($pdo);
        $controller->$action();
        break;
    
    case 'validation':
        require __DIR__ . '/../controllers/ValidationController.php';
        $controller = new ValidationController($pdo);
        $controller->$action();
        break;

    default:
        require __DIR__ . '/index.html';
}