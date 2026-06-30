<div id="avancement" class="tab-content">

    <div class="admin-toolbar">
        <div class="admin-toolbar-left">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchAvancement" placeholder="Rechercher..." oninput="filtrerAvancements()">
            </div>
            <select id="filtreValidation" onchange="filtrerAvancements()" class="filtre-select">
                <option value="">Tous les statuts</option>
                <option value="en attente">En attente de validation</option>
                <option value="valide">Validé</option>
                <option value="refuse">Refusé</option>
            </select>
        </div>
        <span class="chantiers-count" id="avancementsCount">
            <?= count($tousAvancements) ?> avancement<?= count($tousAvancements) > 1 ? 's' : '' ?>
        </span>
    </div>

    <table class="tableau" id="tableAvancements">
        <thead>
            <tr>
                <th>Chantier</th>
                <th>Tâche</th>
                <th>Ouvrier</th>
                <th>%</th>
                <th>Date</th>
                <th>Validation</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tousAvancements)): ?>
                <?php foreach ($tousAvancements as $a): ?>
                    <?php
                        $statutVal = $a['statut_validation'] ?? 'en attente';
                        $badgeClass = match($statutVal) {
                            'valide'     => 'badge-termine',
                            'refuse'     => 'badge-bloque',
                            default      => 'badge-attente'
                        };
                        $badgeLabel = match($statutVal) {
                            'valide'     => 'Validé',
                            'refuse'     => 'Refusé',
                            default      => 'En attente'
                        };
                    ?>
                    <tr data-nom="<?= strtolower(htmlspecialchars($a['nom_tache'] . ' ' . $a['nom_ouvrier'])) ?>"
                        data-validation="<?= $statutVal ?>">
                        <td>
                            <a href="index.php?page=dashboard&action=dashboardChef&id_chantier=<?= $a['id_chantier'] ?>" class="chantier-link">
                                <?= htmlspecialchars($a['nom_chantier']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($a['nom_tache']) ?></td>
                        <td><?= htmlspecialchars($a['nom_ouvrier']) ?></td>
                        <td><strong><?= $a['pourcentage'] ?>%</strong></td>
                        <td><?= $a['date_mise_a_jour'] ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
                        <td>
                            <div class="action-group">
                                <button class="action-icon-btn"
                                    onclick='ouvrirModalDetailAvancement(<?= htmlspecialchars(json_encode($a)) ?>)'
                                    title="Voir détail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Aucun avancement soumis.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- MODAL Détail avancement -->
    <div class="modal-overlay" id="modalDetailAvancement">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-chart-line"></i> Détail de l'avancement</h3>
                <button class="modal-close" onclick="fermerModal('modalDetailAvancement')">✕</button>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Chantier</span>
                    <span class="detail-value" id="daChantier">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tâche</span>
                    <span class="detail-value" id="daTache">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Ouvrier</span>
                    <span class="detail-value" id="daOuvrier">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pourcentage</span>
                    <span class="detail-value" id="daPourcentage">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date soumission</span>
                    <span class="detail-value" id="daDate">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Statut validation</span>
                    <span class="detail-value" id="daStatutValidation">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Validé par</span>
                    <span class="detail-value" id="daArchitecte">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date validation</span>
                    <span class="detail-value" id="daDateValidation">-</span>
                </div>
            </div>

            <div class="detail-comment" id="daCommentaireWrap" style="margin-top:14px;">
                <span class="detail-label">Commentaire</span>
                <p id="daCommentaire" class="comment-box">-</p>
            </div>

            <div class="modal-footer">
                <button class="btn-annuler" onclick="fermerModal('modalDetailAvancement')">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
function filtrerAvancements() {
    const search = document.getElementById('searchAvancement').value.toLowerCase();
    const val    = document.getElementById('filtreValidation').value;
    let visible = 0;

    document.querySelectorAll('#tableAvancements tbody tr').forEach(row => {
        const matchNom = row.dataset.nom?.includes(search) ?? true;
        const matchVal = !val || row.dataset.validation === val;
        const match = matchNom && matchVal;
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('avancementsCount').textContent =
        visible + ' avancement' + (visible > 1 ? 's' : '');
}

function ouvrirModalDetailAvancement(a) {
    document.getElementById('daChantier').textContent  = a.nom_chantier;
    document.getElementById('daTache').textContent      = a.nom_tache;
    document.getElementById('daOuvrier').textContent    = a.nom_ouvrier;
    document.getElementById('daPourcentage').textContent= a.pourcentage + '%';
    document.getElementById('daDate').textContent        = a.date_mise_a_jour;

    const statut = a.statut_validation ?? 'en attente';
    document.getElementById('daStatutValidation').textContent =
        statut === 'valide' ? 'Validé' : statut === 'refuse' ? 'Refusé' : 'En attente';

    document.getElementById('daArchitecte').textContent     = a.nom_architecte ?? '-';
    document.getElementById('daDateValidation').textContent = a.date_validation ?? '-';
    document.getElementById('daCommentaire').textContent    = a.commentaire || 'Aucun commentaire';

    ouvrirModal('modalDetailAvancement');
}
</script>