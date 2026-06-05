<div id="validation" class="tab-content">
    <h2>Validation des avancements</h2>

    <?php if (!empty($avancementsAValider)): ?>
        <table class="tableau">
            <thead>
                <tr>
                    <th>Tâche</th>
                    <th>Ouvrier</th>
                    <th>%</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avancementsAValider as $av): ?>
                    <tr>
                        <td><?= htmlspecialchars($av['nom_tache']) ?></td>
                        <td><?= htmlspecialchars($av['nom_ouvrier']) ?></td>
                        <td><?= $av['pourcentage'] ?>%</td>
                        <td><?= htmlspecialchars($av['commentaire']) ?></td>
                        <td><?= $av['date_mise_a_jour'] ?></td>
                        <td>
                            <form method="POST" action="index.php?page=validation&action=valider">
                                <input type="hidden" name="id_avancement" value="<?= $av['id_avancement'] ?>">
                                <input type="hidden" name="id_chantier" value="<?= $id_chantier ?>">
                                <button name="statut_validation" value="valide" class="btn-modifier">✅ Valider</button>
                                <button name="statut_validation" value="refuse" class="btn-supprimer">❌ Refuser</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun avancement en attente de validation.</p>
    <?php endif; ?>
</div>