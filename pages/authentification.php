
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login    = trim($_POST['login']    ?? '');
    $password =      $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        die("Erreur : champs vides.");
    }

    $sql  = "SELECT * FROM utilisateurs WHERE login = :login LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':login' => $login]);
    $utilisateur = $stmt->fetch();
    if (!$utilisateur) {
        die("Erreur : utilisateur introuvable.");
    }

    if (!password_verify($password, $utilisateur['password'])) {
        die("Erreur : mot de passe incorrect.");
    }

    $sql2 = "SELECT u.*, r.libelle AS role 
             FROM utilisateurs u
             JOIN role r ON u.id_role = r.id_role
             WHERE u.id_user = :id";

    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([':id' => $utilisateur['id_user']]);
    $details = $stmt2->fetch();

    session_start();

    $_SESSION['user_id'] = $details['id_user'];
    $_SESSION['nom']     = $details['nom'];
    $_SESSION['role']    = $details['role'];
    
    header('Location: choice.php');
    exit;
}
?>