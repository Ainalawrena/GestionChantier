<div id="dashboard" class="tab-content active">

    <h2 class="admin-section-title">Vue d'ensemble</h2>

    <!-- KPI CARDS PRINCIPAUX -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon-wrap blue">
                <i class="fa-solid fa-helmet-safety"></i>
            </div>
            <div class="kpi-info">
                <div class="kpi-value"><?= $stats['total_chantiers'] ?></div>
                <div class="kpi-label">Chantiers totaux</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon-wrap green">
                <i class="fa-solid fa-play"></i>
            </div>
            <div class="kpi-info">
                <div class="kpi-value"><?= $stats['chantiers_en_cours'] ?></div>
                <div class="kpi-label">En cours</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon-wrap red">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="kpi-info">
                <div class="kpi-value"><?= $stats['chantiers_retard'] ?></div>
                <div class="kpi-label">En retard</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon-wrap purple">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="kpi-info">
                <div class="kpi-value"><?= $stats['total_utilisateurs'] ?></div>
                <div class="kpi-label">Utilisateurs</div>
            </div>
        </div>
    </div>

    <!-- DEUXIEME LIGNE -->
    <div class="dash-grid-2">

        <!-- Progression globale -->
        <div class="dash-panel">
            <div class="panel-header">
                <h3><i class="fa-solid fa-chart-line"></i> Progression globale</h3>
            </div>
            <div class="panel-body">
                <div class="global-progress-circle">
                    <svg viewBox="0 0 120 120" class="progress-ring">
                        <circle cx="60" cy="60" r="52" class="progress-ring-bg"/>
                        <circle cx="60" cy="60" r="52" class="progress-ring-fill"
                            style="stroke-dashoffset: <?= 327 - (327 * $stats['progression_moyenne'] / 100) ?>"/>
                    </svg>
                    <div class="progress-ring-text"><?= $stats['progression_moyenne'] ?>%</div>
                </div>
                <div class="progress-detail">
                    <div class="progress-detail-row">
                        <span>Tâches terminées</span>
                        <strong><?= $stats['taches_terminees'] ?> / <?= $stats['total_taches'] ?></strong>
                    </div>
                    <div class="progress-detail-row">
                        <span>Avancements à valider</span>
                        <strong class="text-orange"><?= $stats['avancements_a_valider'] ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes -->
        <div class="dash-panel">
            <div class="panel-header">
                <h3><i class="fa-solid fa-triangle-exclamation"></i> Alertes</h3>
            </div>
            <div class="panel-body">
                <div class="alert-row <?= $stats['incidents_critiques'] > 0 ? 'alert-danger' : '' ?>">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= $stats['incidents_critiques'] ?> incident(s) critique(s)</span>
                </div>
                <div class="alert-row">
                    <i class="fa-solid fa-bell"></i>
                    <span><?= $stats['incidents_ouverts'] ?> incident(s) ouvert(s)</span>
                </div>
                <div class="alert-row">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span><?= $stats['chantiers_retard'] ?> chantier(s) en retard</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TROISIEME LIGNE -->
    <div class="dash-grid-2">

        <!-- Répartition utilisateurs -->
        <div class="dash-panel">
            <div class="panel-header">
                <h3><i class="fa-solid fa-user-group"></i> Équipe</h3>
            </div>
            <div class="panel-body">
                <?php foreach ($stats['utilisateurs_par_role'] as $r): ?>
                    <div class="role-row">
                        <span class="role-name"><?= htmlspecialchars($r['libelle']) ?></span>
                        <span class="role-count"><?= $r['nb'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Derniers incidents -->
        <div class="dash-panel">
            <div class="panel-header">
                <h3><i class="fa-solid fa-list"></i> Derniers incidents</h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($stats['derniers_incidents'])): ?>
                    <?php foreach ($stats['derniers_incidents'] as $inc): ?>
                        <div class="incident-row">
                            <div class="incident-dot <?= $inc['gravite'] === 'critique' ? 'dot-red' : 'dot-orange' ?>"></div>
                            <div class="incident-info">
                                <span class="incident-desc"><?= htmlspecialchars($inc['description']) ?></span>
                                <span class="incident-meta">
                                    <?= htmlspecialchars($inc['nom_chantier']) ?> · <?= htmlspecialchars($inc['nom_tache']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-text">Aucun incident récent.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>