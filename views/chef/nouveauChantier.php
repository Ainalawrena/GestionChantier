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

                <input type="submit" value="enregistrer">
            </form>
        </div>

        <a class="btn-retour" href="index.php?page=chantier&action=choice" class="btn-retour">
        ← Retour
        </a>
    </body>
</html>