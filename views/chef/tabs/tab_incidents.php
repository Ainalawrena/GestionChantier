<div id="incidents" class="tab-content">
    <h2>Incidents</h2>

    <table class="tableau">
        <thead>
            <tr>
                <th>Tâche</th>
                <th>Description</th>
                <th>Gravité</th>
                <th>Impact</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Solution</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($incidents)): ?>
                <?php foreach ($incidents as $incident): ?>
                    <tr>
                        <td><?= htmlspecialchars($incident['nom_tache']) ?></td>
                        <td><?= htmlspecialchars($incident['description']) ?></td>
                        <td><?= htmlspecialchars($incident['gravite']) ?></td>
                        <td><?= htmlspecialchars($incident['impact']) ?></td>
                        <td><?= $incident['date_incident'] ?></td>
                        <td>
                            <?php
                            $badgeClass = match($incident['statut']) {
                                'ouvert'   => 'badge-bloque',
                                'en cours' => 'badge-encours',
                                'resolu'   => 'badge-termine',
                                default    => ''
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $incident['statut'] ?></span>
                        </td>
                        <td><?= htmlspecialchars($incident['solution'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Aucun incident déclaré.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>