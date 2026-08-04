<?php
class NotificationModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creer($id_utilisateur, $titre, $message, $type, $lien = null) {
        $sql = "INSERT INTO notification (id_utilisateur, titre, message, type, lien)
                VALUES (?, ?, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([
            $id_utilisateur, $titre, $message, $type, $lien
        ]);
    }

    public function getNonLues($id_utilisateur) {
        $sql = "SELECT * FROM notification
                WHERE id_utilisateur = ? AND lu = false
                ORDER BY date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTout($id_utilisateur, $limit = 20) {
        $sql = "SELECT * FROM notification
                WHERE id_utilisateur = ?
                ORDER BY date_creation DESC
                LIMIT ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_utilisateur, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marquerLu($id_notification, $id_utilisateur) {
        $sql = "UPDATE notification SET lu = true
                WHERE id_notification = ? AND id_utilisateur = ?";
        $this->pdo->prepare($sql)->execute([$id_notification, $id_utilisateur]);
    }

    public function marquerToutLu($id_utilisateur) {
        $sql = "UPDATE notification SET lu = true WHERE id_utilisateur = ?";
        $this->pdo->prepare($sql)->execute([$id_utilisateur]);
    }

    public function compterNonLues($id_utilisateur) {
        $sql = "SELECT COUNT(*) FROM notification
                WHERE id_utilisateur = ? AND lu = false";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_utilisateur]);
        return (int) $stmt->fetchColumn();
    }

    public function supprimer($id_notification, $id_utilisateur) {
    $sql = "DELETE FROM notification
            WHERE id_notification = ? AND id_utilisateur = ?";
    $this->pdo->prepare($sql)->execute([$id_notification, $id_utilisateur]);
    }
}