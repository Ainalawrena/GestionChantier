<?php
session_start();
require '../includes/config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

    $sql  = "SELECT * FROM modele";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $modeles= $stmt->fetchAll();

    $sql2 = "SELECT id_user, nom FROM utilisateurs";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute();
    $utilisateurs = $stmt2->fetchAll();

    //Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom'] ?? '');
    $debut     = $_POST['debut'] ?? '';
    $fin       = $_POST['fin'] ?? '';
    $statut    = $_POST['statut'] ?? '';
    $id_modele = $_POST['id_modele'] ?? '';
    $equipe    = $_POST['equipe'] ?? [];

    // 1. Crée le chantier
    $sql3 = "INSERT INTO chantier (nom, date_debut_prevu, date_fin_prevu, statut, modeleid_modele) 
             VALUES (:nom, :debut, :fin, :statut, :id_modele)";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([
        ':nom'       => $nom,
        ':debut'     => $debut,
        ':fin'       => $fin,
        ':statut'    => $statut,
        ':id_modele' => $id_modele
    ]);
    
    // Récupère l'id du chantier qu'on VIENT d'insérer
    $stmt_id = $pdo->query("SELECT currval('chantier_id_chantier_seq')");
    $id_chantier = $stmt_id->fetchColumn();

    $sql4 = "INSERT INTO affectation_chantier 
             (utilisateursid_utilisateur, chantierid_chantier, roleid_role) 
             VALUES (:id_user, :id_chantier, 2)";
    $stmt4 = $pdo->prepare($sql4);
    $stmt4->execute([
        ':id_user'     => $_SESSION['user_id'],
        ':id_chantier' => $id_chantier
    ]);

    foreach ($equipe as $id_user) {
        if ($id_user == $_SESSION['user_id']) continue;
        
        $sql5 = "INSERT INTO affectation_chantier 
                 (utilisateursid_utilisateur, chantierid_chantier, roleid_role) 
                 VALUES (:id_user, :id_chantier, 3)";
        $stmt5 = $pdo->prepare($sql5);
        $stmt5->execute([
            ':id_user'     => $id_user,
            ':id_chantier' => $id_chantier
        ]);
    }

    // Récupère les tâches modèles du modèle choisi
    $sqlTachesModele = "SELECT * FROM tache_modele WHERE modeleid_modele = :id_modele";
    $stmtTM = $pdo->prepare($sqlTachesModele);
    $stmtTM->execute([':id_modele' => $id_modele]);
    $tachesModele = $stmtTM->fetchAll(PDO::FETCH_ASSOC);

    // Crée automatiquement chaque tâche
    foreach ($tachesModele as $tachem) {
        $pdo->prepare("INSERT INTO tache 
            (nom, ordre, statut, pourcentage, chantierid_chantier, tache_modeleid_tache_modele)
            VALUES (?, ?, 'en attente', 0, ?, ?)")
            ->execute([
                $tachem['nom'],
                $tachem['ordre'],
                $id_chantier,
                $tachem['id_tache_modele']
            ]);
    }
    // ====================== REDIRECTION ======================
    header("Location: Chef.php?id_chantier=" . $id_chantier);
    exit();

    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../styles/login.css">
    </head>

    <body>
        <div class="container">    
            <h2>Config Chantier</h2>
            <form method="POST">
                <label for="nom">Nom : </label>
                <input type="text" name="nom" id="nom" required><br><br>

                <label for="debut">Date debut prevue : </label>
                <input type="date" name="debut" id="debut" required><br><br>

                <label for="fin">Date fin prevue :</label>
                <input type="date" name="fin" id="fin" required><br><br>

                <label for="statut">Statut :</label>
                <input type="text" name="statut" id="statut" required><br><br><label for="id_modele">Modèle :</label>
                    <select name="id_modele" id="id_modele" required>
                        <option value="">-- Choisir un modèle --</option>
                        <?php foreach ($modeles as $modele): ?>
                            <option value="<?= $modele['id_modele'] ?>">
                                <?= $modele['nom'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br><br>

                <label for="equipe">Équipe :</label>
                <select name="equipe[]" id="equipe" multiple required>
                    <?php foreach ($utilisateurs as $user): ?>
                        <option value="<?= $user['id_user'] ?>">
                            <?= $user['nom'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small>Maintenez Ctrl pour sélectionner plusieurs personnes</small>
                <br><br>

                <input type="submit" value="enregistrer">
            </form>
        </div>

        <a class="btn-retour" href="choice.php" class="btn-retour">
        ← Retour
        </a>
    </body>
</html>