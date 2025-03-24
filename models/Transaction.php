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

    // Get all services and queues for transaction prerequisites
    public function getServices()
    {
        $query = "SELECT id, service_name FROM services";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch all services
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $services;
    }

    // Create a new transaction with associated services
    public function createTransactionWithServices($userId, $serviceIds, $transactionCode, $scheduledTime)
    {
        $maxTransactions = $this->getMaxTransactionsPerDay();
        if ($this->getTodaysTransactionCount() >= $maxTransactions) {
            return ["success" => false, "message" => "The maximum number of transactions for today has been reached."];
        }

        if ($this->hasUserMadeTransactionToday($userId)) {
            return ["success" => false, "message" => "You can only book one transaction per day."];
        }

        if (!$this->isWithinBookingHours($scheduledTime)) {
            return ["success" => false, "message" => "Bookings can only be made between 8:00 AM - 4:30 PM, Monday to Saturday."];
        }

        if (!$this->isValidBookingTime($scheduledTime)) {
            return ["success" => false, "message" => "Booking must be at least 30 minutes in advance."];
        }

        try {
            // Start the transaction
            $this->conn->beginTransaction();
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
                'transaction_code' => $transactionCode
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

    // Get all transactions with user details and services
    public function getAllTransactions()
    {
        $query = "
        SELECT 
            t.id AS transaction_id,
            t.transaction_code,
            t.queue_id,
            t.status,
            t.created_at,
            t.date_closed ,
            t.updated_at,
            u.first_name,
            u.middle_name,
            u.last_name,
            u.suffix,
            u.mobile_number,
            GROUP_CONCAT(s.service_name ORDER BY s.service_name ASC SEPARATOR ', ') AS services
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        LEFT JOIN transaction_services ts ON t.id = ts.transaction_id
        LEFT JOIN services s ON ts.service_id = s.id
        GROUP BY t.id
        ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get transaction by transaction_code
    public function getTransactionByCode($transactionCode)
    {
        $query = "
        SELECT 
            t.id AS transaction_id,
            t.transaction_code,
            t.queue_id,
            t.status,
            t.created_at,
            t.date_closed ,
            t.updated_at,
            u.first_name,
            u.middle_name,
            u.last_name,
            u.suffix,
            u.mobile_number,
            GROUP_CONCAT(s.service_name ORDER BY s.service_name ASC SEPARATOR ', ') AS services
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        LEFT JOIN transaction_services ts ON t.id = ts.transaction_id
        LEFT JOIN services s ON ts.service_id = s.id
        WHERE t.transaction_code = :transactionCode
        GROUP BY t.id
        ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':transactionCode' => $transactionCode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get transaction by transaction_id
    public function getTransactionById($transactionId)
    {
        $query = "
                    SELECT 
                        t.id AS transaction_id,
                        t.transaction_code,
                        t.queue_id,
                        t.status,
                        t.created_at,
                        t.date_closed,
                        t.updated_at,
                        u.first_name,
                        u.middle_name,
                        u.last_name,
                        u.suffix,
                        GROUP_CONCAT(
                            CONCAT(
                                '{\"id\":', ts.id, 
                                ',\"name\":\"', s.service_name, 
                                '\",\"status\":\"', ts.status, '\"}'
                            )
                            ORDER BY s.service_name ASC SEPARATOR ', '
                        ) AS services
                    FROM transactions t
                    JOIN users u ON t.user_id = u.id
                    LEFT JOIN transaction_services ts ON t.id = ts.transaction_id
                    LEFT JOIN services s ON ts.service_id = s.id
                    WHERE t.id = :transactionId
                    GROUP BY t.id
                    ORDER BY t.created_at DESC;
                ";


        $stmt = $this->conn->prepare($query);
        $stmt->execute([':transactionId' => $transactionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update the status of a transaction
    public function updateTransactionStatus($id, $status, $reason = null)
    {
        // Start a transaction to ensure atomicity
        $this->conn->beginTransaction();

        // First, update the transaction_services table with the new status
        $updateQuery = "UPDATE transaction_services SET status = :status, reason = :reason, completed_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($updateQuery);

        // Execute the update query
        $stmt->execute([':status' => $status, ':reason' => $reason, ':id' => $id]);

        // If the transaction_services table wasn't updated, rollback and return false
        if ($stmt->rowCount() === 0) {
            $this->conn->rollBack();
            return false;  // No rows affected, so we return false early
        }

        // Fetch the transaction_id for the given transaction service ID
        $query = "SELECT transaction_id FROM transaction_services WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);

        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            // If no transaction is found, rollback and return false
            $this->conn->rollBack();
            return false;
        }

        $transaction_id = $transaction['transaction_id'];

        // Check if there are any other records with the same transaction_id and pending status
        $query = "SELECT COUNT(*) FROM transaction_services WHERE transaction_id = :transaction_id AND status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':transaction_id' => $transaction_id]);

        $pendingCount = $stmt->fetchColumn();

        // If no pending records exist, proceed to update the other table
        if ($pendingCount == 0) {
            // Example query to update the other table (replace with actual query)
            $updateTransaction = "UPDATE " . $this->table_name . " SET status = 'Closed', updated_at = NOW(),  date_closed = NOW() WHERE id = :transaction_id";
            $stmt = $this->conn->prepare($updateTransaction);
            $stmt->execute([':transaction_id' => $transaction_id]);
        }

        // Commit the transaction
        $this->conn->commit();

        // Return whether any row was affected in the last update
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
