<?php
require_once __DIR__ . '/../models/NotificationModel.php';

class NotificationController {
    private $model;

    public function __construct($pdo) {
        $this->model = new NotificationModel($pdo);
    }

    public function getNotifications() {
        $id_user = $_SESSION['user_id'];
        $notifications = $this->model->getTout($id_user);
        header('Content-Type: application/json');
        echo json_encode($notifications);
        exit;
    }

    public function marquerLu() {
        $id_notification = $_POST['id_notification'];
        $this->model->marquerLu($id_notification, $_SESSION['user_id']);
        header('Content-Type: application/json');
        echo json_encode(['succes' => true]);
        exit;
    }

    public function marquerToutLu() {
        $this->model->marquerToutLu($_SESSION['user_id']);
        header('Content-Type: application/json');
        echo json_encode(['succes' => true]);
        exit;
    }
}