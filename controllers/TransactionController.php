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
        $scheduledTime = isset($_POST['scheduledTime']) ? $_POST['scheduledTime'] : date('Y-m-d H:i:s');
        $transactionType = isset($_POST['transactionType']) ? $_POST['transactionType'] : 1; // 1 for walk-in transaction

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

            // Validate transaction
            $validator = $this->TransactionModel->validateTransaction($userId, $serviceIds, $scheduledTime);
            if (!$validator['success']) {
                echo json_encode($validator);
                return;
            }

            // Generate a unique transaction code
            $transactionCode = "TRX-" . date("ymd") . "-" . strtoupper(substr(uniqid(), -3));

            // Add the queue entry
            $queueId = $this->QueueModel->addQueue($userId, $transactionType, $transactionCode, $scheduledTime);

            if (!$queueId) {
                throw new Exception('Failed to create a queue entry');
            }

            // Create the transaction with services
            $result = $this->TransactionModel->createTransactionWithServices($userId, $serviceIds, $transactionType, $transactionCode, $queueId, $scheduledTime);

            if (!$result['success']) {
                throw new Exception($result['message'] ?? 'Failed to create transaction with services');
            }

            // Send SMS confirmation if phone number is available
            $userPhone = $this->TransactionModel->getUserMobileNumber($userId);
            if ($userPhone) {
                //$this->SMS->sendWalkInTransactionNotification($userPhone, $result['transaction_code'], $scheduledTime); TODO uncomment this line to send SMS
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

    // Update Transaction Status
    public function updateTransactionStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionServiceId = $_POST['transaction_service_id'];
            $status = $_POST['status'];
            $reason = $_POST['reason'] ?? null;

            if (empty($transactionServiceId) || empty($status)) {
                echo json_encode(['success' => false, 'message' => 'Transaction ID and status are required']);
                return;
            }

            if ($status !== 'Completed' && (is_null($reason) || empty($reason))) {
                echo json_encode(['success' => false, 'message' => 'Reason is required for status other than Completed']);
                return;
            }

            // Update the transaction status
            $result = $this->TransactionModel->updateTransactionStatus($transactionServiceId, $status, $reason);
            echo json_encode(['success' => $result]);
        }
    }

    // Get Transaction by Code
    public function getTransactionByCode()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionCode = $_POST['transaction_code'];
            if (empty($transactionCode)) {
                echo json_encode(['success' => false, 'message' => 'Transaction code is required']);
                return;
            }

            $transaction = $this->TransactionModel->getTransactionByCode($transactionCode);
            if ($transaction) {
                echo json_encode(['success' => true, 'transaction' => $transaction]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Transaction not found']);
            }
        }
    }

    // Get Transaction by ID
    public function getTransactionById()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $transactionId = $_GET['transaction_id'];
            if (empty($transactionId)) {
                echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
                return;
            }

            $transaction = $this->TransactionModel->getTransactionById($transactionId);
            if ($transaction) {
                echo json_encode(['success' => true, 'transaction' => $transaction]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Transaction not found']);
            }
        }
    }

    // Get Transaction by User ID
    public function getTransactionsByUserId()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['userId'];
            if (empty($userId)) {
                echo json_encode(['success' => false, 'message' => 'User ID is required']);
                return;
            }

            $transactions = $this->TransactionModel->getTransactionsByUserId($userId);
            if ($transactions) {
                echo json_encode(['success' => true, 'transactions' => $transactions]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No transactions found for this user']);
            }
        }
    }

    // Rate Transaction
    public function rateTransaction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionCode = $_POST['transaction_code'];
            $rating = $_POST['rating'];

            if (empty($transactionCode) || empty($rating)) {
                echo json_encode(['success' => false, 'message' => 'Transaction Code and rating are required']);
                return;
            }

            // Rate the transaction
            $result = $this->TransactionModel->rateTransaction($transactionCode, $rating);
            echo json_encode(['success' => $result]);
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
    case 'updateServiceStatus':
        $controller->updateTransactionStatus();
        break;
    case 'getServices':
        $controller->getServices();
        break;
    case 'getTransactionByCode':
        $controller->getTransactionByCode();
        break;
    case 'getTransactionById':
        $controller->getTransactionById();
        break;
    case 'getTransactionsByUserId':
        $controller->getTransactionsByUserId();
        break;
    case 'rateTransaction':
        $controller->rateTransaction();
        break;
    default:
        echo json_encode(['error' => 'Invalid request']);
}
