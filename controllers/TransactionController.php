<?php
require_once '../config/database.php';
require_once '../models/Transaction.php';
require_once '../models/Queue.php';
require_once '../models/SMSNotification.php';
class TransactionController
{
    private $TransactionModel;
    private $QueueModel;
    private $SMS;

    public function __construct($db)
    {
        $this->TransactionModel = new Transaction($db);
        $this->QueueModel = new Queue($db);
        $this->SMS = new SMSNotification();
    }

    // Get Transaction Prequisites
    public function getServices()
    {
        $services = $this->TransactionModel->getServices();
        echo json_encode($services);
    }
    // Handle Add Transaction with services
    public function addTransactionWithServices()
    {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Collect parameters from POST data
        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
        $serviceIds = isset($_POST['serviceIds']) ? $_POST['serviceIds'] : [];
        $scheduledTime = isset($_POST['scheduledTime']) ? $_POST['scheduledTime'] : null;

        // Ensure serviceIds is an array
        if (!is_array($serviceIds)) {
            $serviceIds = [$serviceIds];
        }

        // Validate inputs
        if (empty($userId) || empty($serviceIds)) {
            echo json_encode(['success' => false, 'message' => 'User ID and service IDs are required']);
            return;
        }

        try {
            // Generate a unique transaction code
            $transactionCode = "Q-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -5));

            // Add the queue entry
            $queueId = $this->QueueModel->addQueue($userId, $transactionCode, $scheduledTime);

            if (!$queueId) {
                throw new Exception('Failed to create a queue entry');
            }

            // Create the transaction with services
            $result = $this->TransactionModel->createTransactionWithServices($userId, $serviceIds, $transactionCode, $queueId, $scheduledTime);

            if (!$result['success']) {
                throw new Exception($result['message'] ?? 'Failed to create transaction with services');
            }

            // Send SMS confirmation if phone number is available
            $userPhone = $this->TransactionModel->getUserPhoneNumber($userId);
            if ($userPhone) {
                $this->SMS->sendTransactionConfirmation($userPhone, $result['transaction_code'], $scheduledTime);
            }

            // Return the result
            echo json_encode($result);
        } catch (Exception $e) {
            // Return an error message if anything goes wrong
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Get all transactions
    public function getAllTransactions()
    {
        $transactions = $this->TransactionModel->getAllTransactions();
        echo json_encode($transactions);
    }

    // Get Transaction by ID
    public function getTransactionById()
    {
        if (isset($_GET['transaction_id'])) {
            $transactionId = $_GET['transaction_id'];
            $transaction = $this->TransactionModel->getTransactionById($transactionId);
            echo json_encode($transaction);
        } else {
            echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
        }
    }

    // Update Transaction Status
    public function updateTransactionStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionId = $_POST['transactionId'];
            $status = $_POST['status'];
            $reason = $_POST['reason'] ?? null;

            if (empty($transactionId) || empty($status)) {
                echo json_encode(['success' => false, 'message' => 'Transaction ID and status are required']);
                return;
            }

            if ($status !== 'Completed' && (is_null($reason) || empty($reason))) {
                echo json_encode(['success' => false, 'message' => 'Reason is required for status other than Completed']);
                return;
            }

            // Update the transaction status
            $result = $this->TransactionModel->updateTransactionStatus($transactionId, $status, $reason);
            echo json_encode(['success' => $result]);
        }
    }

    // Get all services for a transaction
    public function getServicesForTransaction()
    {
        if (isset($_GET['transaction_id'])) {
            $transactionId = $_GET['transaction_id'];
            $services = $this->TransactionModel->getServicesForTransaction($transactionId);
            echo json_encode($services);
        } else {
            echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
        }
    }

    // Get transactions by status
    public function getTransactionByStatus()
    {
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
            $transactions = $this->TransactionModel->getTransactionsByStatus($status);
            echo json_encode($transactions);
        } else {
            echo json_encode(['success' => false, 'message' => 'Status is required']);
        }
    }
}

$database = new Database();
$db = $database->getConnection();

// Instantiate the controller with the database connection
$controller = new TransactionController($db);

// Get the action parameter from the URL query string
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $controller->addTransactionWithServices();
        break;
    case 'getTransactionById':
        $controller->getTransactionById();
        break;
    case 'updateStatus':
        $controller->updateTransactionStatus();
        break;
    case 'getServicesForTransaction':
        $controller->getServicesForTransaction();
        break;
    case 'getByStatus':
        $controller->getTransactionByStatus();
        break;
    case 'getServices':
        $controller->getServices();
        break;
    default:
        echo json_encode(['error' => 'Invalid request']);
}
