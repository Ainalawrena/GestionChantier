<div id="ouvriers" class="tab-content">

    <div class="admin-toolbar">
        <div class="admin-toolbar-left">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchUser" placeholder="Rechercher un utilisateur..." oninput="filtrerUsers()">
            </div>
            <select id="filtreRole" onchange="filtrerUsers()" class="filtre-select">
                <option value="">Tous les rôles</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= htmlspecialchars($r['libelle']) ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-primary" onclick="ouvrirModal('modalCreerUser')">
            <i class="fa-solid fa-user-plus"></i> Nouvel utilisateur
        </button>
    </div>

    <table class="tableau" id="tableUsers">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Rôle</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $u): ?>
                <tr data-nom="<?= strtolower(htmlspecialchars($u['nom'] . ' ' . $u['email'])) ?>" data-role="<?= htmlspecialchars($u['role']) ?>">
                    <td><?= htmlspecialchars($u['nom']) ?></td>
                    <td>
                        <span class="role-badge role-<?= strtolower(str_replace(' ', '-', $u['role'])) ?>">
                            <?= htmlspecialchars($u['role']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <button class="action-icon-btn"
                                onclick='ouvrirModalDetailUser(<?= json_encode($u) ?>)'
                                title="Voir détail">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="action-icon-btn"
                                onclick='ouvrirModalModifierUser(<?= json_encode($u) ?>)'
                                title="Modifier">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- MODAL Détail utilisateur -->
    <div class="modal-overlay" id="modalDetailUser">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-user"></i> <span id="duNom"></span></h3>
                <button class="modal-close" onclick="fermerModal('modalDetailUser')">✕</button>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value" id="duEmail">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Login</span>
                    <span class="detail-value" id="duLogin">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Adresse</span>
                    <span class="detail-value" id="duAdresse">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Rôle</span>
                    <span class="detail-value" id="duRole">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Chantiers affectés</span>
                    <span class="detail-value" id="duChantiers">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tâches assignées</span>
                    <span class="detail-value" id="duTaches">-</span>
                </div>
            </div>

            <div class="modal-footer">
                <a id="duBtnSupprimer" href="#" class="btn-danger"
                   onclick="return confirm('Supprimer cet utilisateur ?')">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </a>
                <button class="btn-annuler" onclick="fermerModal('modalDetailUser')">Fermer</button>
            </div>
        </div>
    </div>

    <!-- MODAL Créer utilisateur -->
    <div class="modal-overlay" id="modalCreerUser">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-user-plus"></i> Nouvel utilisateur</h3>
                <button class="modal-close" onclick="fermerModal('modalCreerUser')">✕</button>
            </div>
            <form method="POST" action="index.php?page=auth&action=creerUtilisateurAdmin">
                <label>Nom :</label>
                <input type="text" name="nom" required>

                <label>Email :</label>
                <input type="email" name="email" required>

                <label>Adresse :</label>
                <input type="text" name="adresse">

                <label>Login :</label>
                <input type="text" name="login" required>

                <label>Mot de passe :</label>
                <input type="password" name="password" required>

                <label>Rôle :</label>
                <select name="id_role" required>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_role'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="modal-footer">
                    <button type="button" class="btn-annuler" onclick="fermerModal('modalCreerUser')">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Créer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL Modifier utilisateur -->
    <div class="modal-overlay" id="modalModifierUser">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fa-solid fa-pen"></i> Modifier l'utilisateur</h3>
                <button class="modal-close" onclick="fermerModal('modalModifierUser')">✕</button>
            </div>
            <form method="POST" action="index.php?page=auth&action=modifierUtilisateur">
                <input type="hidden" name="id_user" id="muId">

                <label>Nom :</label>
                <input type="text" name="nom" id="muNom" required>

                <label>Email :</label>
                <input type="email" name="email" id="muEmail" required>

                <label>Adresse :</label>
                <input type="text" name="adresse" id="muAdresse">

                <label>Login :</label>
                <input type="text" name="login" id="muLogin" required>

                <label>Nouveau mot de passe <small>(laisser vide pour ne pas changer)</small> :</label>
                <input type="password" name="password">

                <label>Rôle :</label>
                <select name="id_role" id="muRole" required>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_role'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="modal-footer">
                    <button type="button" class="btn-annuler" onclick="fermerModal('modalModifierUser')">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filtrerUsers() {
    const search = document.getElementById('searchUser').value.toLowerCase();
    const role   = document.getElementById('filtreRole').value;
    document.querySelectorAll('#tableUsers tbody tr').forEach(row => {
        const matchNom  = row.dataset.nom.includes(search);
        const matchRole = !role || row.dataset.role === role;
        row.style.display = (matchNom && matchRole) ? '' : 'none';
    });
}

function ouvrirModalDetailUser(u) {
    document.getElementById('duNom').textContent      = u.nom;
    document.getElementById('duEmail').textContent    = u.email;
    document.getElementById('duLogin').textContent    = u.login;
    document.getElementById('duAdresse').textContent  = u.adresse || '-';
    document.getElementById('duRole').textContent     = u.role;
    document.getElementById('duChantiers').textContent= u.nb_chantiers;
    document.getElementById('duTaches').textContent   = u.nb_taches;
    document.getElementById('duBtnSupprimer').href =
        `index.php?page=auth&action=supprimerUtilisateur&id_user=${u.id_user}`;
    ouvrirModal('modalDetailUser');
}

function ouvrirModalModifierUser(u) {
    document.getElementById('muId').value      = u.id_user;
    document.getElementById('muNom').value     = u.nom;
    document.getElementById('muEmail').value   = u.email;
    document.getElementById('muAdresse').value = u.adresse ?? '';
    document.getElementById('muLogin').value   = u.login;
    document.getElementById('muRole').value    = u.id_role;
    ouvrirModal('modalModifierUser');
}
</script>