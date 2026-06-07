<div id="taches" class="tab-content">
    <h2>Mes Tâches</h2>

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
                        <td><?= $tache['ordre'] ?></td>
                        <td><?= htmlspecialchars($tache['nom']) ?></td>
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
                        <td>
                            <div class="progress-bar">
                                <div class="progress-fill"
                                     style="width: <?= $tache['pourcentage'] ?>%">
                                </div>
                            </div>
                            <small>✅ Validé : <?= $tache['pourcentage'] ?>%</small><br>
                            <?php if (($tache['dernier_soumis'] ?? 0) > $tache['pourcentage']): ?>
                                <small>⏳ Soumis : <?= $tache['dernier_soumis'] ?>%</small>
                            <?php endif; ?>
                        </td>
                        <td><?= $tache['date_debut_prevue'] ?? '-' ?></td>
                        <td><?= $tache['date_fin_prevue'] ?? '-' ?></td>
                        <td>
                            <button class="btn-modifier"
                                onclick="ouvrirModalAvancement(
                                    <?= $tache['id_tache'] ?>,
                                    '<?= htmlspecialchars($tache['nom']) ?>'
                                )">
                                <i class="fa-solid fa-pen"></i> Mettre à jour
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>Aucune tâche assignée pour le moment.</p>
    <?php endif; ?>
</div>

<!-- MODAL Avancement -->
<div class="modal-overlay" id="modalAvancement">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fa-solid fa-chart-line"></i> Mettre à jour : <span id="nomTache"></span></h3>
            <button class="modal-close" onclick="fermerModal('modalAvancement')">✕</button>
        </div>

        <form method="POST" action="index.php?page=avancement&action=ajouter">
            <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">
            <input type="hidden" name="id_tache" id="idTache">

            <label>Pourcentage :</label>
            <input type="number" name="pourcentage" id="pourcentageTache"
                   min="0" max="100" required>

            <label>Commentaire :</label>
            <textarea name="commentaire" rows="3"
                      placeholder="Décrivez l'avancement..."></textarea>

            <div class="modal-footer">
                <button type="button" class="btn-annuler"
                        onclick="fermerModal('modalAvancement')">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Soumettre
                </button>
            </div>
        </form>
    </div>
</div>