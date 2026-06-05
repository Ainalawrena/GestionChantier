<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($chantier['nom']) ?></title>
    <link rel="stylesheet" href="styles/chef.css">
    <!-- Ajoute dans le <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">

    <nav class="sidebar">

        <div class="sidebar-logo">
            <div class="logo-icon">CI</div>
        </div>

        <button class="nav-btn active" data-tab="chantier">
            <i class="fa-solid fa-helmet-safety"></i> Chantier
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
            <span class="user-info">

                <button class="header-icon">
                    <img src="Images/user.jpg" id="imgUser" alt="Utilisateur">
                </button>
                <?= htmlspecialchars(SessionManager::getNom()) ?>
            </span>
            <a href="index.php?page=auth&action=logout" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Déconnecter
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
            <?php require __DIR__ . '/tabs/tab_taches_chef.php'; ?>
            <?php require __DIR__ . '/tabs/tab_ouvriers.php'; ?>
            <?php require __DIR__ . '/tabs/tab_avancement.php'; ?>
            <?php require __DIR__ . '/tabs/tab_incidents.php'; ?>

        </main>

    </div>

    </div>

    <script src="scripts/chef.js"></script>
</body>
</html>