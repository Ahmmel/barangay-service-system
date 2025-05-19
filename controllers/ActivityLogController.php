<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class ActivityLogController
{
    private $activityLogModel;

    public function __construct($db)
    {
        $this->activityLogModel = new ActivityLog($db);
    }

    // Handle add activity log request
    public function addLog()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['userId'] ?? null;
            $roleId = $_POST['roleId'] ?? null;
            $activity = $_POST['activity'] ?? '';
            $status = $_POST['status'] ?? 'Success';
            $referenceId = $_POST['referenceId'] ?? null;

            if ($userId && $roleId && $activity) {
                $result = $this->activityLogModel->createLog($userId, $roleId, $activity, $status, $referenceId);
                echo json_encode(["success" => $result]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'User ID, Role ID, and Activity are required.'
                ]);
            }
        }
    }

    // Get recent logs (with optional limit)
    public function getRecentLogs()
    {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $logs = $this->activityLogModel->getRecentLogs($limit);
        echo json_encode($logs);
    }
}

// Router
$database = new Database();
$db = $database->getConnection();
$controller = new ActivityLogController($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $controller->addLog();
        break;
    case 'getRecent':
        $controller->getRecentLogs();
        break;
    default:
        echo json_encode(["error" => "Invalid request"]);
}
