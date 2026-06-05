<div id="incidents" class="tab-content">
    <h2>Incidents</h2>

    <!-- Liste incidents existants -->
    <table class="tableau">
        <thead>
            <tr>
                <th>Tâche</th>
                <th>Description</th>
                <th>Gravité</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($incidents)): ?>
                <?php foreach ($incidents as $incident): ?>
                    <tr>
                        <td><?= htmlspecialchars($incident['nom_tache']) ?></td>
                        <td><?= htmlspecialchars($incident['description']) ?></td>
                        <td><?= htmlspecialchars($incident['gravite']) ?></td>
                        <td>
                            <?php
                            $badgeClass = match($incident['statut']) {
                                'ouvert'   => 'badge-bloque',
                                'en cours' => 'badge-encours',
                                'resolu'   => 'badge-termine',
                                default    => ''
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= $incident['statut'] ?>
                            </span>
                        </td>
                        <td><?= $incident['date_incident'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Aucun incident déclaré.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Bouton déclarer -->
    <button class="btn btn-primary" onclick="afficherFormulaireIncident()">
        Déclarer un incident
    </button>

    <!-- Formulaire -->
    <div id="formulaireIncident" style="display:none; margin-top:20px;">
        <h3>Déclarer un incident</h3>
        <form method="POST" action="index.php?page=incident&action=declarer">
            <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">

            <label>Tâche concernée :</label>
            <select name="id_tache" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($mesTaches as $tache): ?>
                    <option value="<?= $tache['id_tache'] ?>">
                        <?= htmlspecialchars($tache['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Description :</label>
            <textarea name="description" required rows="3"></textarea><br><br>

            <label>Gravité :</label>
            <select name="gravite">
                <option value="faible">Faible</option>
                <option value="moyen">Moyen</option>
                <option value="critique">Critique</option>
            </select><br><br>

            <label>Impact :</label>
            <textarea name="impact" rows="2"></textarea><br><br>

            <button type="submit" class="btn btn-primary">Déclarer</button>
            <button type="button" onclick="cacherFormulaireIncident()">Annuler</button>
        </form>
    </div>
</div>