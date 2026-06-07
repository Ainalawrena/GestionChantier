<div id="" class="tab-content">
    <h2>Tableau de bord Administrateur</h2>
    <p>Bienvenue sur votre tableau de bord, <?= htmlspecialchars(SessionManager::getNom()) ?>. Ici, vous pouvez gérer tous les aspects de vos chantiers, utilisateurs et modèles de tâches.</p>

    <div class="admin-panels">
        <div class="admin-panel">
            <h3>Gestion des Chantiers</h3>
            <p>Créez, modifiez ou supprimez des chantiers. Attribuez des chefs de chantier et suivez l'avancement global.</p>
            <a href="index.php?page=admin&action=manage_chantiers" class="btn-primary">Gérer les chantiers</a>
        </div>
</div>

        <div class="admin-panel">
            <h3>Gestion des Utilisateurs</h3>
            <p>Ajoutez, modifiez ou supprimez des utilisateurs. Attribuez des rôles et gérez les accès.</p>
            <a href="index.php?page=admin&action=manage_users" class="btn-primary">Gérer les utilisateurs</a>
        </div>

        <div class="admin-panel">
            <h3>Gestion des Modèles de Tâches</h3>
            <p>Créez et gérez des modèles de tâches pour standardiser les processus sur vos chantiers.</p>
            <a href="index.php?page=admin&action=manage_task_templates" class="btn-primary">Gérer les modèles de tâches</a>
        </div>
    </div>