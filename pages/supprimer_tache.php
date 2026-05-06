<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Chef de chantier') {
    header('Location: ../pages/login.html');
    exit;
}

$id_tache    = (int)$_GET['id_tache'] ?? 0;
$id_chantier = (int)$_GET['id_chantier'] ?? 0;

if ($id_tache > 0) {
    $pdo->prepare("DELETE FROM tache WHERE id_tache = ?")->execute([$id_tache]);
}

header('Location: Chef.php?id_chantier=' . $id_chantier);
exit;