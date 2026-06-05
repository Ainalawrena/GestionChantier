<?php 

?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Tableau de bord</h1>
        <p><?= date('d F Y') ?></p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Chantiers actifs</h3>
            <h2>24</h2>
            <p>+12% ce mois</p>
        </div>
        <!-- Répéter pour Avancement moyen, Budget consommé, Tâches en retard -->
    </div>

    <!-- Graphiques -->
    <div class="charts-row">
        <div class="chart-card">
            <h3>Avancement des chantiers</h3>
            <!-- Chart.js ou Chartist ici -->
        </div>
        <div class="chart-card">
            <h3>Répartition des tâches</h3>
            <!-- Pie Chart -->
        </div>
    </div>

    <!-- Tables récentes -->
    <div class="recent-sections">
        <div>Chantiers récents</div>
        <div>Dépenses récentes</div>
        <div>Tâches du jour</div>
    </div>
</div>

<style>
/* Tu peux mettre le CSS ici ou dans un fichier admin.css */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin: 30px 0;
}
.stat-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}
</style>