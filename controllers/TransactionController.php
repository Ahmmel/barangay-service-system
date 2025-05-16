<?php
require_once '../config/database.php';
require_once '../models/Transaction.php';
require_once '../models/Queue.php';
require_once '../models/SMSNotification.php';
require_once '../models/Notification.php';
require_once '../models/ActivityLog.php';

class TransactionController
{
    private $transaction;
    private $queue;
    private $sms;
    private $notifier;
    private $logger;

    public function __construct($db)
    {
        $this->transaction = new Transaction($db);
        $this->queue = new Queue($db);
        $this->sms = new SMSNotification($db);
        $this->notifier = new Notification($db);
        $this->logger = new ActivityLog($db);
    }

    // Get Transaction Prequisites
    public function getServices()
    {
        $services = $this->transaction->getServices();
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
            $validator = $this->transaction->validateTransaction($userId, $serviceIds, $scheduledTime);
            if (!$validator['success']) {
                echo json_encode($validator);
                return;
            }

            // Generate a unique transaction code
            $transactionCode = 'Q-' . strtoupper(base_convert(time(), 10, 36)) . strtoupper(bin2hex(random_bytes(1)));

            // Add the queue entry
            $queueId = $this->queue->addQueue($userId, $transactionType, $transactionCode, $scheduledTime);

            if (!$queueId) {
                throw new Exception('Failed to create a queue entry');
            }

            // Create the transaction with services
            $result = $this->transaction->createTransactionWithServices($userId, $serviceIds, $transactionType, $transactionCode, $queueId, $scheduledTime);

            if (!$result['success']) {
                throw new Exception($result['message'] ?? 'Failed to create transaction with services');
            }

            $bookingType = "Walk-in";
            // Send SMS confirmation if phone number is available
            $userPhone = $this->transaction->getUserMobileNumber($userId);
            if ($userPhone) {
                if ($transactionType == 1) {
                    $this->sms->sendWalkInTransactionNotification($userPhone, $result['transaction_code'], $scheduledTime, $serviceIds);
                } else {
                    $bookingType = "Scheduled";
                    $this->sms->sendTransactionConfirmation($userPhone, $result['transaction_code'], $scheduledTime, $serviceIds);
                }
            }

            $transactionID = $this->transaction->getTransactionIdByCode($result['transaction_code']);
            // 3) Notify the user
            $this->notifier->createNotification(
                $userId,
                2,
                'booking_confirmed',
                $transactionID,
                "{$bookingType} Booking Confirmed",
                "Your booking (ID: {$result['transaction_code']}) for services [" . implode(', ', $serviceIds) . "] on {$scheduledTime} has been confirmed."
            );

            // 4) Log success
            $this->logger->logActivity(
                $userId,
                2,
                'transaction',
                "Created {$bookingType} transaction #{$result['transaction_code']} for user ID {$userId}",
                'Success',
                $transactionID,
                ['services' => $serviceIds, 'scheduled_at' => $scheduledTime]
            );

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
        $transactions = $this->transaction->getAllTransactions();
        echo json_encode($transactions);
    }

    // Update Transaction Status
    public function updateTransactionStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionServiceId = $_POST['transaction_service_id'];
            $status = $_POST['status'];
            $reason = $_POST['reason'] ?? null;
            $staffId = $_POST['staff_id'] ?? null;
            $actorRole = (int) $_POST['session_role_id'] ?? null;

            if (empty($transactionServiceId) || empty($status) || empty($staffId)) {
                echo json_encode(['success' => false, 'message' => 'Transaction ID, status, and staff ID are required']);
                return;
            }

            if ($status !== 'Completed' && (is_null($reason) || empty($reason))) {
                echo json_encode(['success' => false, 'message' => 'Reason is required for status other than Completed']);
                return;
            }

            // Update the transaction status
            $result = $this->transaction->updateTransactionStatus($transactionServiceId, $status, $staffId, $reason);
            if ($result) {
                $transactionCode = $this->transaction->getTransactionCodeById($transactionServiceId);
                $this->queue->setToAssignedTransaction($transactionCode, $staffId);
                // Notify the user
                $this->notifier->createNotification(
                    $staffId,
                    $actorRole,
                    'transaction_status_updated',
                    $transactionServiceId,
                    'Transaction Status Updated',
                    "Transaction (ID: {$transactionServiceId}) status has been updated to {$status}."
                );

                // Log the activity
                $this->logger->logActivity(
                    $staffId,
                    $actorRole,
                    'transaction',
                    "Updated transaction (ID: {$transactionServiceId}) status to {$status}",
                    'Success',
                    $transactionServiceId,
                    ['status' => $status, 'reason' => $reason]
                );
            }
            echo json_encode(['success' => $result]);
            return;
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

            $transaction = $this->transaction->getTransactionByCode($transactionCode);
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

            $transaction = $this->transaction->getTransactionById($transactionId);
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

            $transactions = $this->transaction->getTransactionsByUserId($userId);
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
            $result = $this->transaction->rateTransaction($transactionCode, $rating);

            if ($result) {
                // Notify the user
                $this->notifier->createNotification(
                    null,
                    2,
                    'transaction_rated',
                    null,
                    'Transaction Rated',
                    "Your transaction (ID: {$transactionCode}) has been rated with {$rating} stars."
                );

                // Log the activity
                $this->logger->logActivity(
                    null,
                    2,
                    'transaction',
                    "Rated transaction (ID: {$transactionCode}) with {$rating} stars",
                    'Success',
                    null,
                    ['rating' => $rating]
                );
            }
            echo json_encode(['success' => $result]);
        }
    }

    //checkBookingAvailability
    public function checkBookingAvailability()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $scheduledTime = $_POST['scheduled_time'];

            if (empty($scheduledTime)) {
                echo json_encode(['success' => false, 'message' => 'Service ID and scheduled time are required']);
                return;
            }

            // Check booking availability
            $isAvailable = $this->transaction->isServiceSlotTaken($scheduledTime);

            if ($isAvailable) {
                echo json_encode(['success' => true, 'message' => 'Booking is available']);
            } else {
                echo json_encode(['success' => false, 'message' => 'The selected time slot is already booked. Please choose another time.']);
            }
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
    case 'checkBookingAvailability':
        $controller->checkBookingAvailability();
        break;
    default:
        echo json_encode(['error' => 'Invalid request']);
}
