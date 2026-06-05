<div id="chantier" class="tab-content active">
    <h2>Informations du Chantier</h2>
    
    <!-- Infos générales -->
    <div class="info-grid">
        <div class="info-card">
            <strong>Nom du chantier</strong>
            <p><?= htmlspecialchars($chantier['nom']) ?></p>
        </div>
        <div class="info-card">
            <strong>Date début prévue</strong>
            <p><?= date('d/m/Y', strtotime($chantier['date_debut_prevu'])) ?></p>
        </div>
        <div class="info-card">
            <strong>Date fin prévue</strong>
            <p><?= date('d/m/Y', strtotime($chantier['date_fin_prevu'])) ?></p>
        </div>
        <div class="info-card">
            <strong>Statut</strong>
            <p class="status-badge"><?= htmlspecialchars($chantier['statut']) ?></p>
        </div>
    </div>

    <hr>

    <!-- Membres du chantier -->
    <h3>Membres du chantier</h3>
    <table class="tableau">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ouvriers as $membre): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($membre['nom']) ?>
                        <?php if ($membre['libelle'] === 'Chef de chantier'): ?>
                            <span class="badge badge-chef">👷 Chef</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($membre['email']) ?></td>
                    <td>
                        <?php
                        $badgeClass = match($membre['libelle']) {
                            'Chef de chantier' => 'badge-chef',
                            'Ouvrier'          => 'badge-ouvrier',
                            'Architecte'       => 'badge-architecte',
                            default            => ''
                        };
                        ?>
                        <span class="badge <?= $badgeClass ?>">
                            <?= htmlspecialchars($membre['libelle']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>