<?php
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
        <p>
            <?php if ($role === 'Chef de chantier'): ?>
                Gérez vos chantiers ou créez-en un nouveau.
            <?php elseif ($role === 'Ouvrier'): ?>
                Consultez vos chantiers et tâches assignées.
            <?php elseif ($role === 'Architecte'): ?>
                Consultez et validez les tâches de vos chantiers.
            <?php endif; ?>
        </p>
    </div>

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
                    <?php if ($role === 'Chef de chantier'): ?>
                        <li>Vous n'avez pas encore de chantier assigné.</li>
                        <li>Créez votre premier chantier avec le bouton ci-dessous.</li>
                        <li>Ou contactez un administrateur pour vous en attribuer un.</li>
                    <?php elseif ($role === 'Ouvrier'): ?>
                        <li>Vous n'avez pas encore de tâche assignée.</li>
                        <li>Contactez votre chef de chantier pour être affecté.</li>
                    <?php elseif ($role === 'Architecte'): ?>
                        <li>Aucun chantier ne nécessite votre validation pour le moment.</li>
                        <li>Contactez un chef de chantier pour être affecté.</li>
                    <?php elseif ($role === 'Administrateur'): ?>
                        <li>Aucun chantier n'existe encore dans le système.</li>
                        <li>Invitez un chef de chantier à créer un chantier.</li>
                    <?php endif; ?>
                </ul>
                <p class="hint">
                    <?php if ($role === 'Chef de chantier'): ?>
                        Une fois un chantier créé, il apparaîtra ici automatiquement.
                    <?php else: ?>
                        Une fois affecté à un chantier, il apparaîtra ici automatiquement.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
        </div>

        <?php if ($role === 'Chef de chantier'): ?>
            <div class="divider">ou</div>
            <button class="btn btn-secondary" onclick="nouveauChantier()">
                ➕ Créer un nouveau chantier
            </button>
        <?php endif; ?>
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
