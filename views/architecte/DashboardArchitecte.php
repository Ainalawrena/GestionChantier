<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($chantier['nom']) ?></title>
    <link rel="stylesheet" href="styles/chef.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
</head>
<body>

    <div class="dashboard">
        <nav class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-icon">CI</div>
            </div>

            <button class="nav-btn active" data-tab="chantier">
                <i class="fa-solid fa-helmet-safety"></i> Chantier
            </button>

            <button class="nav-btn" data-tab="validation">
                <i class="fa-solid fa-circle-check"></i>Validation Avancement
            </button>
            
            <div class="sidebar-footer">
            
                <img src="Images/user.jpg" alt="Utilisateur" class="user-avatar">
                <div class="user-details">
                    <span class="user-name"><?= htmlspecialchars(SessionManager::getNom()) ?></span>
                    <span class="user-role"><?= htmlspecialchars(SessionManager::getRole()) ?></span>
                </div>
                <a href="index.php?page=auth&action=logout" class="btn-logout" title="Se déconnecter">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>     
        </div>
        </nav>

        <div class="main-area">
            <header class="topbar">
                <div class="header-left">
                    <h1><?= htmlspecialchars($chantier['nom']) ?></h1>
                </div>
            </header>
            <main class="content">
                <?php require __DIR__ . '/tabs/tab_chantier.php'; ?>
                <?php require __DIR__ . '/tabs/tab_validation.php'; ?>
            </main>
        </div>

    <script src="scripts/global.js"></script>
</body>
</html>