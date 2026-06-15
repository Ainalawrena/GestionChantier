<div id="avancement" class="tab-content">
    <h2>Avancement du Chantier</h2>

    <?php
    $total = count($taches);
    $terminees = count(array_filter($taches, fn($t) => $t['statut'] === 'termine'));
    $progression = $total > 0 ? round(($terminees / $total) * 100) : 0;
    ?>

    <div class="progression-globale">
        <h3>Progression globale : <?= $progression ?>%</h3><br>
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= $progression ?>%"></div>
        </div><br><br>
        <p><?= $terminees ?> / <?= $total ?> tâches terminées</p>
    </div>

    <hr>
    <br>
    <h3>Détail par tâche</h3><br>
    <table class="tableau">
        <thead>
            <tr>
                <th>Tâche</th>
                <th>Ouvrier</th>
                <th>Statut</th>
                <th>Avancement</th>
                <th>Date fin prévue</th>
                <th>Retard</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($taches as $tache): ?>
                <?php $enRetard = $tache['statut'] !== 'termine' && $tache['date_fin_prevue'] < date('Y-m-d'); ?>
                <tr class="<?= $enRetard ? 'retard' : '' ?>">
                    <td><?= htmlspecialchars($tache['nom']) ?></td>
                    <td><?= htmlspecialchars($tache['nom_ouvrier'] ?? '-') ?></td>
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
                        <span <?= $badgeClass ?>"><?= $tache['statut'] ?></span>
                    </td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $tache['pourcentage'] ?>%"></div>
                        </div>
                        <small><?= $tache['pourcentage'] ?>%</small>
                    </td>
                    <td><?= $tache['date_fin_prevu'] ?? '-' ?></td>
                    <td>
                        <?php if ($enRetard): ?>
                            <span> Retard</span>
                        <?php else: ?>
                            <span> OK</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (!empty($avancementsAValider)): ?>
        <hr>
        <h3>⏳ En attente de validation</h3>
        <table class="tableau">
            <thead>
                <tr>
                    <th>Tâche</th>
                    <th>Ouvrier</th>
                    <th>%</th>
                    <th>Commentaire</th>
                    <th>Date</th>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>