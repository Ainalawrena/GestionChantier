<?php
// tab_chantier.php — Vue Admin : liste de tous les chantiers en cards
?>

<div id="chantier" class="tab-content">

    <!-- TOOLBAR -->
    <div class="admin-toolbar">
        <div class="admin-toolbar-left">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchChantier" placeholder="Rechercher un chantier..." oninput="filtrerChantiers()">
            </div>
            <select id="filtreStatut" onchange="filtrerChantiers()" class="filtre-select">
                <option value="">Tous les statuts</option>
                <option value="en attente">En attente</option>
                <option value="en cours">En cours</option>
                <option value="termine">Terminé</option>
                <option value="en retard">En retard</option>
            </select>
        </div>
        <div class="admin-toolbar-right">
            <span class="chantiers-count" id="chantiersCount">
                <?= count($chantiers) ?> chantier<?= count($chantiers) > 1 ? 's' : '' ?>
            </span>
        </div>
    </div>

    <!-- GRID CARDS -->
    <div class="chantiers-grid" id="chantiersGrid">
        <?php if (!empty($chantiers)): ?>
            <?php foreach ($chantiers as $c): ?>
                <?php
                    $enRetard  = $c['en_retard'];
                    $statut    = $enRetard ? 'en retard' : $c['statut'];
                    $badgeClass = match($statut) {
                        'en attente' => 'badge-attente',
                        'en cours'   => 'badge-encours',
                        'termine'    => 'badge-termine',
                        'en retard'  => 'badge-retard',
                        default      => 'badge-attente'
                    };
                    $badgeLabel = match($statut) {
                        'en attente' => 'En attente',
                        'en cours'   => 'En cours',
                        'termine'    => 'Terminé',
                        'en retard'  => 'En retard',
                        default      => $c['statut']
                    };
                    $progression = (int)($c['progression'] ?? 0);
                    $barColor = match(true) {
                        $enRetard         => '#ef4444',
                        $progression >= 80 => '#22c55e',
                        $progression >= 40 => '#f97316',
                        default            => '#3b82f6'
                    };
                ?>
                <div class="chantier-card" data-nom="<?= strtolower(htmlspecialchars($c['nom'])) ?>" data-statut="<?= $statut ?>">

                    <!-- Header card -->
                    <div class="card-header-strip" style="background: <?= $barColor ?>"></div>

                    <div class="card-body">
                        <div class="card-top">
                            <div>
                                <h3 class="card-title"><?= htmlspecialchars($c['nom']) ?></h3>
                                <span class="card-modele">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <?= htmlspecialchars($c['nom_modele'] ?? '-') ?>
                                </span>
                            </div>
                            <span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                        </div>

                        <!-- Progression -->
                        <div class="card-progress-section">
                            <div class="card-progress-label">
                                <span>Avancement</span>
                                <strong><?= $progression ?>%</strong>
                            </div>
                            <div class="card-progress-bar">
                                <div class="card-progress-fill" style="width: <?= $progression ?>%; background: <?= $barColor ?>"></div>
                            </div>
                        </div>

                        <!-- Infos dates -->
                        <div class="card-dates">
                            <span><i class="fa-regular fa-calendar"></i>
                                <?= $c['date_debut_prevu'] ? date('d M Y', strtotime($c['date_debut_prevu'])) : '-' ?>
                            </span>
                            <i class="fa-solid fa-arrow-right card-arrow"></i>
                            <span><?= $c['date_fin_prevu'] ? date('d M Y', strtotime($c['date_fin_prevu'])) : '-' ?></span>
                        </div>

                        <!-- Stats -->
                        <div class="card-stats">
                            <div class="card-stat">
                                <i class="fa-solid fa-list-check"></i>
                                <span><?= $c['nb_taches_terminees'] ?>/<?= $c['nb_taches'] ?> tâches</span>
                            </div>
                            <div class="card-stat">
                                <i class="fa-solid fa-users"></i>
                                <span><?= $c['nb_ouvriers'] ?> ouvrier<?= $c['nb_ouvriers'] > 1 ? 's' : '' ?></span>
                            </div>
                            <div class="card-stat <?= $c['nb_incidents'] > 0 ? 'stat-danger' : '' ?>">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span><?= $c['nb_incidents'] ?> incident<?= $c['nb_incidents'] > 1 ? 's' : '' ?></span>
                            </div>
                        </div>

                    <button class="card-btn-voir" onclick="ouvrirModalChantier(<?= htmlspecialchars(json_encode([
    'id'                  => $c['id_chantier'],
    'nom'                 => $c['nom'],
    'statut'              => $statut,
    'badge'               => $badgeLabel,
    'badgeClass'          => $badgeClass,
    'modele'              => $c['nom_modele'] ?? '-',
    'progression'         => $progression,
    'barColor'            => $barColor,
    'debut'               => $c['date_debut_prevu'] ? date('d M Y', strtotime($c['date_debut_prevu'])) : '-',
    'fin'                 => $c['date_fin_prevu']   ? date('d M Y', strtotime($c['date_fin_prevu']))   : '-',
    'nb_taches'           => $c['nb_taches'],
    'nb_taches_terminees' => $c['nb_taches_terminees'],
    'nb_ouvriers'         => $c['nb_ouvriers'],
    'nb_incidents'        => $c['nb_incidents'],
    'en_retard'           => $c['en_retard'],
    'statut_reel'         => $c['statut'],  // ✅ ajoute
])) ?>)">
    Voir plus <i class="fa-solid fa-arrow-right"></i>
</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-helmet-safety"></i>
                <p>Aucun chantier créé pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>

 <!-- MODAL DÉTAIL CHANTIER -->
<div class="modal-overlay" id="modalChantier">
    <div class="modal-chantier">

        <!-- Hero -->
        <div class="mc-hero" id="mcHero">
            <div class="mc-hero-overlay"></div>
            <div class="mc-hero-content">
                <span class="badge" id="mcBadge"></span>
                <h2 id="mcNom"></h2>
                <span class="mc-modele">
                    <i class="fa-solid fa-layer-group"></i>
                    <span id="mcModeleNom"></span>
                </span>
            </div>
            <button class="mc-close" onclick="fermerModal('modalChantier')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="mc-body">

            <!-- Progression -->
            <div class="mc-section">
                <div class="mc-progress-header">
                    <span class="mc-section-title">
                        <i class="fa-solid fa-chart-line"></i> Avancement global
                    </span>
                    <strong id="mcPct"></strong>
                </div>
                <div class="mc-progress-bar">
                    <div class="mc-progress-fill" id="mcFill"></div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:8px; font-size:0.8rem; color:#64748b;">
                    <span id="mcTachesDetail"></span>
                </div>
            </div>

            <!-- Dates -->
            <div class="mc-section">
                <p class="mc-section-title">
                    <i class="fa-regular fa-calendar"></i> Calendrier
                </p>
                <div class="mc-dates-row">
                    <div class="mc-date-box">
                        <span class="mc-date-label">Début prévu</span>
                        <span class="mc-date-val" id="mcDebut"></span>
                    </div>
                    <div class="mc-date-arrow">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    <div class="mc-date-box">
                        <span class="mc-date-label">Fin prévue</span>
                        <span class="mc-date-val" id="mcFin"></span>
                    </div>
                </div>
            </div>

            <!-- KPI stats -->
            <div class="mc-section">
                <p class="mc-section-title">
                    <i class="fa-solid fa-gauge-high"></i> Indicateurs
                </p>
                <div class="mc-kpis">
                    <div class="mc-kpi">
                        <div class="mc-kpi-icon blue">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <div class="mc-kpi-val" id="mcTaches"></div>
                            <div class="mc-kpi-label">Tâches terminées</div>
                        </div>
                    </div>
                    <div class="mc-kpi">
                        <div class="mc-kpi-icon green">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <div class="mc-kpi-val" id="mcOuvriers"></div>
                            <div class="mc-kpi-label">Ouvriers affectés</div>
                        </div>
                    </div>
                    <div class="mc-kpi" id="mcKpiIncident">
                        <div class="mc-kpi-icon red">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="mc-kpi-val" id="mcIncidents"></div>
                            <div class="mc-kpi-label">Incidents ouverts</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statut retard -->
            <div class="mc-section" id="mcRetardSection" style="display:none;">
                <div class="alert-row alert-danger">
                    <i class="fa-solid fa-clock"></i>
                    <span>Ce chantier est en retard par rapport à la date de fin prévue !</span>
                </div>
            </div>

            <!-- Actions — seulement Fermer -->
            <div class="mc-actions">
                <button class="mc-btn-secondary" style="width:100%;" onclick="fermerModal('modalChantier')">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function filtrerChantiers() {
    const search = document.getElementById('searchChantier').value.toLowerCase();
    const statut = document.getElementById('filtreStatut').value.toLowerCase();
    const cards  = document.querySelectorAll('.chantier-card');
    let visible  = 0;

    cards.forEach(card => {
        const nom    = card.dataset.nom || '';
        const stCard = card.dataset.statut || '';
        const matchNom    = nom.includes(search);
        const matchStatut = !statut || stCard === statut;

        if (matchNom && matchStatut) {
            card.style.display = '';
            visible++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('chantiersCount').textContent =
        visible + ' chantier' + (visible > 1 ? 's' : '');
}
function ouvrirModalChantier(data) {
    // Hero
    document.getElementById('mcHero').style.background =
        `linear-gradient(135deg, ${data.barColor}cc, ${data.barColor}55)`;

    // Badge
    const badge = document.getElementById('mcBadge');
    badge.textContent = data.badge;
    badge.className   = 'badge ' + data.badgeClass;

    // Infos principales
    document.getElementById('mcNom').textContent       = data.nom;
    document.getElementById('mcModeleNom').textContent = data.modele;
    document.getElementById('mcPct').textContent       = data.progression + '%';
    document.getElementById('mcDebut').textContent     = data.debut;
    document.getElementById('mcFin').textContent       = data.fin;

    // Tâches
    document.getElementById('mcTaches').textContent      = data.nb_taches_terminees + '/' + data.nb_taches;
    document.getElementById('mcTachesDetail').textContent = data.nb_taches_terminees + ' tâches terminées sur ' + data.nb_taches;
    document.getElementById('mcOuvriers').textContent     = data.nb_ouvriers;
    document.getElementById('mcIncidents').textContent    = data.nb_incidents;

    // Barre progression
    const fill = document.getElementById('mcFill');
    fill.style.width      = data.progression + '%';
    fill.style.background = data.barColor;

    // Incidents
    const kpiInc = document.getElementById('mcKpiIncident');
    kpiInc.style.borderColor = data.nb_incidents > 0 ? 'rgba(239,68,68,0.4)' : '';

    // Retard
    const retardSection = document.getElementById('mcRetardSection');
    retardSection.style.display = data.en_retard ? 'block' : 'none';

    ouvrirModal('modalChantier');
}
</script>

