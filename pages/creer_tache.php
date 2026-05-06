<?php
session_start();
require '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO tache 
            (nom, ordre, statut, pourcentage, date_debut_prevue, date_fin_prevue,
             tache_modeleid_tache_modele, chantierid_chantier, utilisateursid_utilisateur)
            VALUES (:nom, :ordre, :statut, 0, :debut, :fin, :modele, :chantier, :ouvrier)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom'      => $_POST['nom'],
        ':ordre'    => $_POST['ordre'] ?: null,
        ':statut'   => $_POST['statut'],
        ':debut'    => $_POST['date_debut_prevue'] ?: null,
        ':fin'      => $_POST['date_fin_prevue'] ?: null,
        ':modele'   => $_POST['tache_modeleid_tache_modele'] ?: null,
        ':chantier' => $_POST['id_chantier'],
        ':ouvrier'  => $_POST['utilisateursid_utilisateur'] ?: null
    ]);

    header('Location: Chef.php?id_chantier=' . $_POST['id_chantier']);
    exit;
}
?>