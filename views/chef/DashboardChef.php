<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($chantier['nom']) ?></title>
    <link rel="stylesheet" href="styles/chef.css">
</head>
<body>
    <div class="dashboard-container">

    <nav class="sidebar">

        <div class="sidebar-logo">
            <div class="logo-icon">CI</div>
        </div>

        <button class="nav-btn active" data-tab="chantier">
            🏗 Chantier
        </button>

        <button class="nav-btn" data-tab="taches">
            📋 Tâches
        </button>

        <button class="nav-btn" data-tab="ouvriers">
            👷 Ouvriers
        </button>

        <button class="nav-btn" data-tab="avancement">
            📈 Avancement
        </button>

        <button class="nav-btn" data-tab="incidents">
            ⚠ Incidents
        </button>

        <div class="sidebar-footer">
            <span class="user-info">

                <button class="header-icon">
                    👤
                </button>
                <?= htmlspecialchars($_SESSION['nom']) ?>
            </span>
            
        </div>
    </nav>

    <div class="main-area">

        <header class="topbar">

            <div class="header-left">
                <h1><?= htmlspecialchars($chantier['nom']) ?></h1>
            </div>

            <div class="header-actions">

                <button class="header-icon">
                    🔔
                </button>

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