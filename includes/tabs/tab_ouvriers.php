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
                            <!-- Bouton affecter tâche -->
                            <button class="btn-modifier" 
                                onclick="afficherFormulaireAffectation(<?= $ouvrier['id_user'] ?>, '<?= htmlspecialchars($ouvrier['nom']) ?>')">
                                Affecter une tâche
                            </button>
                            <a href="retirer_ouvrier.php?id_user=<?= $ouvrier['id_user'] ?>&id_chantier=<?= $id_chantier ?>"
                               onclick="return confirm('Retirer ?')"
                               class="btn-supprimer">Retirer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Aucun membre affecté.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <button class="btn btn-primary" onclick="afficherFormulaireOuvrier()">
        + Ajouter un membre
    </button>

    <!-- Formulaire ajouter membre -->
    <div id="formulaireOuvrier" style="display:none; margin-top:20px;">
        <h3>Ajouter un membre</h3>
        <form method="POST" action="ajouter_ouvrier.php">
            <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">

            <label>Utilisateur :</label>
            <select name="id_user" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($tousUtilisateurs as $u): ?>
                    <option value="<?= $u['id_user'] ?>">
                        <?= htmlspecialchars($u['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Rôle :</label>
            <select name="roleid_role">
                <option value="3">Ouvrier</option>
                <option value="4">Architecte</option>
            </select><br><br>

            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" onclick="cacherFormulaireOuvrier()">Annuler</button>
        </form>
    </div>

    <!-- Formulaire affectation tâche -->
    <div id="formulaireAffectation" style="display:none; margin-top:20px;">
        <h3>Affecter une tâche à <span id="nomOuvrier"></span></h3>
        <form method="POST" action="affecter_tache.php">
            <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">
            <input type="hidden" name="id_user" id="idOuvrier">

            <label>Choisir une tâche :</label>
            <select name="id_tache" required>
                <option value="">-- Choisir une tâche --</option>
                <?php foreach ($taches as $tache): ?>
                    <option value="<?= $tache['id_tache'] ?>">
                        <?= htmlspecialchars($tache['nom']) ?>
                        <?php if ($tache['nom_ouvrier']): ?>
                            (déjà assignée à <?= htmlspecialchars($tache['nom_ouvrier']) ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <button type="submit" class="btn btn-primary">Affecter</button>
            <button type="button" onclick="cacherFormulaireAffectation()">Annuler</button>
        </form>
    </div>
</div>