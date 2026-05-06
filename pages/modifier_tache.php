<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Chef de chantier') {
    header('Location: ../pages/login.html');
    exit;
}

$id_tache    = (int)$_GET['id_tache'] ?? 0;
$id_chantier = (int)$_GET['id_chantier'] ?? 0;

// Récupère la tâche
$stmt = $pdo->prepare("SELECT * FROM tache WHERE id_tache = ?");
$stmt->execute([$id_tache]);
$tache = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tache) die("Tâche introuvable.");

// Récupère les ouvriers du chantier
$sql = "SELECT u.id_user, u.nom
        FROM utilisateurs u
        JOIN affectation_chantier a ON u.id_user = a.utilisateursid_utilisateur
        WHERE a.chantierid_chantier = ? AND a.roleid_role = 3";
$stmt2 = $pdo->prepare($sql);
$stmt2->execute([$id_chantier]);
$ouvriers = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Récupère les tâches modèles
$stmt3 = $pdo->prepare("SELECT * FROM tache_modele");
$stmt3->execute();
$tachesModele = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE tache SET
                nom                    = :nom,
                ordre                        = :ordre,
                statut                       = :statut,
                date_debut_prevue            = :debut,
                date_fin_prevue              = :fin,
                date_debut_reelle            = :debut_reelle,
                date_fin_reelle              = :fin_reelle,
                tache_modeleid_tache_modele  = :modele,
                utilisateursid_utilisateur   = :ouvrier
            WHERE id_tache = :id_tache";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom'         => $_POST['nom'],
        ':ordre'       => $_POST['ordre'] ?: null,
        ':statut'      => $_POST['statut'],
        ':debut'       => $_POST['date_debut_prevue'] ?: null,
        ':fin'         => $_POST['date_fin_prevue'] ?: null,
        ':debut_reelle'=> $_POST['date_debut_reelle'] ?: null,
        ':fin_reelle'  => $_POST['date_fin_reelle'] ?: null,
        ':modele'      => $_POST['tache_modeleid_tache_modele'] ?: null,
        ':ouvrier'     => $_POST['utilisateursid_utilisateur'] ?: null,
        ':id_tache'    => $id_tache
    ]);

    header('Location: Chef.php?id_chantier=' . $id_chantier);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une tâche</title>
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
<div class="container">
    <h2>Modifier la tâche</h2>
    <form method="POST">
        <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">

        <label>Nom de la tâche :</label>
        <input type="text" name="nom" 
               value="<?= htmlspecialchars($tache['nom']) ?>" required><br><br>

        <label>Ordre :</label>
        <input type="number" name="ordre" 
               value="<?= $tache['ordre'] ?>"><br><br>

        <label>Statut :</label>
        <select name="statut">
            <option value="en attente"  <?= $tache['statut'] === 'en attente'  ? 'selected' : '' ?>>En attente</option>
            <option value="en cours"    <?= $tache['statut'] === 'en cours'    ? 'selected' : '' ?>>En cours</option>
            <option value="termine"     <?= $tache['statut'] === 'termine'     ? 'selected' : '' ?>>Terminé</option>
            <option value="bloque"      <?= $tache['statut'] === 'bloque'      ? 'selected' : '' ?>>Bloqué</option>
        </select><br><br>

        <label>Date début prévue :</label>
        <input type="date" name="date_debut_prevue" 
               value="<?= $tache['date_debut_prevue'] ?>"><br><br>

        <label>Date fin prévue :</label>
        <input type="date" name="date_fin_prevue" 
               value="<?= $tache['date_fin_prevue'] ?>"><br><br>

        <label>Date début réelle :</label>
        <input type="date" name="date_debut_reelle" 
               value="<?= $tache['date_debut_reelle'] ?>"><br><br>

        <label>Date fin réelle :</label>
        <input type="date" name="date_fin_reelle" 
               value="<?= $tache['date_fin_reelle'] ?>"><br><br>

        <label>Tâche modèle :</label>
        <select name="tache_modeleid_tache_modele">
            <option value="">-- Aucun modèle --</option>
            <?php foreach ($tachesModele as $tm): ?>
                <option value="<?= $tm['id_tache_modele'] ?>"
                    <?= $tache['tache_modeleid_tache_modele'] == $tm['id_tache_modele'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tm['nom_tache']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Affecter à un ouvrier :</label>
        <select name="utilisateursid_utilisateur">
            <option value="">-- Aucun ouvrier --</option>
            <?php foreach ($ouvriers as $ouvrier): ?>
                <option value="<?= $ouvrier['id_user'] ?>"
                    <?= $tache['utilisateursid_utilisateur'] == $ouvrier['id_user'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ouvrier['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="config_chantier.php?id_chantier=<?= $id_chantier ?>">Annuler</a>
    </form>
</div>
</body>
</html>