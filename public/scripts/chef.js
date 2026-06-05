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

function afficherFormulaireNouveauTache() {
    document.getElementById('formulaireTache').style.display = 'block';
}

function cacherFormulaireNouveauTache() {
    document.getElementById('formulaireTache').style.display = 'none';
}

function afficherFormulaireOuvrier() {
    document.getElementById('formulaireOuvrier').style.display = 'block';
}

function cacherFormulaireOuvrier() {
    document.getElementById('formulaireOuvrier').style.display = 'none';
}

function afficherFormulaireAffectation(idUser, nomOuvrier) {
    document.getElementById('formulaireAffectation').style.display = 'block';
    document.getElementById('nomOuvrier').textContent = nomOuvrier;
    document.getElementById('idOuvrier').value = idUser;
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

function afficherFormulaireAvancement(idTache, nomTache, pourcentage) {
    document.getElementById('formulaireAvancement').style.display = 'block';
    document.getElementById('nomTache').textContent = nomTache;
    document.getElementById('idTache').value = idTache;
    document.getElementById('pourcentageTache').value = pourcentage;
}

function cacherFormulaireAvancement() {
    document.getElementById('formulaireAvancement').style.display = 'none';
}

function afficherFormulaireIncident() {
    document.getElementById('formulaireIncident').style.display = 'block';
}

function cacherFormulaireIncident() {
    document.getElementById('formulaireIncident').style.display = 'none';
}


function afficherFormulaireAvancement(idTache, nomTache, jalons) {
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
    
    // 5. Rendre le bloc du formulaire visible à l'écran
    document.getElementById('formulaireAvancement').style.display = 'block';
    
    // Optionnel : Faire défiler la page automatiquement jusqu'au formulaire
    document.getElementById('formulaireAvancement').scrollIntoView({ behavior: 'smooth' });
}

function cacherFormulaireAvancement() {
    // Masquer le formulaire si l'ouvrier clique sur "Annuler"
    document.getElementById('formulaireAvancement').style.display = 'none';
}

function ouvrirDetailsTache(idTache) {
    // Message temporaire pour votre bouton "Voir plus" en attendant le développement
    alert("Historique des étapes soumises pour la tâche numéro : " + idTache);
}

