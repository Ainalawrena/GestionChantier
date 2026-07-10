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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <li><a href="A_propos.php">À propos</a></li>
        <li><a href="Fonctionnalites.php">Fonctionnalités</a></li>
        <li><a href="Contact.php" class="active">Contact</a></li>
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

    <!-- Formulaire -->
    <div class="contact-form">
        <h2>Envoyez-moi un message</h2>

        <?php if ($succes): ?>
            <div class="alert-succes">
                <i class="fa-solid fa-circle-check"></i>
                Message envoyé avec succès ! Je vous répondrai bientôt.
            </div>
        <?php endif; ?>

        <?php if ($erreur === 'champs_vides'): ?>
            <div class="alert-erreur">
                <i class="fa-solid fa-circle-xmark"></i>
                Veuillez remplir tous les champs obligatoires.
            </div>
        <?php elseif ($erreur === 'envoi_echoue'): ?>
            <div class="alert-erreur">
                <i class="fa-solid fa-circle-xmark"></i>
                Erreur lors de l'envoi. Veuillez réessayer.
            </div>
        <?php endif; ?>

        <form action="index.php?page=contact&action=envoyer" method="POST" id="contactForm">

            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom complet <span class="required">*</span></label>
                    <input type="text" id="nom" name="nom"
                           placeholder="Votre nom complet"
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="societe">Société</label>
                    <input type="text" id="societe" name="societe"
                           placeholder="Nom de votre entreprise"
                           value="<?= htmlspecialchars($_POST['societe'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email"
                       placeholder="votre@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="tel">Téléphone</label>
                <input type="tel" id="tel" name="tel"
                       placeholder="+261 XX XX XXX XX"
                       value="<?= htmlspecialchars($_POST['tel'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="sujet">Sujet <span class="required">*</span></label>
                <select id="sujet" name="sujet" required>
                    <option value="" disabled selected>-- Choisissez un sujet --</option>
                    <option value="Démonstration du logiciel">Démonstration du logiciel</option>
                    <option value="Demande de devis">Demande de devis</option>
                    <option value="Support technique">Support technique</option>
                    <option value="Partenariat">Partenariat</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Votre message <span class="required">*</span></label>
                <textarea id="message" name="message" rows="6"
                          placeholder="Décrivez votre besoin ou votre projet..."
                          required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="cta-btn">
                <i class="fa-solid fa-paper-plane"></i> Envoyer le message
            </button>

        </form>
    </div>

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