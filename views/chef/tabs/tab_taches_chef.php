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
                <th>Ouvrier</th>
                <th>Dependances</th>
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
                        <td><?= htmlspecialchars($tache['nom_ouvrier'] ?? '-') ?></td>
                        <td>
                            <?php
                            if (!empty($tache['dependances'])) {
                                    $noms = array_column(
                                    $tache['dependances'],
                                    'nom'
                                );
                                echo implode(', ', $noms);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="dropdown-item"
                                    onclick="ouvrirModalDetailTache(<?= $tache['id_tache'] ?>, '<?= htmlspecialchars($tache['nom']) ?>')">
                                    <i class="fa-solid fa-eye"></i> 
                                </button>
                                <button class="dropdown-item"
                                    onclick="ouvrirModalModifierTache(<?= $tache['id_tache'] ?>)">
                                    <i class="fa-solid fa-pen"></i> 
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">Aucune tâche créée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <button class="btn btn-primary" onclick="afficherFormulaireNouveauTache()">+ Nouveau tâche</button>


    <!-- MODAL Creer tache -->
    <div class="modal-overlay" id="modalTache">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-list-check"></i> Créer une nouvelle tâche</h3>
                <button class="modal-close" onclick="fermerModal('modalTache')">✕</button>
            </div>
                
            <form method="POST" action="index.php?page=tache&action=creerTache">
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

                <label>Dependences : </label>
                <select id="selectDependancesCreate" name="dependances[]" multiple style="width:100%;min-height:80px;">
                    <?php foreach ($taches as $opt): ?>
                        <option value="<?= $opt['id_tache'] ?>"><?= htmlspecialchars($opt['nom']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="modal-footer">
                    <button type="button" class="btn-annuler" onclick="fermerModal('modalTache')">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Créer
                    </button>
                </div>
            </form>
        </div>
    </div>

     <!-- MODAL Modifier tache -->
    <div class="modal-overlay" id="modalModifierTache">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-list-check"></i> Modifier une tâche</h3>
                <button class="modal-close" onclick="fermerModal('modalModifierTache')">✕</button>
            </div>
                
            <form method="POST" action="index.php?page=tache&action=modifierTache">
                <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">
                <input type="hidden" id="id_tache" name="id_tache">

                <label>Nom :</label>
                <input type="text" name="nom" value="<?= $tache['nom'] ?>" required><br><br>

                <label>Ordre :</label>
                <input type="number" name="ordre" value="<?= $tache['ordre'] ?>"><br><br>

               

                <label>Date début prévue :</label>
                <input type="date" name="date_debut_prevue" value="<?= $tache['date_debut_prevue'] ?>"><br><br>

                <label>Date fin prévue :</label>
                <input type="date" name="date_fin_prevue" value="<?= $tache['date_fin_prevue'] ?>"><br><br>

                <label>Dependences : </label>
                <select id="selectDependances" name="dependances[]" multiple style="width:100%;min-height:80px;">
                    <?php foreach ($taches as $opt): ?>
                        <option value="<?= $opt['id_tache'] ?>"><?= htmlspecialchars($opt['nom']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="modal-footer">
                    <button type="button" class="btn-annuler" onclick="fermerModal('modalModifierTache')">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Modifier
                    </button>
                </div>
            </form>
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

        <!-- Footer avec Supprimer + Fermer -->
        <div class="modal-footer">
            <!-- Supprimer à gauche -->
            <a id="btnSupprimerTache" href="#"
               class="btn-danger"
               onclick="return confirm('Supprimer cette tâche ?')">
                <i class="fa-solid fa-trash"></i> Supprimer la tache
            </a>

            <!-- Fermer à droite -->
            <button class="btn-annuler" onclick="fermerModal('modalDetailTache')">
                Fermer
            </button>
        </div>
    </div>
</div>
</div>