<div id="ouvriers" class="tab-content">
    <h2>Équipe du chantier</h2>
    <table class="tableau">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Nb tâches</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ouvriers)): ?>
                <?php foreach ($ouvriers as $ouvrier): ?>
                    <tr>
                        <td><?= htmlspecialchars($ouvrier['nom']) ?></td>
                        <td><?= htmlspecialchars($ouvrier['email']) ?></td>
                        <td><?= htmlspecialchars($ouvrier['libelle']) ?></td>
                        <td><?= $ouvrier['nb_taches'] ?></td>
                        <td>
                            <div class="action-group">
                                <button class="action-icon-btn"
                                    onclick="afficherFormulaireAffectation(<?= $ouvrier['id_user'] ?>, '<?= htmlspecialchars($ouvrier['nom']) ?>')"
                                    title="Affecter une tâche">
                                    <i class="fa-solid fa-list-check"></i>
                                </button>
                                <a href="index.php?page=chantier&action=retirerMembre&id_user=<?= $ouvrier['id_user'] ?>&id_chantier=<?= $id_chantier ?>"
                                   onclick="return confirm('Retirer ce membre du chantier ?')"
                                   class="action-icon-btn danger"
                                   title="Retirer">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Aucun membre affecté.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    
   <!-- Bouton ouvrir -->
    <button class="btn btn-primary" onclick="ouvrirModal('modalOuvrier')">
        <i class="fa-solid fa-user-plus"></i> Ajouter un membre
    </button>
                
    <!-- MODAL Ajouter membre -->
    <div class="modal-overlay" id="modalOuvrier">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-user-plus"></i> Ajouter un membre</h3>
                <button class="modal-close" onclick="fermerModal('modalOuvrier')">✕</button>
            </div>
                
            <form method="POST" action="index.php?page=chantier&action=ajouterMembre">
                <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">
                
                <label>Utilisateur :</label>
                <select name="id_user" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($tousUtilisateurs as $u): ?>
                        <option value="<?= $u['id_user'] ?>">
                            <?= htmlspecialchars($u['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                    
                <label>Rôle :</label>
                <select name="roleid_role">
                    <option value="3">Ouvrier</option>
                    <option value="4">Architecte</option>
                </select>
                    
                <div class="modal-footer">
                    <button type="button" class="btn-annuler" onclick="fermerModal('modalOuvrier')">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
                    
    <!-- MODAL Affecter tâche -->
    <div class="modal-overlay" id="modalAffectation">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-list-check"></i> Affecter une tâche à <span id="nomOuvrier"></span></h3>
                <button class="modal-close" onclick="fermerModal('modalAffectation')">✕</button>
            </div>
                    
            <form method="POST" action="index.php?page=chantier&action=affecterTache">
                <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">
                <input type="hidden" name="id_user" id="idOuvrier">
                    
                <label>Choisir une tâche :</label>
                <select name="id_tache" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($taches as $tache): ?>
                        <option value="<?= $tache['id_tache'] ?>">
                            <?= htmlspecialchars($tache['nom']) ?>
                            <?php if ($tache['nom_ouvrier']): ?>
                                (<?= htmlspecialchars($tache['nom_ouvrier']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                            
                <div class="modal-footer">
                    <button type="button" class="btn-annuler" onclick="fermerModal('modalAffectation')">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Affecter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>