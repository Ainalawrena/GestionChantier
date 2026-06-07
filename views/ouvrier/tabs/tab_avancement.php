<div id="tachesAvancement" class="tab-content">
    <h2>Mettre à jour l'avancement</h2>

    <?php if (isset($erreur)): ?>
        <div class="alert-erreur"><?= $erreur ?></div>
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
                        <span class="badge <?= $badgeClass ?>">
                            <?= htmlspecialchars($tache['statut']) ?>
                        </span>
                    </td>
                    
                    <!-- Colonne Action optimisée -->
                    <td>
                        <div class="actions-group">
                            <!-- 1. Bouton COMMENCER (Uniquement si en attente) -->
                            <?php if ($tache['statut'] === 'en attente'): ?>
                                <a href="index.php?page=tache&action=commencer&id_tache=<?= $tache['id_tache'] ?>&id_chantier=<?= $chantier['id_chantier'] ?>"
                                   class="btn-action btn-commencer" title="Démarrer la tâche">
                                    ▶ Commencer
                                </a>
                            <?php endif; ?>

                            <!-- 2. Bouton METTRE À JOUR (Uniquement si en cours) -->
                            <?php if ($tache['statut'] === 'en cours'): ?>
                                <button class="btn-action btn-modifier"
                                    onclick="afficherFormulaireAvancement(
                                        <?= $tache['id_tache'] ?>,
                                        '<?= htmlspecialchars($tache['nom']) ?>',
                                        <?= htmlspecialchars(json_encode($tache['jalons'] ?? [])) ?>
                                    )">
                                    Declarer un avancement
                                </button>
                            <?php endif; ?>

                            <!-- 3. Bouton VOIR PLUS (Toujours visible) -->
                            <button class="btn-action btn-voir" 
                                    onclick="ouvrirDetailsTache(<?= $tache['id_tache'] ?>)" title="Détails et historique">
                                 Voir plus
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