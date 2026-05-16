<?php
session_start();
require '../includes/config.php';
$id_chantier = $_POST['id_chantier'];
$userAjouter = $_POST['id_user'];
$sonrole = $_POST['roleid_role'];

$verif = "SELECT * FROM affectation_chantier
        WHERE utilisateursid_utilisateur = ?
        AND chantierid_chantier = ?";

$stmt = $pdo->prepare($verif);
$stmt->execute([$userAjouter,$id_chantier]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($result)){
    $insert = "INSERT INTO affectation_chantier
        (utilisateursid_utilisateur, chantierid_chantier, roleid_role)
        VALUES (?, ?, ?)
    ";
    $stmtInsert = $pdo->prepare($insert);
    $stmtInsert->execute([$userAjouter,$id_chantier,$sonrole]);
}

else {
     // Redirige avec message d'erreur dans l'URL
    header('Location: Chef.php?id_chantier=' . $id_chantier . '&erreur=deja_affecte');
    exit;
}
// Retourne vers Chef.php avec le bon chantier

header('Location: Chef.php?id_chantier=' . $id_chantier . '&success=ajoute');
?>