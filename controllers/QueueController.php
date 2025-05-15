<?php
require_once '../config/Database.php';
require_once '../models/Queue.php';
require_once '../models/Transaction.php';
require_once '../models/SMSNotification.php';

class QueueController
{
    private $queue;
    private $transaction;
    private $sms;

    public function __construct($db)
    {
        $this->queue = new Queue($db);
        $this->transaction = new Transaction($db);
        $this->sms = new SMSNotification($db);
    }

    // Add a new queue entry
    public function addQueue()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $transactionType = $_POST['type'] ?? null;
            $transactionCode = $_POST['transaction_code'] ?? null;
            $scheduledDate = $_POST['scheduled_date'] ?? null;

            if ($userId && $transactionType && $transactionCode) {
                $result = $this->queue->addQueue($userId, $transactionType, $transactionCode, $scheduledDate);
                echo json_encode($result ?
                    ['success' => true, 'queue_id' => $result] :
                    ['success' => false, 'message' => 'Failed to add to queue.']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
            }
        }
    }

    // Assign a queue to staff
    public function assignQueue()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $queueId = $_POST['queue_id'] ?? null;
            $staffId = $_POST['staff_id'] ?? null;

            if ($queueId && $staffId) {
                $success = $this->queue->nextQueue($queueId, $staffId);
                echo json_encode(['success' => $success]);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing queue_id or staff_id.']);
            }
        }
    }

    // Mark a queue as no-show
    public function markNoShow()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionCode = $_POST['transaction_code'] ?? null;
            $staffId = $_POST['staff_id'] ?? null;

            if ($transactionCode && $staffId) {
                $this->queue->setToAssignedTransaction($transactionCode, $staffId);
                $this->transaction->setCancelStatusByTransactionCode($transactionCode, $staffId);
                $this->notifyNextQueue();
                echo json_encode(['success' => true]);
                return;
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
                return;
            }
        }
    }

    private function notifyNextQueue()
    {
        // Get the next queue details
        $nextQueueTransactionCode = $this->queue->getTodayNextQueueCode();
        if ($nextQueueTransactionCode) {
            // Send notification to the next queue
            $this->sms->sendOptionalEarlyArrivalNotification($nextQueueTransactionCode);
        }
    }
}


// Set up the database connection
$database = new Database();
$db = $database->getConnection();

// Instantiate the controller with DB dependency
$controller = new QueueController($db);

// Get the action from the query string
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $controller->addQueue();
        break;
    case 'assign':
        $controller->assignQueue();
        break;
        break;
    case 'markNoShow':
        $controller->markNoShow();
        break;
    default:
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Invalid request"]);
}
