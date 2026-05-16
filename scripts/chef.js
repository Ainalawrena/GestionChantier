// Gestion des onglets
document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
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

function afficherFormulaireAvancement(idTache, nomTache, pourcentage) {
    document.getElementById('formulaireAvancement').style.display = 'block';
    document.getElementById('nomTache').textContent = nomTache;
    document.getElementById('idTache').value = idTache;
    document.getElementById('pourcentageTache').value = pourcentage;
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