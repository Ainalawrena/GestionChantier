<?php
class ValidationModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function valider($data) 
    {
        try {
            // On sécurise le tout avec une transaction
            $this->pdo->beginTransaction();

            // 1. INSERTION de la validation de l'architecte
            $sql = "INSERT INTO validation (statut_validation, date_validation, id_avancement, id_utilisateur)
                    VALUES (?, CURRENT_DATE, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data['statut_validation'],
                $data['id_avancement'],
                $data['id_utilisateur']
            ]);

            // CORRECTION A : Il faut aussi mettre à jour le statut dans la table avancement !
            // Sinon la tâche reste marquée "En attente" sur le tableau de l'ouvrier
            $sqlUpdateAvancement = "UPDATE avancement SET statut_validation = ? WHERE id_avancement = ?";
            $this->pdo->prepare($sqlUpdateAvancement)->execute([
                $data['statut_validation'],
                $data['id_avancement']
            ]);

            // 2. Si l'architecte a choisi 'valide' → UPDATE tache.pourcentage
            if ($data['statut_validation'] === 'valide') {

                // CORRECTION B : On remplace 'pourcentage' par 'pourcentage_soumis' 
                // pour correspondre au nom exact de votre table 'avancement'
                $sql2 = "SELECT pourcentage_soumis, id_tache FROM avancement
                         WHERE id_avancement = ?";
                $stmt = $this->pdo->prepare($sql2);
                $stmt->execute([$data['id_avancement']]);
                $avancement = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($avancement) {
                    // Détermination du statut de la tâche (Prise en compte de votre ENUM 'en cours')
                    $nouveauStatut = ((int)$avancement['pourcentage_soumis'] === 100) ? 'termine' : 'en cours';

                    // Mettre à jour la table tâche avec les bonnes valeurs
                    $sql3 = "UPDATE tache SET pourcentage = ?, statut = ?
                             WHERE id_tache = ?";
                    $this->pdo->prepare($sql3)->execute([
                        $avancement['pourcentage_soumis'],
                        $nouveauStatut,
                        $avancement['id_tache']
                    ]);
                }
            }

            // Si tout s'est bien passé, on valide les modifications dans PostgreSQL
            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            // En cas de bug, on annule tout pour éviter de corrompre les données
            $this->pdo->rollBack();
            // Affichage de l'erreur pour vous aider pendant le développement
            die("Erreur lors de la validation : " . $e->getMessage());
        }
    }

    public function getAvancementsAValider($id_chantier) {
        $sql = "SELECT a.id_avancement, a.pourcentage, a.commentaire,
                t.nom AS nom_tache, t.id_tache,
                u.nom AS nom_ouvrier,
                m.date_mise_a_jour
                FROM avancement_tache a
                JOIN tache t ON a.id_tache = t.id_tache
                JOIN modifier m ON a.id_avancement = m.id_avancement
                JOIN utilisateur u ON m.id_utilisateur = u.id_user
                LEFT JOIN validation v ON a.id_avancement = v.id_avancement
                WHERE t.id_chantier = ?
                AND v.id_validation IS NULL
                ORDER BY m.date_mise_a_jour ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_chantier]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
}
?>