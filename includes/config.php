<?php
$host = 'localhost';
$db   = 'gestionchantier';
$user = 'postgres';
$pass = 'a';
$port = 5432;

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); 
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>