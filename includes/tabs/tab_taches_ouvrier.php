
<div id="taches" class="tab-content">
    <h2>Mes taches</h2>

    <?php
        $sqltache = "
            SELECT t.*
            FROM tache t
            WHERE t.chantierid_chantier = ?
            AND t.utilisateursid_utilisateur = ?
            ORDER BY t.ordre
        ";

        $stmt = $pdo->prepare($sqltache);
        $stmt->execute([$id_chantier, $_SESSION['user_id']]);
        $mestaches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if (!empty($mestaches)): ?>
        <table class="tableau">
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Nom</th>
                    <th>Statut</th>
                    <th>Avancement</th>
                    <th>Date début prévue</th>
                    <th>Date fin prévue</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($mestaches as $tache): ?>
                    <tr>
                        <td><?= $tache['ordre']?></td>
                        <td><?= htmlspecialchars($tache['nom'])?></td>
                         <td>
                            <?php
                            $badgeClass = match($tache['statut']) {
                                'en attente' => 'badge-attente',
                                'en cours'   => 'badge-encours',
                                'termine'    => 'badge-termine',
                                'bloque'     => 'badge-bloque',
                                default      => ''
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= htmlspecialchars($tache['statut']) ?>
                            </span>
                        </td>
                        <td><?= $tache['date_debut_prevue'] ?? '-' ?></td>
                        <td><?= $tache['date_fin_prevue'] ?? '-' ?></td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-fill" 
                                     style="width: <?= $tache['pourcentage'] ?>%">
                                </div>
                            </div>
                            <small><?= $tache['pourcentage'] ?>%</small>
                        </td>
                        <td>
                            <button class="btn-modifier"
                                onclick="afficherFormulaireAvancement(
                                    <?= $tache['id_tache'] ?>,
                                    '<?= htmlspecialchars($tache['nom']) ?>',
                                    <?= $tache['pourcentage'] ?>
                                )">
                                Mettre à jour
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

         <!-- Formulaire mise à jour avancement -->
        <div id="formulaireAvancement" style="display:none; margin-top:20px;">
            <h3>Mettre à jour : <span id="nomTache"></span></h3>
            <form method="POST" action="#">
                <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">
                <input type="hidden" name="id_tache" id="idTache">

                <label>Statut :</label>
                <select name="statut">
                    <option value="en attente">En attente</option>
                    <option value="en cours">En cours</option>
                    <option value="termine">Terminé</option>
                    <option value="bloque">Bloqué</option>
                </select><br><br>

                <label>Avancement (%) :</label>
                <input type="number" name="pourcentage" id="pourcentageTache"
                       min="0" max="100" required><br><br>

                <label>Commentaire :</label>
                <textarea name="commentaire" rows="3" 
                          placeholder="Décrivez l'avancement..."></textarea><br><br>

                <label>Date début réelle :</label>
                <input type="date" name="date_debut_reelle"><br><br>

                <label>Date fin réelle :</label>
                <input type="date" name="date_fin_reelle"><br><br>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" 
                        onclick="cacherFormulaireAvancement()">Annuler</button>
            </form>
        </div>

    <?php else: ?>
        <p>Aucune tâche assignée pour le moment.</p>
    <?php endif; ?>
</div>