<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Chef de chantier') {
    header('Location: ../pages/login.html');
    exit;
}

$id_tache    = (int)$_POST['id_tache'];
$id_user     = (int)$_POST['id_user'];
$id_chantier = (int)$_POST['id_chantier'];

// Met à jour la tâche avec l'ouvrier
$stmt = $pdo->prepare("UPDATE tache 
    SET utilisateursid_utilisateur = ? 
    WHERE id_tache = ?");
$stmt->execute([$id_user, $id_tache]);

// Ajoute dans affectation_tache si pas déjà là
$existe = $pdo->prepare("SELECT * FROM affectation_tache 
    WHERE utilisateursid_utilisateur = ? AND tacheid_tache = ?");
$existe->execute([$id_user, $id_tache]);

if (!$existe->fetch()) {
    $pdo->prepare("INSERT INTO affectation_tache 
        (utilisateursid_utilisateur, tacheid_tache) 
        VALUES (?, ?)")
        ->execute([$id_user, $id_tache]);
}

header('Location: Chef.php?id_chantier=' . $id_chantier);
exit;