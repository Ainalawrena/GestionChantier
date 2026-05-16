<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.html');
    exit;
}

$role = $_SESSION['role'];
$rolesAutorises = ['Chef de chantier', 'Ouvrier', 'Architecte', 'Administrateur'];

if (!in_array($role, $rolesAutorises)) {
    header('Location: ../pages/login.html');
    exit;
}

require '../includes/config.php';
require '../includes/queries_chantier.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($chantier['nom']) ?></title>
    <link rel="stylesheet" href="../styles/chef.css">
</head>
<body>
    <header>
        <div class="header-left">
            <h1><?= htmlspecialchars($chantier['nom']) ?></h1>
            <span class="status"><?= htmlspecialchars($chantier['statut']) ?></span>
        </div>
        <button class="btn-logout" onclick="window.location.href='logout.php'">Se déconnecter</button>
    </header>

    <div class="dashboard">
        <nav class="sidebar">
            <button class="nav-btn active" data-tab="chantier">Chantier</button>
            <button class="nav-btn" data-tab="taches">Tâches</button>

            <?php if ($role === 'Chef de chantier'): ?>
                <button class="nav-btn" data-tab="ouvriers">Ouvriers</button>
                <button class="nav-btn" data-tab="avancement">Avancement</button>
                <button class="nav-btn" data-tab="incidents">Incidents</button>
            <?php endif; ?>

            <?php if ($role === 'Architecte'): ?>
                <button class="nav-btn" data-tab="validation">Validation</button>
            <?php endif; ?>

            <?php if ($role === 'Ouvrier'): ?>
                <button class="nav-btn" data-tab="avancement">Mon avancement</button>
                <button class="nav-btn" data-tab="incidents">Incidents</button>
            <?php endif; ?>
        </nav>

        <main class="content">
            <?php require '../includes/tabs/tab_chantier.php'; ?>

            <?php if ($role === 'Chef de chantier'): ?>
                <?php require '../includes/tabs/tab_taches_chef.php'; ?>
                <?php require '../includes/tabs/tab_ouvriers.php'; ?>
                <?php require '../includes/tabs/tab_avancement.php'; ?>
                <?php require '../includes/tabs/tab_incidents.php'; ?>
            <?php endif; ?>

            <?php if ($role === 'Ouvrier'): ?>
                <?php require '../includes/tabs/tab_taches_ouvrier.php'; ?>
                <?php require '../includes/tabs/tab_avancement.php'; ?>
                <?php require '../includes/tabs/tab_incidents.php'; ?>
            <?php endif; ?>

            <?php if ($role === 'Architecte'): ?>
                <?php require '../includes/tabs/tab_taches_chef.php'; ?>
                <?php require '../includes/tabs/tab_validation.php'; ?>
            <?php endif; ?>
        </main>
    </div>

    <script src="../scripts/chef.js"></script>
</body>
</html>