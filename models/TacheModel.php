<?php

class TacheModel {
    private $pdo; // connexion BDD

    public function __construct($pdo) {
        $this->pdo = $pdo; // reçoit la connexion
    }
    
    public function getJalonsTache($id_tache) {
        $sql = "SELECT j.ordre, j.nom, j.pourcentage
                FROM jalon j
                WHERE j.id_tache = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);
        $jalons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $jalons;
    }
public function commencerTache($id_tache) {
    try {
        // 1. Démarrer la transaction
        $this->pdo->beginTransaction();

        // 2. Première mise à jour
        $sql = "UPDATE affectation_tache SET date_debut_reelle = CURRENT_DATE WHERE id_tache = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tache]);

        // 3. Seconde mise à jour (Correction de la chaîne de caractères)
        // Note : Essayez 'En cours' avec une majuscule si 'en cours' ne fonctionne pas, 
        // selon la configuration de votre ENUM PostgreSQL.
        $sql2 = "UPDATE tache SET statut = 'en cours' WHERE id_tache = ?";
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute([$id_tache]);

        // 4. Valider les changements
        $this->pdo->commit();
        return true;

    } catch (Exception $e) {
        // En cas d'erreur, on annule tout pour ne pas corrompre la BDD
        $this->pdo->rollBack();
        // Pour votre projet académique, ce die() vous affichera la vraie erreur PostgreSQL à l'écran
        die("Erreur PostgreSQL lors du changement de statut : " . $e->getMessage());
    }
}

}
?>