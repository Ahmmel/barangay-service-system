<?php
require_once '../config/Database.php';

class Transaction
{
    private $conn;
    private $table_name = "transactions";  // Table name for transactions

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create a new transaction with associated services
    public function createTransactionWithServices($userId, $serviceIds, $queueId = null, $scheduledTime)
    {
        if (!$this->isValidBookingTime($scheduledTime)) {
            return ["success" => false, "message" => "Booking must be at least 30 minutes in advance."];
        }

        if (!$this->isWithinBookingHours($scheduledTime)) {
            return ["success" => false, "message" => "Bookings can only be made between 8:00 AM - 4:30 PM, Monday to Saturday."];
        }

        if ($this->hasUserMadeTransactionToday($userId)) {
            return ["success" => false, "message" => "You can only book one transaction per day."];
        }

        $maxTransactions = $this->getMaxTransactionsPerDay();
        if ($this->getTodaysTransactionCount() >= $maxTransactions) {
            return ["success" => false, "message" => "The maximum number of transactions for today has been reached."];
        }

        try {
            // Start the transaction
            $this->conn->beginTransaction();
            $transactionCode = "TRX-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -5));

            // Insert the transaction into the transactions table
            $query = "INSERT INTO " . $this->table_name . " (user_id, queue_id, status) VALUES (:user_id, :queue_id, 'In Progress')";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":queue_id", $queueId);
            $stmt->bindParam(":transactionCode", $transactionCode);

            $stmt->execute();

            // Get the last inserted transaction ID
            $transactionId = $this->conn->lastInsertId();

            // Insert selected services into the transaction_services table
            $query = "INSERT INTO transaction_services (transaction_id, service_id, status) VALUES (:transaction_id, :service_id, 'Pending')";
            $stmt = $this->conn->prepare($query);

            // Loop through all service IDs and associate them with the transaction
            foreach ($serviceIds as $serviceId) {
                $stmt->bindParam(":transaction_id", $transactionId);
                $stmt->bindParam(":service_id", $serviceId);
                $stmt->execute();
            }

            // Commit the transaction
            $this->conn->commit();

            // Return success response with the transaction ID
            return [
                'success' => true,
                'message' => 'Transaction created successfully!',
                'transaction_id' => $transactionId
            ];
        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Get a single transaction by its ID
    public function getTransactionById($id)
    {
        $query = "SELECT id, user_id, queue_id, status, created_at, updated_at
                  FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        // Fetch the transaction details
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all transactions for a user
    public function getTransactionsByUser($userId)
    {
        $query = "SELECT id, user_id, queue_id, status, created_at, updated_at
                  FROM " . $this->table_name . " WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        // Fetch all transactions
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all services for a specific transaction
    public function getServicesForTransaction($transactionId)
    {
        $query = "SELECT s.id, s.service_name, ts.status as service_status, ts.completed_at
                  FROM transaction_services ts
                  JOIN services s ON ts.service_id = s.id
                  WHERE ts.transaction_id = :transaction_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":transaction_id", $transactionId);
        $stmt->execute();

        // Return associated services
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Get all transactions by status
    public function getTransactionsByStatus($status)
    {
        $query = "SELECT s.id, s.service_name, ts.status as service_status, ts.completed_at
                  FROM transaction_services ts
                  JOIN services s ON ts.service_id = s.id
                  WHERE status = :status";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->execute();

        // Fetch all transactions
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update the status of a transaction
    public function updateTransactionStatus($transactionId, $status, $reason = null)
    {
        $query = "UPDATE transactions SET status = :status, updated_at = NOW() WHERE id = :transactionId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':status' => $status, ':transactionId' => $transactionId]);

        // Insert reason if status is Pending
        if ($status === "Pending" && $reason) {
            $reasonQuery = "INSERT INTO transaction_logs (transaction_id, status, reason) VALUES (:transactionId, :status, :reason)";
            $reasonStmt = $this->conn->prepare($reasonQuery);
            $reasonStmt->execute([':transactionId' => $transactionId, ':status' => $status, ':reason' => $reason]);
        }

        return $stmt->rowCount() > 0;
    }

    public function setNextTransaction()
    {
        // Get the next pending transaction in the queue
        $query = "SELECT * FROM transactions WHERE status = 'Pending' ORDER BY scheduled_time ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $nextTransaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($nextTransaction) {
            // Update transaction status to "In Progress"
            $updateQuery = "UPDATE transactions SET status = 'In Progress' WHERE id = :transactionId";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->execute([':transactionId' => $nextTransaction['id']]);

            return $nextTransaction;
        }

        return null;
    }

    public function getUserPhoneNumber($userId)
    {
        $query = "SELECT phone FROM users WHERE id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':userId' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['phone'] : null;
    }

    // Rule: Transactions must be booked at least 30 minutes from now
    public function isValidBookingTime($scheduledTime)
    {
        $currentTime = new DateTime();
        $bookingTime = new DateTime($scheduledTime);
        $difference = $currentTime->diff($bookingTime);

        return ($difference->i >= 30 || $difference->h > 0 || $difference->d > 0);
    }

    //Rule: Booking should be between 8:00 AM - 4:30 PM (Monday - Saturday)
    public function isWithinBookingHours($scheduledTime)
    {
        $bookingTime = new DateTime($scheduledTime);
        $dayOfWeek = $bookingTime->format('N'); // 1 = Monday, 7 = Sunday
        $hour = (int) $bookingTime->format('H');
        $minute = (int) $bookingTime->format('i');

        return ($dayOfWeek >= 1 && $dayOfWeek <= 6) && // Monday-Saturday
            ($hour >= 8 && ($hour < 16 || ($hour == 16 && $minute <= 30)));
    }

    //Rule: A user can only make one transaction per day
    public function hasUserMadeTransactionToday($userId)
    {
        $query = "SELECT COUNT(*) FROM transactions WHERE user_id = :userId AND DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    //Rule: Get the max allowed transactions per day from settings
    public function getMaxTransactionsPerDay()
    {
        $query = "SELECT value FROM settings WHERE name = 'max_transactions_per_day'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    //Rule: If the user is late by more than 25 minutes, cancel the transaction
    public function checkAndCancelLateTransactions()
    {
        $query = "UPDATE transactions 
                  SET status = 'Cancelled', cancel_reason = 'No Show'
                  WHERE status = 'In Progress' 
                  AND TIMESTAMPDIFF(MINUTE, scheduled_time, NOW()) > 25";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    //Rule: Get the count of today's transactions
    public function getTodaysTransactionCount()
    {
        $query = "SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
