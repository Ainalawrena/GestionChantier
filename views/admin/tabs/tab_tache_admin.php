<div id="taches" class="tab-content">

    <div class="admin-toolbar">
        <div class="admin-toolbar-left">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchTache" placeholder="Rechercher une tâche..." oninput="filtrerTaches()">
            </div>
            <select id="filtreChantierTache" onchange="filtrerTaches()" class="filtre-select">
                <option value="">Tous les chantiers</option>
                <?php
                $chantiersUniques = [];
                foreach ($toutesTaches as $t) {
                    $chantiersUniques[$t['id_chantier']] = $t['nom_chantier'];
                }
                foreach ($chantiersUniques as $idC => $nomC):
                ?>
                    <option value="<?= htmlspecialchars($nomC) ?>"><?= htmlspecialchars($nomC) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="filtreStatutTache" onchange="filtrerTaches()" class="filtre-select">
                <option value="">Tous les statuts</option>
                <option value="en attente">En attente</option>
                <option value="en cours">En cours</option>
                <option value="termine">Terminé</option>
                <option value="bloque">Bloqué</option>
            </select>
        </div>
        <span class="chantiers-count" id="tachesCount">
            <?= count($toutesTaches) ?> tâche<?= count($toutesTaches) > 1 ? 's' : '' ?>
        </span>
    </div>

    <table class="tableau" id="tableTaches">
        <thead>
            <tr>
                <th>Chantier</th>
                <th>Tâche</th>
                <th>Statut</th>
                <th>Avancement</th>
                <th>Ouvrier</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($toutesTaches)): ?>
                <?php foreach ($toutesTaches as $t): ?>
                    <?php
                        $statutAffiche = $t['en_retard'] ? 'en retard' : $t['statut'];
                        $badgeClass = match($statutAffiche) {
                            'en attente' => 'badge-attente',
                            'en cours'   => 'badge-encours',
                            'termine'    => 'badge-termine',
                            'bloque'     => 'badge-bloque',
                            'en retard'  => 'badge-retard',
                            default      => 'badge-attente'
                        };
                        $badgeLabel = match($statutAffiche) {
                            'en attente' => 'En attente',
                            'en cours'   => 'En cours',
                            'termine'    => 'Terminé',
                            'bloque'     => 'Bloqué',
                            'en retard'  => 'En retard',
                            default      => $t['statut']
                        };
                    ?>
                    <tr data-nom="<?= strtolower(htmlspecialchars($t['nom'])) ?>"
                        data-chantier="<?= htmlspecialchars($t['nom_chantier']) ?>"
                        data-statut="<?= $statutAffiche ?>">
                        <td>
                            <a href="index.php?page=dashboard&action=dashboardChef&id_chantier=<?= $t['id_chantier'] ?>" class="chantier-link">
                                <?= htmlspecialchars($t['nom_chantier']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($t['nom']) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
                        <td>
                            <div class="mini-progress-bar">
                                <div class="mini-progress-fill" style="width: <?= $t['pourcentage'] ?>%"></div>
                            </div>
                            <small><?= $t['pourcentage'] ?>%</small>
                        </td>
                        <td><?= htmlspecialchars($t['nom_ouvrier'] ?? '-') ?></td>
                        <td>
                            <div class="action-group">
                                <button class="action-icon-btn"
                                    onclick="ouvrirModalDetailTacheAdmin(<?= htmlspecialchars(json_encode($t)) ?>)"
                                    title="Voir détail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Aucune tâche créée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- MODAL Détail tâche admin -->
    <div class="modal-overlay" id="modalDetailTacheAdmin">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-list-check"></i> <span id="dtaNom"></span></h3>
                <button class="modal-close" onclick="fermerModal('modalDetailTacheAdmin')">✕</button>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Chantier</span>
                    <span class="detail-value" id="dtaChantier">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Statut</span>
                    <span class="detail-value" id="dtaStatut">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Ordre</span>
                    <span class="detail-value" id="dtaOrdre">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Avancement</span>
                    <span class="detail-value" id="dtaPourcentage">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date début prévue</span>
                    <span class="detail-value" id="dtaDebut">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date fin prévue</span>
                    <span class="detail-value" id="dtaFin">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Ouvrier assigné</span>
                    <span class="detail-value" id="dtaOuvrier">-</span>
                </div>
            </div>

            <div class="modal-footer">
                <a id="dtaBtnOuvrir" href="#" class="mc-btn-primary" style="flex:1; justify-content:center;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Voir le chantier
                </a>
                <button class="btn-annuler" onclick="fermerModal('modalDetailTacheAdmin')">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
function filtrerTaches() {
    const search   = document.getElementById('searchTache').value.toLowerCase();
    const chantier = document.getElementById('filtreChantierTache').value;
    const statut   = document.getElementById('filtreStatutTache').value;
    let visible = 0;

    document.querySelectorAll('#tableTaches tbody tr').forEach(row => {
        const matchNom      = row.dataset.nom?.includes(search) ?? true;
        const matchChantier = !chantier || row.dataset.chantier === chantier;
        const matchStatut   = !statut || row.dataset.statut === statut;
        const match = matchNom && matchChantier && matchStatut;
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('tachesCount').textContent =
        visible + ' tâche' + (visible > 1 ? 's' : '');
}

function ouvrirModalDetailTacheAdmin(t) {
    document.getElementById('dtaNom').textContent         = t.nom;
    document.getElementById('dtaChantier').textContent    = t.nom_chantier;
    document.getElementById('dtaStatut').textContent      = t.en_retard ? 'En retard' : t.statut;
    document.getElementById('dtaOrdre').textContent       = t.ordre ?? '-';
    document.getElementById('dtaPourcentage').textContent = t.pourcentage + '%';
    document.getElementById('dtaDebut').textContent       = t.date_debut_prevue ?? '-';
    document.getElementById('dtaFin').textContent         = t.date_fin_prevue ?? '-';
    document.getElementById('dtaOuvrier').textContent     = t.nom_ouvrier ?? '-';
    document.getElementById('dtaBtnOuvrir').href =
        `index.php?page=dashboard&action=dashboardChef&id_chantier=${t.id_chantier}`;
    ouvrirModal('modalDetailTacheAdmin');
}
</script>