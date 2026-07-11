

function ouvrirModalChantier(data) {
    // Hero
    document.getElementById('mcHero').style.background =
        `linear-gradient(135deg, ${data.barColor}cc, ${data.barColor}55)`;

    const badge = document.getElementById('mcBadge');
    badge.textContent = data.badge;
    badge.className   = 'badge ' + data.badgeClass;

    document.getElementById('mcNom').textContent       = data.nom;
    document.getElementById('mcModeleNom').textContent = data.modele;
    document.getElementById('mcPct').textContent       = data.progression + '%';
    document.getElementById('mcDebut').textContent     = data.debut;
    document.getElementById('mcFin').textContent       = data.fin;
    document.getElementById('mcTaches').textContent    = data.nb_taches_terminees + '/' + data.nb_taches;
    document.getElementById('mcOuvriers').textContent  = data.nb_ouvriers;
    document.getElementById('mcIncidents').textContent = data.nb_incidents;

    const fill = document.getElementById('mcFill');
    fill.style.width      = data.progression + '%';
    fill.style.background = data.barColor;

    document.getElementById('mcKpiIncident').style.borderColor =
        data.nb_incidents > 0 ? 'rgba(239,68,68,0.4)' : '';

    document.getElementById('mcRetardSection').style.display =
        data.en_retard ? 'block' : 'none';

    // ── Remplit le tableau Tâches ──
    const tachesBody = document.getElementById('mcTachesBody');
    if (data.taches && data.taches.length > 0) {
        tachesBody.innerHTML = data.taches.map(t => `
            <tr>
                <td>${t.nom}</td>
                <td><span class="badge ${badgeClassStatut(t.statut)}">${t.statut}</span></td>
                <td>${t.pourcentage}%</td>
                <td>${t.nom_ouvrier ?? '-'}</td>
            </tr>
        `).join('');
    } else {
        tachesBody.innerHTML = '<tr><td colspan="4">Aucune tâche</td></tr>';
    }

    // ── Remplit le tableau Équipe ──
    const equipeBody = document.getElementById('mcEquipeBody');
    if (data.ouvriers && data.ouvriers.length > 0) {
        equipeBody.innerHTML = data.ouvriers.map(o => `
            <tr>
                <td>${o.nom}</td>
                <td>${o.email}</td>
                <td>${o.libelle}</td>
            </tr>
        `).join('');
    } else {
        equipeBody.innerHTML = '<tr><td colspan="3">Aucun membre</td></tr>';
    }

    // ── Remplit le tableau Incidents ──
    const incidentsBody = document.getElementById('mcIncidentsBody');
    if (data.incidents && data.incidents.length > 0) {
        incidentsBody.innerHTML = data.incidents.map(i => `
            <tr>
                <td>${i.nom_tache}</td>
                <td>${i.description}</td>
                <td><span class="badge ${badgeClassGravite(i.gravite)}">${i.gravite}</span></td>
                <td><span class="badge ${badgeClassStatut(i.statut)}">${i.statut}</span></td>
            </tr>
        `).join('');
    } else {
        incidentsBody.innerHTML = '<tr><td colspan="4">Aucun incident</td></tr>';
    }

    // Reset sur l'onglet Aperçu à chaque ouverture
    switchMcTab('apercu');
    ouvrirModal('modalChantier');
}

function switchMcTab(tab) {
    document.querySelectorAll('.mc-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.mc-tab-content').forEach(c => c.classList.remove('active'));

    document.querySelector(`.mc-tab-btn[data-mctab="${tab}"]`).classList.add('active');
    document.getElementById(`mctab-${tab}`).classList.add('active');
}

function badgeClassStatut(statut) {
    return {
        'en attente': 'badge-attente',
        'en cours':   'badge-encours',
        'termine':    'badge-termine',
        'bloque':     'badge-bloque',
        'ouvert':     'badge-bloque',
        'resolu':     'badge-termine',
    }[statut] || 'badge-attente';
}

function badgeClassGravite(gravite) {
    return {
        'critique': 'badge-bloque',
        'moyen':    'badge-encours',
        'faible':   'badge-attente',
    }[gravite] || 'badge-attente';
}