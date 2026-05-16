<?php
$id_chantier = isset($_GET['id_chantier']) ? (int)$_GET['id_chantier'] : 0;
if ($id_chantier <= 0) die("Aucun chantier sélectionné.");

// Chantier
$stmt = $pdo->prepare("SELECT * FROM chantier WHERE id_chantier = ?");
$stmt->execute([$id_chantier]);
$chantier = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$chantier) die("Chantier introuvable.");

// Tâches modèles
$stmt1 = $pdo->prepare("SELECT tm.id_tache_modele, tm.nom, tm.ordre 
    FROM tache_modele tm
    JOIN modele m ON tm.modeleid_modele = m.id_modele
    JOIN chantier c ON m.id_modele = c.modeleid_modele
    WHERE c.id_chantier = ?");
$stmt1->execute([$id_chantier]);
$tachesModele = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Tâches du chantier
$stmt2 = $pdo->prepare("SELECT t.*, u.nom AS nom_ouvrier
    FROM tache t
    LEFT JOIN utilisateurs u ON t.utilisateursid_utilisateur = u.id_user
    WHERE t.chantierid_chantier = ?
    ORDER BY t.ordre");
$stmt2->execute([$id_chantier]);
$taches = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Ouvriers du chantier
$stmt3 = $pdo->prepare("SELECT u.id_user, u.nom, u.email, r.libelle, COUNT(t.id_tache) AS nb_taches
    FROM affectation_chantier ac
    JOIN utilisateurs u ON ac.utilisateursid_utilisateur = u.id_user
    LEFT JOIN tache t ON t.utilisateursid_utilisateur = u.id_user
        AND t.chantierid_chantier = ac.chantierid_chantier
    JOIN role r ON r.id_role = ac.roleid_role
    WHERE ac.chantierid_chantier = ?
    GROUP BY u.id_user, u.nom, u.email, r.libelle");
$stmt3->execute([$id_chantier]);
$ouvriers = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// Tous les utilisateurs
$stmt4 = $pdo->query("SELECT id_user, nom FROM utilisateurs");
$tousUtilisateurs = $stmt4->fetchAll(PDO::FETCH_ASSOC);