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
    <button class="btn btn-primary"
        onclick="ouvrirModal('modalIncident')">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Déclarer un incident
    </button>


    <!-- Modal Déclaration Incident -->
    <div class="modal-overlay" id="modalIncident">

        <div class="modal">

            <div class="modal-header">
                <h3>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Déclarer un incident
                </h3>

                <button class="modal-close"
                        onclick="fermerModal('modalIncident')">
                    ✕
                </button>
            </div>

            <form method="POST"
                  action="index.php?page=incident&action=declarer">

                <input type="hidden"
                       name="id_chantier"
                       value="<?= $id_chantier ?>">

                <label>Tâche concernée :</label>

                <select name="id_tache" required>
                    <option value="">-- Choisir une tâche --</option>

                    <?php foreach ($mestaches as $tache): ?>
                        <option value="<?= $tache['id_tache'] ?>">
                            <?= htmlspecialchars($tache['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Description :</label>

                <textarea name="description"
                          rows="4"
                          required></textarea>

                <label>Gravité :</label>

                <select name="gravite">
                    <option value="faible">Faible</option>
                    <option value="moyen">Moyenne</option>
                    <option value="critique">Critique</option>
                </select>

                <label>Impact :</label>

                <textarea name="impact"
                          rows="3"></textarea>

                <div class="modal-footer">

                    <button type="button"
                            class="btn-annuler"
                            onclick="fermerModal('modalIncident')">
                        Annuler
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Déclarer l'incident
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>