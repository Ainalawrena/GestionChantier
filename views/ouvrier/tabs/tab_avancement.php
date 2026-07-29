<div id="tachesAvancement" class="tab-content">
    <h2>Mettre à jour l'avancement</h2>

    <?php if (isset($erreur)): ?>
        <div class="alert-erreur"><?= $erreur ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error']) && $_GET['error'] === 'deps_not_finished'): ?>
        <div class="alert-erreur">
            Cette tâche est bloquée : une dépendance n'est pas encore terminée.
        </div>
    <?php endif; ?>

    <table class="tableau">
        <thead>
            <tr>
                <th>Ordre</th>
                <th>Nom</th>
                <th>Avancement actuel</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($mestaches as $tache): ?>
                <tr>
                    <td><?= $tache['ordre']?></td>
                    <td><?= htmlspecialchars($tache['nom'])?></td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $tache['pourcentage'] ?>%"></div>
                        </div>
                        <small>Validé : <?= $tache['pourcentage'] ?>%</small>
                    </td>
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
                        <span>
                            <?= htmlspecialchars($tache['statut']) ?>
                        </span>
                    </td>
                    
                    <!-- Colonne Action optimisée -->
                    <td>
                        <div class="actions-group">
                            <!-- 1. Bouton COMMENCER -->
                            <?php if ($tache['statut'] === 'en attente'): ?>
                                <a href="index.php?page=tache&action=commencer&id_tache=<?= $tache['id_tache'] ?>&id_chantier=<?= $chantier['id_chantier'] ?>"
                                   class="btn-action btn-commencer" title="Démarrer la tâche">
                                    <i class="fa-solid fa-play"></i> Commencer
                                </a>
                            <?php endif; ?>
                            
                            <!-- 2. Bouton DÉCLARER AVANCEMENT -->
                            <?php if ($tache['statut'] === 'en cours'): ?>
                                <button class="btn-action btn-modifier"
                                    onclick="afficherFormulaireAvancement(
                                        <?= $tache['id_tache'] ?>,
                                        '<?= htmlspecialchars($tache['nom']) ?>',
                                        <?= htmlspecialchars(json_encode($tache['jalons'] ?? [])) ?>
                                    )">
                                    <i class="fa-solid fa-chart-line"></i> Déclarer
                                </button>
                            <?php endif; ?>
                            
                            <!-- 3. Bouton VOIR DÉTAIL (icône seule, toujours visible) -->
                            <button class="action-icon-btn"
                                onclick="ouvrirModalDetailTache(<?= $tache['id_tache'] ?>, '<?= htmlspecialchars($tache['nom']) ?>')"
                                title="Voir détail">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    

    <div class="modal-overlay" id="modalAvancement">

    <div class="modal">

        <div class="modal-header">
            <h3>
                <i class="fa-solid fa-list-check"></i>
                Soumettre une étape :
                <span id="nomTache"></span>
            </h3>

            <button
                class="modal-close"
                onclick="fermerModal('modalAvancement')">
                ✕
            </button>
        </div>

        <form method="POST"
              action="index.php?page=avancement&action=ajouter">

            <input type="hidden"
                   name="id_chantier"
                   value="<?= $chantier['id_chantier'] ?>">

            <input type="hidden"
                   name="id_tache"
                   id="idTache">

            <label for="selectJalon">
                Sélectionner le jalon atteint :
            </label>

            <select name="id_jalon"
                    id="selectJalon"
                    required>
            </select>

            <label>Commentaire / Précisions :</label>

            <textarea name="commentaire"
                      rows="3"></textarea>

            <div class="modal-footer">

                <button type="button"
                        class="btn-annuler"
                        onclick="fermerModal('modalAvancement')">
                    Annuler
                </button>

                <button type="submit"
                        class="btn btn-primary">
                    Envoyer à l'architecte
                </button>

            </div>

        </form>

    </div>

</div>

</div>

<div class="modal-overlay" id="modalDetailTache">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fa-solid fa-eye"></i> Détail de la tâche</h3>
            <button class="modal-close" onclick="fermerModal('modalDetailTache')">✕</button>
        </div>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Nom : </span>
                <span class="detail-value" id="detailNom">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Statut : </span>
                <span class="detail-value" id="detailStatut">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Ordre : </span>
                <span class="detail-value" id="detailOrdre">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Avancement : </span>
                <span class="detail-value" id="detailPourcentage">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date début prévue : </span>
                <span class="detail-value" id="detailDebut">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date fin prévue : </span>
                <span class="detail-value" id="detailFin">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Dependences: </span>
                <span class="detail-value" id="detailDependence">-</span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Ouvrier assigné : </span>
                <span class="detail-value" id="detailOuvrier">-</span>
            </div>
        </div>


            <!-- Fermer à droite -->
            <button class="btn-annuler" onclick="fermerModal('modalDetailTache')">
                Fermer
            </button>
        </div>
</div>