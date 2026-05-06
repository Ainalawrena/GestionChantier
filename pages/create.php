<?php
require '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom      = trim($_POST['nom']      ?? '');
    $email    = trim($_POST['email']    ?? '');
    $adresse  = trim($_POST['adresse']  ?? '');
    $login    = trim($_POST['login']    ?? '');
    $password =      $_POST['password'] ?? '';
    $role     =      $_POST['role']     ?? '';

    if (empty($nom) || empty($email) || empty($login) || empty($password)) {
        die("Erreur : tous les champs sont obligatoires.");
    }
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (nom, login, adresse, email, password, id_role) 
        VALUES (:nom, :login, :adresse, :email, :password, :role)
    ");

    $stmt->execute([
        ':nom'      => $nom,
        ':login'    => $login,
        ':adresse'  => $adresse,
        ':email'    => $email,
        ':password' => $hashedPassword, 
        ':role'     => $role            
    ]);

    echo "Inscription réussie !";
}
?>