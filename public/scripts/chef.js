// Gestion des onglets
document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', () => {

        document.querySelectorAll('.nav-btn')
            .forEach(b => b.classList.remove('active'));

        document.querySelectorAll('.tab-content')
            .forEach(c => c.classList.remove('active'));

        btn.classList.add('active');

        const tab = document.getElementById(btn.dataset.tab);

        if (tab) {
            tab.classList.add('active');
        } else {
            console.error("Tab introuvable :", btn.dataset.tab);
        }
    });
});



function cacherFormulaireNouveauTache() {
    document.getElementById('formulaireTache').style.display = 'none';
}



function cacherFormulaireOuvrier() {
    document.getElementById('formulaireOuvrier').style.display = 'none';
}

function cacherFormulaireAffectation() {
    document.getElementById('formulaireAffectation').style.display = 'none';
}

function cacherFormulaireOuvrier() {
    document.getElementById('formulaireOuvrier').style.display = 'none';
}

function cacherFormulaireAvancement() {
    document.getElementById('formulaireAvancement').style.display = 'none';
}


function cacherFormulaireAvancement() {
    document.getElementById('formulaireAvancement').style.display = 'none';
}


function cacherFormulaireIncident() {
    document.getElementById('formulaireIncident').style.display = 'none';
}


function fermerModal(id) {
    document.getElementById(id).classList.remove('active');
}

// Fermer en cliquant sur l'overlay
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});


function cacherFormulaireAvancement() {
    // Masquer le formulaire si l'ouvrier clique sur "Annuler"
    document.getElementById('formulaireAvancement').style.display = 'none';
}


function ouvrirModalAvancement(idTache, nomTache) {
    document.getElementById('nomTache').textContent = nomTache;
    document.getElementById('idTache').value = idTache;
    ouvrirModal('modalAvancement');
}




//=========================================AFFICHAGE DES FORMULAIRES==============================================================
function ouvrirModal(id) {
    document.getElementById(id).classList.add('active');
}


function afficherFormulaireNouveauTache() {
    ouvrirModal('modalTache');
}

function afficherFormulaireModifierTache(idTache) {
    document.getElementById("id_tache").value = idTache;
    ouvrirModal('modalModifierTache');
}

function afficherFormulaireAffectation(idUser, nomOuvrier) {
    document.getElementById('nomOuvrier').textContent = nomOuvrier;
    document.getElementById('idOuvrier').value = idUser;
    ouvrirModal('modalAffectation');
}

function afficherFormulaireIncident() {
    document.getElementById('formulaireIncident').style.display = 'block';
}

function afficherFormulaireOuvrier() {
    document.getElementById('formulaireOuvrier').style.display = 'block';
}

function afficherFormulaireAvancement(idTache, nomTache, jalons) {
    console.log("Bouton cliqué");
    // 1. Assigner l'ID de la tâche au champ masqué du formulaire
    document.getElementById('idTache').value = idTache;
    
    // 2. Afficher le nom de la tâche dans le titre <h3>
    document.getElementById('nomTache').innerText = nomTache;
    
    // 3. Récupérer la liste déroulante (select) et la vider
    const selectJalon = document.getElementById('selectJalon');
    selectJalon.innerHTML = '<option value="">-- Choisir un jalon atteint --</option>';
    
    // 4. Remplir dynamiquement le select avec les jalons de la tâche
    if (jalons && jalons.length > 0) {
        jalons.forEach(jalon => {
            const option = document.createElement('option');
            
            // TRÈS IMPORTANT : On envoie le pourcentage comme valeur au contrôleur PHP
            option.value = jalon.pourcentage; 
            
            // Texte affiché à l'ouvrier (ex: "Tuyauterie encastrée (25%)")
            option.text = `${jalon.nom} (${jalon.pourcentage}%)`;
            
            selectJalon.appendChild(option);
        });
    } else {
        const option = document.createElement('option');
        option.text = "Aucun jalon configuré pour cette tâche";
        selectJalon.appendChild(option);
    }
    
    ouvrirModal('modalAvancement');
}


//=========
// Toggle dropdown
function toggleDropdown(btn) {
    const menu = btn.nextElementSibling;
    
    // Ferme tous les autres dropdowns
    document.querySelectorAll('.dropdown-menu.open').forEach(m => {
        if (m !== menu) m.classList.remove('open');
    });
    
    menu.classList.toggle('open');
}

// Ferme dropdown en cliquant ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-dropdown')) {
        document.querySelectorAll('.dropdown-menu.open')
            .forEach(m => m.classList.remove('open'));
    }
});

// (Removed duplicate broken definition.)

// Ouvrir modal modifier
function ouvrirModalModifierTache(idTache) {
    fetch(`index.php?page=tache&action=detailTache&id_tache=${idTache}`)
        .then(r => r.json())
        .then(data => {
            document.querySelector('#modalModifierTache [name="id_tache"]').value            = data.id_tache;
            document.querySelector('#modalModifierTache [name="nom"]').value                 = data.nom;
            document.querySelector('#modalModifierTache [name="ordre"]').value               = data.ordre ?? '';             
            document.querySelector('#modalModifierTache [name="date_debut_prevue"]').value   = data.date_debut_prevue ?? '';
            document.querySelector('#modalModifierTache [name="date_fin_prevue"]').value     = data.date_fin_prevue ?? '';
            // Pré-sélectionner les dépendances dans le select multiple
            const select = document.querySelector('#modalModifierTache [name="dependances[]"]') || document.getElementById('selectDependances');
            if (select) {
                // Réinitialiser selections et enable
                Array.from(select.options).forEach(o => { o.selected = false; o.disabled = false; });

                if (data.dependances && data.dependances.length > 0) {
                    const depIds = data.dependances.map(d => String(d.id_tache));
                    Array.from(select.options).forEach(o => {
                        if (depIds.includes(o.value)) o.selected = true;
                    });
                }

                // Empêcher de choisir la tâche elle-même
                Array.from(select.options).forEach(o => {
                    if (o.value == data.id_tache) o.disabled = true;
                });
            }

            ouvrirModal('modalModifierTache');
        });
}function ouvrirModalDetailTache(idTache) {
    console.log('🔍 Ouverture détail tâche ID:', idTache);
    fetch(`index.php?page=tache&action=detailTache&id_tache=${idTache}`)
        .then(r => {
            if (!r.ok) throw new Error('Erreur serveur');
            return r.json();
        })
        .then(data => {
            console.log('✅ Données reçues :', data);
            const modal = document.getElementById('modalDetailTache');

            modal.querySelector('#detailNom').textContent         = data.nom || '-';
            modal.querySelector('#detailStatut').textContent      = data.statut || '-';
            modal.querySelector('#detailOrdre').textContent       = data.ordre ?? '-';
            modal.querySelector('#detailPourcentage').textContent = (data.pourcentage ?? '-') + '%';
            modal.querySelector('#detailDebut').textContent       = data.date_debut_prevue || '-';
            modal.querySelector('#detailFin').textContent         = data.date_fin_prevue || '-';
            modal.querySelector('#detailOuvrier').textContent     = data.nom_ouvrier || '-';

            // Dépendances
            let dependances = '-';
            if (data.dependances && data.dependances.length > 0) {
                dependances = data.dependances.map(dep => dep.nom).join(', ');
            }
            modal.querySelector('#detailDependence').textContent = dependances;

            // ✅ Vérifie que le bouton existe (absent côté ouvrier)
            const btnSupprimer = document.getElementById('btnSupprimerTache');
            if (btnSupprimer) {
                btnSupprimer.href =
                    `index.php?page=tache&action=supprimerTache&id_tache=${idTache}&id_chantier=${data.id_chantier || ''}`;
            }

            ouvrirModal('modalDetailTache');
        })
        .catch(err => {
            console.error('Erreur lors du chargement des détails:', err);
            alert('Impossible de charger les détails de la tâche.');
        });
}