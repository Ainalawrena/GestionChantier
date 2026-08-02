<?php
$succes = isset($_GET['succes']);
$erreur = $_GET['erreur'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Construct IT</title>
    <link rel="stylesheet" href="fonts/fonts.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/contact.css">
</head>
<body>

<!-- Navigation -->
<nav>
    <a href="index.html" class="nav-logo">
        <div class="logo-icon">CI</div>
        <span class="logo-text">Construct <span>it</span></span>
    </a>
    <ul class="nav-links">
        <li><a href="index.html">Accueil</a></li>
        <li><a href="apropos.html">À propos</a></li>
        <li><a href="fonctionnalites.html">Fonctionnalités</a></li>
        <li><a href="contact.php" class="active">Contact</a></li>
    </ul>
    <div class="nav-right">
        <a href="index.php?page=auth&action=loginForm" class="btn-connexion">Connexion</a>
        <a href="index.php?page=auth&action=registerForm" class="btn-commencer-nav">Commencer</a>
    </div>
</nav>

<!-- Hero -->
<section class="contact-hero">
    <div class="hero-badge">Restons en contact</div>
    <h1>Une question ? Un projet ?<br><span>Je suis à votre écoute.</span></h1>
    <p>Vous pouvez me contacter directement pour toute information concernant Construct IT.</p>
</section>

<!-- Container principal -->
<div class="contact-container reveal">

   

    <!-- Informations personnelles -->
    <div class="contact-info">
        <h2>Mes coordonnées</h2>

        <div class="info-item">
            <span class="icon"><i class="fas fa-user"></i></span>
            <div>
                <h3>Nom</h3>
                <p><strong>ANDRIANARIMINO Tahiry Ny Aina Lawrena</strong></p>
            </div>
        </div>

        <div class="info-item">
            <span class="icon"><i class="fas fa-envelope"></i></span>
            <div>
                <h3>Email</h3>
                <p>nyaina.lawrena@gmail.com</p>
            </div>
        </div>

        <div class="info-item">
            <span class="icon"><i class="fas fa-phone"></i></span>
            <div>
                <h3>Téléphone</h3>
                <p>+261 34 04 364 70</p>
            </div>
        </div>

        <div class="info-item">
            <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
            <div>
                <h3>Ville</h3>
                <p>Antananarivo, Madagascar</p>
            </div>
        </div>

        <div class="info-item">
            <span class="icon"><i class="fas fa-graduation-cap"></i></span>
            <div>
                <h3>Statut</h3>
                <p>Projet de Fin d'Études - L3 MIT-MISA</p>
            </div>
        </div>
    </div>

</div>

<!-- CTA -->
<section class="cta reveal">
    <h2>Merci pour votre intérêt envers Construct IT</h2>
    <p>Je vous répondrai dans les plus brefs délais.</p>
    <a href="index.html" class="cta-btn">Retour à l'accueil</a>
</section>

<script>
    const elements = document.querySelectorAll(".reveal");
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) e.target.classList.add("show");
        });
    });
    elements.forEach(el => observer.observe(el));
</script>

</body>
</html>