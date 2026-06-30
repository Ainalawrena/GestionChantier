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

    public function getUtilisateursSaufConnecte(){
        $userConnecter = SessionManager::getUserId();
        $sql = "SELECT id_user,nom FROM utilisateur WHERE id_user != :id AND id_role !=1 AND id_role !=4";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userConnecter]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // ===== Méthodes Admin ajoutées =====

    public function getTousAvecStats() {
        $sql = "SELECT u.id_user, u.nom, u.email, u.adresse, u.login,
                r.libelle AS role, r.id_role,
                COUNT(DISTINCT ac.id_chantier) AS nb_chantiers,
                COUNT(DISTINCT t.id_tache) AS nb_taches
                FROM utilisateur u
                JOIN role r ON u.id_role = r.id_role
                LEFT JOIN affectation_chantier ac ON ac.id_utilisateur = u.id_user
                LEFT JOIN tache t ON t.id_utilisateur = u.id_user
                GROUP BY u.id_user, u.nom, u.email, u.adresse, u.login, r.libelle, r.id_role
                ORDER BY u.id_user DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoles() {
        $stmt = $this->pdo->query("SELECT * FROM role ORDER BY id_role");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifier($data) {
        $sql = "UPDATE utilisateur SET nom = ?, email = ?, adresse = ?, login = ?, id_role = ?
                WHERE id_user = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['adresse'],
            $data['login'],
            $data['id_role'],
            $data['id_user']
        ]);
    }

    public function changerMotDePasse($id_user, $password) {
        $sql = "UPDATE utilisateur SET password_hash = ? WHERE id_user = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            password_hash($password, PASSWORD_BCRYPT),
            $id_user
        ]);
    }

    public function supprimer($id_user) {
        $sql = "DELETE FROM utilisateur WHERE id_user = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_user]);
    }

    public function getById($id_user) {
        $sql = "SELECT * FROM utilisateur WHERE id_user = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

?>