<div id="incidents" class="tab-content">

    <div class="admin-toolbar">
        <div class="admin-toolbar-left">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchIncident" placeholder="Rechercher..." oninput="filtrerIncidents()">
            </div>
            <select id="filtreGravite" onchange="filtrerIncidents()" class="filtre-select">
                <option value="">Toutes gravités</option>
                <option value="faible">Faible</option>
                <option value="moyen">Moyen</option>
                <option value="critique">Critique</option>
            </select>
            <select id="filtreStatutIncident" onchange="filtrerIncidents()" class="filtre-select">
                <option value="">Tous les statuts</option>
                <option value="ouvert">Ouvert</option>
                <option value="en cours">En cours</option>
                <option value="resolu">Résolu</option>
            </select>
        </div>
        <span class="chantiers-count" id="incidentsCount">
            <?= count($tousIncidents) ?> incident<?= count($tousIncidents) > 1 ? 's' : '' ?>
        </span>
    </div>

    <table class="tableau" id="tableIncidents">
        <thead>
            <tr>
                <th>Chantier</th>
                <th>Tâche</th>
                <th>Description</th>
                <th>Gravité</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tousIncidents)): ?>
                <?php foreach ($tousIncidents as $inc): ?>
                    <?php
                        $graviteClass = match($inc['gravite']) {
                            'critique' => 'badge-bloque',
                            'moyen'    => 'badge-encours',
                            'faible'   => 'badge-attente',
                            default    => 'badge-attente'
                        };
                        $statutClass = match($inc['statut']) {
                            'ouvert'   => 'badge-bloque',
                            'en cours' => 'badge-encours',
                            'resolu'   => 'badge-termine',
                            default    => 'badge-attente'
                        };
                    ?>
                    <tr data-nom="<?= strtolower(htmlspecialchars($inc['description'] . ' ' . $inc['nom_tache'])) ?>"
                        data-gravite="<?= $inc['gravite'] ?>"
                        data-statut="<?= $inc['statut'] ?>">
                        <td>
                            <a href="index.php?page=dashboard&action=dashboardChef&id_chantier=<?= $inc['id_chantier'] ?>" class="chantier-link">
                                <?= htmlspecialchars($inc['nom_chantier']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($inc['nom_tache']) ?></td>
                        <td class="cell-truncate"><?= htmlspecialchars($inc['description']) ?></td>
                        <td><span class="badge <?= $graviteClass ?>"><?= ucfirst($inc['gravite']) ?></span></td>
                        <td><?= $inc['date_incident'] ?></td>
                        <td><span class="badge <?= $statutClass ?>"><?= ucfirst($inc['statut']) ?></span></td>
                        <td>
                            <div class="action-group">
                                <button class="action-icon-btn"
                                    onclick='ouvrirModalDetailIncident(<?= htmlspecialchars(json_encode($inc)) ?>)'
                                    title="Voir détail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Aucun incident déclaré.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- MODAL Détail incident -->
    <div class="modal-overlay" id="modalDetailIncident">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-triangle-exclamation"></i> Détail de l'incident</h3>
                <button class="modal-close" onclick="fermerModal('modalDetailIncident')">✕</button>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Chantier</span>
                    <span class="detail-value" id="diChantier">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tâche</span>
                    <span class="detail-value" id="diTache">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Gravité</span>
                    <span class="detail-value" id="diGravite">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Statut</span>
                    <span class="detail-value" id="diStatut">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date incident</span>
                    <span class="detail-value" id="diDate">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date résolution</span>
                    <span class="detail-value" id="diDateResolution">-</span>
                </div>
            </div>

            <div class="detail-comment">
                <span class="detail-label">Description</span>
                <p id="diDescription" class="comment-box">-</p>
            </div>

            <div class="detail-comment" style="margin-top:12px;">
                <span class="detail-label">Impact</span>
                <p id="diImpact" class="comment-box">-</p>
            </div>

            <div class="detail-comment" id="diSolutionWrap" style="margin-top:12px;">
                <span class="detail-label">Solution apportée</span>
                <p id="diSolution" class="comment-box">-</p>
            </div>

            <div class="modal-footer">
                <button class="btn-annuler" onclick="fermerModal('modalDetailIncident')">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
function filtrerIncidents() {
    const search  = document.getElementById('searchIncident').value.toLowerCase();
    const gravite = document.getElementById('filtreGravite').value;
    const statut  = document.getElementById('filtreStatutIncident').value;
    let visible = 0;

    document.querySelectorAll('#tableIncidents tbody tr').forEach(row => {
        const matchNom     = row.dataset.nom?.includes(search) ?? true;
        const matchGravite = !gravite || row.dataset.gravite === gravite;
        const matchStatut  = !statut || row.dataset.statut === statut;
        const match = matchNom && matchGravite && matchStatut;
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('incidentsCount').textContent =
        visible + ' incident' + (visible > 1 ? 's' : '');
}

function ouvrirModalDetailIncident(inc) {
    document.getElementById('diChantier').textContent       = inc.nom_chantier;
    document.getElementById('diTache').textContent           = inc.nom_tache;
    document.getElementById('diGravite').textContent         = inc.gravite.charAt(0).toUpperCase() + inc.gravite.slice(1);
    document.getElementById('diStatut').textContent          = inc.statut.charAt(0).toUpperCase() + inc.statut.slice(1);
    document.getElementById('diDate').textContent             = inc.date_incident;
    document.getElementById('diDateResolution').textContent  = inc.date_resolution || '-';
    document.getElementById('diDescription').textContent     = inc.description;
    document.getElementById('diImpact').textContent          = inc.impact || 'Non précisé';
    document.getElementById('diSolution').textContent        = inc.solution || 'Pas encore de solution';

    ouvrirModal('modalDetailIncident');
}
</script>