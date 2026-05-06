<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.html');
    exit;
}

if ($_SESSION['role'] !== 'Chef de chantier') {
    header('Location: ../pages/login.html');
    exit;
}

require '../includes/config.php';

try {
    $sql = "SELECT c.id_chantier, c.nom, r.libelle
        FROM chantier c
        JOIN affectation_chantier a ON c.id_chantier = a.chantierid_chantier
        JOIN role r ON a.roleid_role = r.id_role
        WHERE a.utilisateursid_utilisateur = :id_chef";
        
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_chef' => $_SESSION['user_id']]);
    $chantiers = $stmt->fetchAll();
} catch (PDOException $e) {
    $chantiers = []; // table inexistante → liste vide
    // echo $e->getMessage(); // décommenter pour voir l'erreur exacte
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Chef de Chantier</title>
    <link rel="stylesheet" href="../styles/choice.css">
</head>
<body>

<nav>
    <h1>Gestion Chantier</h1>
    <div>
        Connecté : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong>
        &nbsp;|&nbsp;
        <a href="logout.php">Se déconnecter</a>
    </div>
</nav>

<div class="container">

    <div class="welcome">
        <h2>Bonjour, <?= htmlspecialchars($_SESSION['nom']) ?></h2>
        <p>Que souhaitez-vous faire aujourd'hui ?</p>
    </div>

    <div class="card">

        <!-- Boutons choix -->
        <div class="actions">
            <button class="btn btn-primary" onclick="afficherSelect()">
                Ouvrir un chantier existant
            </button>
            <button class="btn btn-secondary" onclick="nouveauChantier()">
                 Créer un nouveau chantier
            </button>
        </div>

        <!-- Zone chantiers -->
        <div class="select-group" id="selectGroup">
            <label>Sélectionner un chantier :</label>

            <?php if (!empty($chantiers)): ?>
                <!--  Chantiers disponibles → select dynamique -->
                <select id="listeChantiers" name="id_chantier">
                    <option value="">-- Choisir un chantier --</option>
                    <?php foreach ($chantiers as $chantier): ?>
                        <option value="<?= $chantier['id_chantier'] ?>">
                            <?= htmlspecialchars($chantier['nom'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn-ouvrir" onclick="ouvrirChantier()">
                    Ouvrir ce chantier →
                </button>

            <?php else: ?>
                <!--  Aucun chantier → liste simple -->
                <div class="liste-vide">
                    <p>Aucun chantier disponible pour le moment.</p>
                    <ul>
                        <li><span></span> Vous n'avez pas encore de chantier assigné.</li>
                        <li><span></span> Créez votre premier chantier avec le bouton ci-dessus.</li>
                        <li><span></span> Ou contactez un administrateur pour vous en attribuer un.</li>
                    </ul>
                    <p class="hint">Une fois un chantier créé, il apparaîtra ici automatiquement.</p>
                </div>

            <?php endif; ?>
        </div>

    </div>
</div>
<a class="btn-retour" href="login.html" class="btn-retour">
        ← Retour
</a>
<script>
    function afficherSelect() {
        document.getElementById('selectGroup').classList.add('visible');
    }

    function nouveauChantier() {
        window.location.href = 'nouveau_chantier.php';
    }

    function ouvrirChantier() {
        const select = document.getElementById('listeChantiers');
        const id = select.value;

        if (!id) {
            alert('Veuillez sélectionner un chantier.');
            return;
        }

        window.location.href = 'Chef.php?id_chantier=' + id;
    }
</script>

</body>
</html>