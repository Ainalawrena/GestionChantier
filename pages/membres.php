<?php
session_start(); // en premier, aucun echo avant

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.html');
    exit;
}

$nom  = $_SESSION['nom'];
$role = $_SESSION['role']; // contient le libellé ex: "admin"

switch ($role) {
    case 'Administrateur':
        header('Location: admin.php');
        exit;
    case 'Chef de chantier':
        header('Location: chef.php');
        exit;
    case 'Ouvrier':
        header('Location: ouvrier.php');
        exit;
    case 'Architecte':
        header('Location: architecte.php');
        exit;
    default:
        header('Location: ../pages/login.html');
        exit;
}
?>