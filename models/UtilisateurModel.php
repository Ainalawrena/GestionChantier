<?php

class UtilisateurModel {
    private $pdo; // connexion BDD

    public function __construct($pdo) {
        $this->pdo = $pdo; // reçoit la connexion
    }

    public function login($login) {
        $sql = "SELECT u.*, r.libelle AS role 
            FROM utilisateur u
            JOIN role r ON u.id_role = r.id_role
            WHERE u.login = :login 
            LIMIT 1";
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute([$login]);
        $utilisateur = $stmt->fetch();

        return $utilisateur;
    }

    public function creer($data) {
        $sql = "INSERT INTO utilisateur(nom, email, adresse, login, password_hash, id_role)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql); // ← $this->pdo pas $pdo !
        $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['adresse'],
            $data['login'],
            password_hash($data['password'], PASSWORD_BCRYPT), // ← hashage ici !
            $data['id_role'] =(int)$data['id_role']
        ]);
    }

    public function getUtilisateurs() {
        $sql = "SELECT id_user, nom FROM utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $tout = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tout;
    }

}

?>