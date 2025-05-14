<?php
require_once '../config/database.php';
require_once '../models/Notification.php';

class NotificationController
{
    private $notificationModel;

    public function __construct($db)
    {
        $this->notificationModel = new Notification($db);
    }

    // Get unread notifications
    public function getUnreadNotifications()
    {
        if (isset($_GET['user_id']) && isset($_GET['role_id'])) {
            $userId = $_GET['user_id'];
            $roleId = $_GET['role_id'];

            $notifications = $this->notificationModel->getUnreadNotifications($userId, $roleId);
            echo json_encode($notifications);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'User ID and Role ID are required.'
            ]);
        }
    }

    // Mark a specific notification as read
    public function markAsRead()
    {
        if (isset($_POST['notification_id'])) {
            $notificationId = $_POST['notification_id'];
            // $result = $this->notificationModel->markAsRead($notificationId);

            // echo json_encode(["success" => $result]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Notification ID is required.'
            ]);
        }
    }
}

// Router logic
$database = new Database();
$db = $database->getConnection();
$controller = new NotificationController($db);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getUnread':
        $controller->getUnreadNotifications();
        break;
    case 'markAsRead':
        $controller->markAsRead();
        break;
    default:
        echo json_encode(["error" => "Invalid request"]);
}
