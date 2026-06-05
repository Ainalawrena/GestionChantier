<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($chantier['nom']) ?></title>
    <link rel="stylesheet" href="styles/chef.css">
</head>
<body>
    <header>
        <div class="header-left">
            <h1><?= htmlspecialchars($chantier['nom']) ?></h1>
            <span class="status"><?= htmlspecialchars($chantier['statut']) ?></span>
        </div>
        <button class="btn-logout" 
            onclick="window.location.href='index.php?page=auth&action=logout'">
            Se déconnecter
        </button>
    </header>

    <div class="dashboard">
        <nav class="sidebar">
            <button class="nav-btn active" data-tab="chantier">Chantier</button>
            <button class="nav-btn" data-tab="validation">Validation Avancement</button>
        </nav>

        <main class="content">
            <?php require __DIR__ . '/tabs/tab_chantier.php'; ?>
            <?php require __DIR__ . '/tabs/tab_validation.php'; ?>
        </main>
    </div>

    <script src="scripts/chef.js"></script>
</body>
</html>