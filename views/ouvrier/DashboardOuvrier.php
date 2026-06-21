
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($chantier['nom']) ?></title>
    <link rel="stylesheet" href="styles/chef.css">
    <link rel="stylesheet" href="style/ouvrier.css">
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
         
        <button class="nav-btn" data-tab="tachesAvancement">
             <i class="fa-solid fa-list-check"></i> Tâches et avancement
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
            <?php require __DIR__ . '/tabs/tab_chantier.php'; ?>
            <?php require __DIR__ . '/tabs/tab_avancement.php'; ?>
            <?php require __DIR__ . '/tabs/tab_incident.php'; ?>

        </main>

    </div>

    </div>

    <script src="scripts/chef.js"></script>
</body>
</html>