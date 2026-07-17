<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/chef.css">
    <link rel="stylesheet" href="styles/tabAdmin.css">
    <!-- Ajoute dans le <head> -->
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">

    <nav class="sidebar">

        <div class="sidebar-logo">
            <div class="logo-icon">CI</div>
        </div>

        <button class="nav-btn active" data-tab="dashboard">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </button>

        <button class="nav-btn" data-tab="chantier">
            <i class="fa-solid fa-helmet-safety"></i> Chantiers
        </button>

        <button class="nav-btn" data-tab="taches">
            <i class="fa-solid fa-list-check"></i> Tâches
        </button>

        <button class="nav-btn" data-tab="ouvriers">
            <i class="fa-solid fa-users-gear"></i> Ouvriers
        </button>

        <button class="nav-btn" data-tab="avancement">
            <i class="fa-solid fa-chart-line"></i> Avancement
        </button>

        <button class="nav-btn" data-tab="incidents">
            <i class="fa-solid fa-triangle-exclamation"></i> Incidents
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
            <?php require __DIR__ . '/tabs/tab_dashboard.php'; ?>
            <?php require __DIR__ . '/tabs/tab_chantier.php'; ?>
            <?php require __DIR__ . '/tabs/tab_tache_admin.php'; ?>
            <?php require __DIR__ . '/tabs/tab_ouvriers.php'; ?>
            <?php require __DIR__ . '/tabs/tab_avancement.php'; ?>
            <?php require __DIR__ . '/tabs/tab_incidents.php'; ?>

        </main>

    </div>

    </div>

    <script src="scripts/global.js"></script>
    <script src="scripts/admin.js"></script>
</body>
</html>