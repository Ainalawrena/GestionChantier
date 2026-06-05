<?php
class SessionManager implements SessionHandlerInterface {

    private PDO $pdo;
    private int $duree = 3600;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function open($path, $name): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

 public function read($id): string {
    //  Compare avec NOW() en heure locale PHP
    $stmt = $this->pdo->prepare("
        SELECT donnees 
        FROM session
        WHERE id_session = ?
        AND date_expiration > ?
    ");

    $stmt->execute([$id, date('Y-m-d H:i:s')]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || empty($result['donnees'])) {
        return '';
    }

    return $result['donnees'];
}

public function write($id, $data): bool {
    if (empty($data)) return true;

    // Utilise UTC+3 pour Madagascar
    $expiration = date('Y-m-d H:i:s', time() + $this->duree);

    $id_utilisateur = null;
    preg_match('/user_id\|i:(\d+)/', $data, $matches);
    if (!empty($matches[1])) {
        $id_utilisateur = (int)$matches[1];
    }

    $sql = "INSERT INTO session 
            (id_session, id_utilisateur, donnees, date_expiration, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?)
            ON CONFLICT (id_session)
            DO UPDATE SET
                donnees         = EXCLUDED.donnees,
                date_expiration = EXCLUDED.date_expiration,
                id_utilisateur  = EXCLUDED.id_utilisateur";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        $id,
        $id_utilisateur,
        $data,
        $expiration,
        $ip ?? $_SERVER['REMOTE_ADDR'] ?? '',
        $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
}

    public function destroy($id): bool {
        $this->pdo->prepare("DELETE FROM session WHERE id_session = ?")
            ->execute([$id]);

        return true;
    }

    public function gc($max_lifetime): int|false {
        $stmt = $this->pdo->prepare("
            DELETE FROM session 
            WHERE date_expiration < NOW()
        ");

        $stmt->execute();

        return $stmt->rowCount();
    }

    // =========================
    // AUTH HELPERS
    // =========================

    public static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=loginForm');
            exit;
        }
    }

    public static function requireRole($roles) {
        self::requireLogin();

        if (!in_array($_SESSION['role'] ?? '', $roles)) {
            header('Location: index.php?page=auth&action=loginForm');
            exit;
        }
    }

    public static function login($utilisateur) {
        $_SESSION['user_id'] = $utilisateur['id_user'] ?? $utilisateur['id_utilisateur'];
        $_SESSION['nom']     = $utilisateur['nom'];
        $_SESSION['role']    = $utilisateur['role'];

    }

    public static function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?page=auth&action=loginForm');
        exit;
    }

    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getRole() {
        return $_SESSION['role'] ?? null;
    }

    public static function getNom() {
        return $_SESSION['nom'] ?? null;
    }
}