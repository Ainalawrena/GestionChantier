<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../styles/nouveau.css">
    </head>

    <body>
        <div class="container">    
            <h2>Config Chantier</h2>
            <form method="POST" action="index.php?page=chantier&action=enregistrerNouveauChantier">

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
                <input type="date" name="debut" id="debut" required>

                <label for="fin">Date fin prevue :</label>
                <input type="date" name="fin" id="fin" required>

                <input type="submit" value="enregistrer">
                    
            </form>
        </div>

        <a class="btn-retour" href="index.php?page=chantier&action=choice" class="btn-retour">
        ← Retour
        </a>
    </body>
</html>