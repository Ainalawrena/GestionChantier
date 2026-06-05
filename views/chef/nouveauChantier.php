<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../styles/login.css">
    </head>

    <body>
        <div class="container">    
            <h2>Config Chantier</h2>
            <form method="POST" action="index.php?page=chantier&action=enregistrerNouveauChantier">
                <label for="nom">Nom : </label>
                <input type="text" name="nom" id="nom" required><br><br>

                <label for="debut">Date debut prevue : </label>
                <input type="date" name="debut" id="debut" required><br><br>

                <label for="fin">Date fin prevue :</label>
                <input type="date" name="fin" id="fin" required><br><br>

                <label for="statut">Statut :</label>
                <input type="text" name="statut" id="statut" required><br><br><label for="id_modele">Modèle :</label>
                    <select name="id_modele" id="id_modele" required>
                        <option value="">-- Choisir un modèle --</option>
                        <?php foreach ($modeles as $modele): ?>
                            <option value="<?= $modele['id_modele'] ?>">
                                <?= $modele['nom'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br><br>

                <label for="equipe">Équipe :</label>
                <select name="equipe[]" id="equipe" multiple required>
                    <?php foreach ($utilisateurs as $user): ?>
                        <option value="<?= $user['id_user'] ?>">
                            <?= $user['nom'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small>Maintenez Ctrl pour sélectionner plusieurs personnes</small>
                <br><br>

                <input type="submit" value="enregistrer">
            </form>
        </div>

        <a class="btn-retour" href="#" class="btn-retour">
        ← Retour
        </a>
    </body>
</html>