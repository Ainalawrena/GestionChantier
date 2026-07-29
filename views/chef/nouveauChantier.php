<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../styles/nouveau.css">
    </head>

    <body>
        <div class="container">    
            <h2>Config Chantier</h2>

            <?php if (isset($_SESSION['erreur'])): ?>
                <div class="alert-erreur">
                    <?= htmlspecialchars($_SESSION['erreur']) ?>
                </div>
                <?php unset($_SESSION['erreur']); ?>
            <?php endif; ?>

            <form method="POST" action="index.php?page=chantier&action=enregistrerNouveauChantier" id="formNouveauChantier">

                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" required>
                <label for="modele">Modèle de chantier :</label>
                <select name="id_modele" id="modele" required>

                    <option value="">
                        -- Choisir un modèle --
                    </option>

                    <?php foreach ($modeles as $modele): ?>
                    
                        <option value="<?= $modele['id_modele'] ?>">
                            <?= htmlspecialchars($modele['nom']) ?>
                        </option>
                    
                    <?php endforeach; ?>
                    
                </select>
                    
                <label for="debut">Date debut prevue :</label>
                <input type="date" name="debut" id="debut" min="<?= date('Y-m-d') ?>" required>

                <label for="fin">Date fin prevue :</label>
                <input type="date" name="fin" id="fin" min="<?= date('Y-m-d') ?>"required>

                <input type="submit" value="enregistrer">
                    
            </form>
        </div>

        <a class="btn-retour" href="index.php?page=chantier&action=choice">
        ← Retour
        </a>

        <script>
            document.getElementById('formNouveauChantier').addEventListener('submit', function(e) {
                const debutInput = document.getElementById('debut');
                const finInput   = document.getElementById('fin');

                const debut = new Date(debutInput.value);
                const fin   = new Date(finInput.value);
                const aujourdhui = new Date();
                aujourdhui.setHours(0, 0, 0, 0);

                if (debut < aujourdhui) {
                    e.preventDefault();
                    alert("La date de début ne peut pas être dans le passé.");
                    return;
                }

                if (fin <= debut) {
                    e.preventDefault();
                    alert("La date de fin doit être après la date de début.");
                    return;
                }

                const dureeMinimum = new Date(debut);
                dureeMinimum.setMonth(dureeMinimum.getMonth() + 1);

                if (fin < dureeMinimum) {
                    e.preventDefault();
                    alert("La durée du chantier doit être d'au moins 1 mois.");
                    return;
                }
            });
        </script>
    </body>
</html>