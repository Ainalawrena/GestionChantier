<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Chef de chantier') {
    header('Location: ../pages/login.html');
    exit;
}

require '../includes/config.php';

$id_chantier = isset($_GET['id_chantier']) ? (int)$_GET['id_chantier'] : 0;

if ($id_chantier <= 0) {
    die("Aucun chantier sélectionné.");
}

// Récupération des informations du chantier
$stmt = $pdo->prepare("SELECT * FROM chantier WHERE id_chantier = ?");
$stmt->execute([$id_chantier]);
$chantier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chantier) {
    die("Chantier introuvable.");
}

// Recuperation des taches modele
$sql = "SELECT tm.nom , tm.ordre 
        FROM tache_modele tm
        JOIN modele m ON tm.modeleid_modele = m.id_modele
        JOIN chantier c ON m.id_modele = c.modeleid_modele
        WHERE c.id_chantier = ?";
$stmt1 = $pdo->prepare($sql);
$stmt1->execute([$id_chantier]);
$tachesModele= $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Récupération des tâches du chantier
$sql2 = "SELECT t.*, u.nom as nom_ouvrier
         FROM tache t
         LEFT JOIN utilisateurs u ON t.utilisateursid_utilisateur = u.id_user
         WHERE t.chantierid_chantier = ?";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([$id_chantier]);
$taches = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef - <?= htmlspecialchars($chantier['nom']) ?></title>
    
    <link rel="stylesheet" href="../styles/chef.css">
</head>
<body>
    <header>
        <div class="header-left">
            <h1><?= htmlspecialchars($chantier['nom']) ?></h1>
            <span class="status"><?= htmlspecialchars($chantier['statut']) ?></span>
        </div>
        <button class="btn-logout">Se déconnecter</button>
    </header>

    <div class="dashboard">
        <!-- Sidebar gauche -->
        <nav class="sidebar">
            <button class="nav-btn active" data-tab="chantier"> Chantier</button>
            <button class="nav-btn" data-tab="taches">Tâches</button>
            <button class="nav-btn" data-tab="ouvriers"> Ouvriers</button>
            <button class="nav-btn" data-tab="avancement"> Avancement</button>
            <button class="nav-btn" data-tab="incidents"> Incidents</button>
        </nav>

        <!-- Contenu principal -->
        <main class="content">

            <!-- SECTION CHANTIER -->
            <div id="chantier" class="tab-content active">
                <h2>Informations du Chantier</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <strong>Nom du chantier</strong>
                        <p><?= htmlspecialchars($chantier['nom']) ?></p>
                    </div>
                    <div class="info-card">
                        <strong>Date début prévue</strong>
                        <p><?= date('d/m/Y', strtotime($chantier['date_debut_prevu'])) ?></p>
                    </div>
                    <div class="info-card">
                        <strong>Date fin prévue</strong>
                        <p><?= date('d/m/Y', strtotime($chantier['date_fin_prevu'])) ?></p>
                    </div>
                    <div class="info-card">
                        <strong>Statut</strong>
                        <p class="status-badge"><?= htmlspecialchars($chantier['statut']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Autres sections (à remplir plus tard) -->
            <!-- SECTION TACHES -->
            <div id="taches" class="tab-content">
                <h2>Gestion des Tâches</h2>

                <!-- Tableau 1 : Tâches modèles -->
                <h3>Modèles de tâches disponibles</h3>
                <table class="tableau">
                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Nom de la tâche modèle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tachesModele as $tachem): ?>
                            <tr>
                                <td><?= $tachem['ordre'] ?></td>
                                <td><?= htmlspecialchars($tachem['nom']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                        
                <hr>
                        
                <!-- Tableau 2 : Tâches du chantier -->
                <h3>Tâches du chantier</h3>
                <table class="tableau">
                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Nom</th>
                            <th>Statut</th>
                            <th>Avancement</th>
                            <th>Date début prévue</th>
                            <th>Date fin prévue</th>
                            <th>Date début réelle</th>
                            <th>Date fin réelle</th>
                            <th>Ouvrier</th>
                            <th>Action</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($taches)): ?>
                            <?php foreach ($taches as $tache): ?>
                                <tr>
                                    <td><?= $tache['ordre'] ?></td>
                                    <td><?= htmlspecialchars($tache['nom']) ?></td>
                                    <td><?= htmlspecialchars($tache['statut']) ?></td>
                                    <td><?= $tache['pourcentage'] ?>%</td>
                                    <td><?= $tache['date_debut_prevue'] ?></td>
                                    <td><?= $tache['date_fin_prevue'] ?></td>
                                    <td><?= $tache['date_debut_reelle'] ?? '-' ?></td>
                                    <td><?= $tache['date_fin_reelle'] ?? '-' ?></td>
                                    <td><?= htmlspecialchars($tache['nom_ouvrier'] ?? '-') ?></td>
                                    <td>
                                        <a href="modifier_tache.php?id_tache=<?= $tache['id_tache'] ?>&id_chantier=<?= $id_chantier ?>"
                                            class="btn-modifier">
                                            Modifier
                                        </a>
                                        <a href="supprimer_tache.php?id_tache=<?= $tache['id_tache'] ?>&id_chantier=<?= $id_chantier ?>"
                                            onclick="return confirm('Supprimer cette tâche ?')"
                                            class="btn-supprimer">
                                            Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">Aucune tâche créée pour ce chantier.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                        
                <!-- Bouton nouveau tâche -->
                <button class="btn btn-primary" onclick="afficherFormulaireNouveauTache()">
                    + Nouveau tâche
                </button>
                        
                <!-- Formulaire création tâche (caché par défaut) -->
                <div id="formulaireTache" style="display:none; margin-top:20px;">
                    <h3>Créer une nouvelle tâche</h3>
                    <form method="POST" action="creer_tache.php">
                        <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">
                        
                        <label>Nom de la tâche :</label>
                        <input type="text" name="nom" required><br><br>
                        
                        <label>Ordre :</label>
                        <input type="number" name="ordre"><br><br>
                        
                        <label>Statut :</label>
                        <select name="statut">
                            <option value="en cours">En cours</option>
                            <option value="termine">Terminé</option>
                            <option value="bloque">Bloqué</option>
                        </select><br><br>
                        
                        <label>Date début prévue :</label>
                        <input type="date" name="date_debut_prevue"><br><br>
                        
                        <label>Date fin prévue :</label>
                        <input type="date" name="date_fin_prevue"><br><br>
                        
                        <label>Tâche modèle :</label>
                        <select name="tache_modeleid_tache_modele">
                            <option value="">-- Aucun modèle --</option>
                            <?php foreach ($tachesModele as $tachem): ?>
                                <option value="<?= $tachem['id_tache_modele'] ?>">
                                    <?= htmlspecialchars($tachem['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br><br>
                            
                        <label>Affecter à un ouvrier :</label>
                        <select name="utilisateursid_utilisateur">
                            <option value="">-- Choisir un ouvrier --</option>
                            <?php foreach ($ouvriers as $ouvrier): ?>
                                <option value="<?= $ouvrier['id_user'] ?>">
                                    <?= htmlspecialchars($ouvrier['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br><br>
                            
                        <button type="submit" class="btn btn-primary">Créer la tâche</button>
                        <button type="button" onclick="cacherFormulaireNouveauTache()">Annuler</button>
                    </form>
                </div>
            </div>

            <div id="ouvriers" class="tab-content">
                <h2>Équipe du Chantier</h2>
                <p>Liste des ouvriers à venir...</p>
            </div>

            <div id="avancement" class="tab-content">
                <h2>Avancement du Chantier</h2>
                <p>Graphiques et progression à venir...</p>
            </div>

            <div id="incidents" class="tab-content">
                <h2>Incidents</h2>
                <p>Gestion des incidents à venir...</p>
            </div>

        </main>
    </div>

    <script>
        // Gestion des onglets
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Retirer active de tous
                document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Ajouter active
                btn.classList.add('active');
                document.getElementById(btn.dataset.tab).classList.add('active');
            });
        });
        function afficherFormulaireNouveauTache() {
            document.getElementById('formulaireTache').style.display = 'block';
        }

        function cacherFormulaireNouveauTache() {
            document.getElementById('formulaireTache').style.display = 'none';
        }
    </script>
</body>
</html>
