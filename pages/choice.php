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
    $chantiers = []; 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Chef de Chantier</title>
    <link rel="stylesheet" href="../styles/choice.css">
</head>
<body>

  <!-- HEADER -->
  <header>
      <div class="logo">
        <span class="logo-icon">CI</span>
        <h1>Construct <em>it</em></h1>
      </div>

      <nav class="btn-row">
        <div class="user-info">
            <span>Connecté : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong></span>
            <a href="logout.php" class="logout-link">Se déconnecter</a>
        </div>
      </nav>

    </div>
  </header>

<div class="container">

    <div class="welcome">
        <h2>Bonjour, <?= htmlspecialchars($_SESSION['nom']) ?></h2>
        <p>Que souhaitez-vous faire aujourd'hui ?</p>
    </div>

    <!-- CORRECTION : Suppression de la balise fermante parasite ici -->
    <div class="card">         
        
        <!-- Zone chantiers -->
        <div class="select-group" id="selectGroup">
            <label for="listeChantiers">Sélectionner un chantier actif :</label>

            <?php if (!empty($chantiers)): ?>
                <div class="action-row">
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
                </div>

            <?php else: ?>
                <div class="liste-vide">
                    <p class="warning-title">⚠️ Aucun chantier disponible pour le moment.</p>
                    <ul>
                        <li>Vous n'avez pas encore de chantier assigné.</li>
                        <li>Créez votre premier chantier avec le bouton ci-dessous.</li>
                        <li>Ou contactez un administrateur pour vous en attribuer un.</li>
                    </ul>
                    <p class="hint">Une fois un chantier créé, il apparaîtra ici automatiquement.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="divider">ou</div>

        <button class="btn btn-secondary" onclick="nouveauChantier()">
                 ➕ Créer un nouveau chantier
        </button>
    </div>

    <a class="btn-retour" href="login.html">← Retour à la connexion</a>
</div>

<script>
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
