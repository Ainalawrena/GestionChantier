<div id="taches" class="tab-content">
    <h2>Gestion des Tâches</h2>

    <h3>Modèles de tâches</h3>
    <table class="tableau">
        <thead>
            <tr>
                <th>Ordre</th>
                <th>Nom</th>
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
                        <td><?= $tache['date_debut_prevue'] ?? '-' ?></td>
                        <td><?= $tache['date_fin_prevue'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($tache['nom_ouvrier'] ?? '-') ?></td>
                        <td>
                            <a href="modifier_tache.php?id_tache=<?= $tache['id_tache'] ?>&id_chantier=<?= $id_chantier ?>" class="btn-modifier">Modifier</a>
                            <a href="supprimer_tache.php?id_tache=<?= $tache['id_tache'] ?>&id_chantier=<?= $id_chantier ?>" onclick="return confirm('Supprimer ?')" class="btn-supprimer">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">Aucune tâche créée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <button class="btn btn-primary" onclick="afficherFormulaireNouveauTache()">+ Nouveau tâche</button>

    <div id="formulaireTache" style="display:none; margin-top:20px;">
        <h3>Créer une nouvelle tâche</h3>
        <form method="POST" action="creer_tache.php">
            <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">

            <label>Nom :</label>
            <input type="text" name="nom" required><br><br>

            <label>Ordre :</label>
            <input type="number" name="ordre"><br><br>

            <label>Statut :</label>
            <select name="statut">
                <option value="en attente">En attente</option>
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
                <option value="">-- Aucun --</option>
                <?php foreach ($tachesModele as $tm): ?>
                    <option value="<?= $tm['id_tache_modele'] ?>">
                        <?= htmlspecialchars($tm['nom_tache']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Affecter à :</label>
            <select name="utilisateursid_utilisateur">
                <option value="">-- Aucun --</option>
                <?php foreach ($ouvriers as $ouvrier): ?>
                    <option value="<?= $ouvrier['id_user'] ?>">
                        <?= htmlspecialchars($ouvrier['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <button type="submit" class="btn btn-primary">Créer</button>
            <button type="button" onclick="cacherFormulaireNouveauTache()">Annuler</button>
        </form>
    </div>
</div>