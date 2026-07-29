<div id="historique" class="tab-content">

    <h2>
        <i class="fa-solid fa-clock-rotate-left"></i>
        Historique des avancements
    </h2>

    <table class="tableau">
        <thead>
            <tr>
                <th>Tâche</th>
                <th>Ouvrier</th>
                <th>Avancement</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Voir plus</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($historiqueAvancements)): ?>

                <?php foreach ($historiqueAvancements as $historique): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($historique['nom_tache']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($historique['nom_ouvrier']) ?>
                        </td>

                        <td>
                            <?= $historique['pourcentage'] ?> %
                        </td>

                        <td>

                            <?php if ($historique['statut_validation'] === 'valide'): ?>

                                <span class="badge success">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Validé
                                </span>

                            <?php elseif ($historique['statut_validation'] === 'refuse'): ?>
                                <span class="badge danger">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    Refusé
                                </span>

                            <?php else: ?>
                                <span class="badge warning">
                                    <i class="fa-solid fa-clock"></i>
                                    En attente
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= !empty($historique['date_validation'])
                                ? date('d/m/Y', strtotime($historique['date_validation']))
                                : '-' ?>
                        </td>

                        <td>
                            <button
                                class="action-icon-btn"
                                onclick="ouvrirModalHistorique(<?= $historique['id_avancement'] ?>)"
                                title="Voir plus">

                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">
                        Aucun historique d'avancement.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>
<!-- Modal détail avancement -->
<div class="modal-overlay" id="modalHistorique">
    <div class="modal">
        <div class="modal-header">
            <h3>
                <i class="fa-solid fa-eye"></i>
                Détails de l'avancement
            </h3>

            <button class="modal-close"
                    onclick="fermerModal('modalHistorique')">
                ✕
            </button>
        </div>

        <div class="modal-body">

    <p>
        <strong>Tâche :</strong>
        <span class="detail-value" id="detailNomTache">-</span>
    </p>

    <p>
        <strong>Ouvrier :</strong>
        <span class="detail-value" id="detailNomOuvrier">-</span>
    </p>

    <p>
        <strong>Avancement :</strong>
        <span class="detail-value" id="detailPourcentage">-</span>
    </p>

    <p>
        <strong>Commentaire :</strong>
    </p>

    <div class="detail-box detail-value" id="detailCommentaire">
        -
    </div>

    <p>
        <strong>Statut :</strong>
        <span class="detail-value" id="detailStatut">-</span>
    </p>

    <p>
        <strong>Date de validation :</strong>
        <span class="detail-value" id="detailDate">-</span>
    </p>

</div>
    </div>
</div>