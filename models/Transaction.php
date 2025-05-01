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
    public function createTransactionWithServices($userId, $serviceIds, $type, $transactionCode, $queueId, $scheduledTime)
    {
        try {
            // Start the transaction
            $this->conn->beginTransaction();
            // Insert the transaction into the transactions table
            $query = "INSERT INTO " . $this->table_name . " (transaction_code, user_id, queue_id, type, status, created_at) VALUES (:transactionCode, :user_id, :queue_id, :type, 'Open', :scheduledTime)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":transactionCode", $transactionCode);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":queue_id", $queueId);
            $stmt->bindParam(":type", $type);
            $stmt->bindParam(":scheduledTime", $scheduledTime);

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

    public function validateTransaction($userId, $serviceIds, $scheduledTime)
    {
        $maxTransactions = $this->getMaxTransactionsPerDay();
        if ($this->countUserTransactionByIdAndDate($userId, $scheduledTime) >= $maxTransactions) {
            return ["success" => false, "message" => "The maximum number of transactions for today has been reached."];
        }

        if (!$this->isWithinBookingHours($scheduledTime)) {
            return ["success" => false, "message" => "Bookings can only be made between 8:00 AM - 4:30 PM, Monday to Saturday."];
        }

        // check if the user has booked the same service within the scheduled time
        if ($this->hasUserBookedSameService($userId, $scheduledTime, $serviceIds)) {
            return ["success" => false, "message" => "You have already booked the same service for the selected schedule."];
        }

        if (!$this->isValidBookingTime($scheduledTime)) {
            return ["success" => false, "message" => "Booking must be at least 30 minutes in advance."];
        }

        return ["success" => true];
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

    //getAllStaffTransactions
    public function getAllStaffTransactions($staffId)
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
        WHERE t.handled_by_staff_id = :staffId
        GROUP BY t.id
        ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':staffId' => $staffId]);
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

    // Get transaction by user_id
    public function getTransactionsByUserId($userId)
    {
        $query = "
        SELECT 
            t.id AS transaction_id,
            t.transaction_code,
            t.queue_id,
            t.status,
            t.created_at,
            t.date_closed,
            t.rating,
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
        WHERE t.user_id = :userId
        GROUP BY t.id
        ORDER BY t.created_at DESC;
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update the status of a transaction
    public function updateTransactionStatus($id, $status, $staffId, $reason = null)
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
            $updateTransaction = "UPDATE " . $this->table_name . " SET status = 'Closed', updated_at = NOW(), handled_by_staff_id = :staffId,  date_closed = NOW() WHERE id = :transaction_id";
            $stmt = $this->conn->prepare($updateTransaction);
            $stmt->execute([
                ':transaction_id' => $transaction_id,
                ':staffId' => $staffId
            ]);
        } else {
            // If there are still pending records, update the transaction status to "In Progress"
            $updateTransaction = "UPDATE " . $this->table_name . " SET status = 'In Progress', updated_at = NOW(), handled_by_staff_id = :staffId WHERE id = :transaction_id";
            $stmt = $this->conn->prepare($updateTransaction);
            $stmt->execute([':transaction_id' => $transaction_id, ':staffId' => $staffId]);
        }

        // Commit the transaction
        $this->conn->commit();

        // Return whether any row was affected in the last update
        return $stmt->rowCount() > 0;
    }

    public function setCancelStatusByTransactionCode($transactionCode, $staffId)
    {
        try {
            // Begin transaction
            $this->conn->beginTransaction();

            // Step 1: Fetch transaction ID
            $stmt = $this->conn->prepare("
            SELECT id FROM {$this->table_name} 
            WHERE transaction_code = :transactionCode 
            LIMIT 1
        ");
            $stmt->execute([':transactionCode' => $transactionCode]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$transaction) {
                $this->conn->rollBack();
                return false;
            }

            $transactionId = $transaction['id'];

            // Step 2: Update transactions table
            $stmt = $this->conn->prepare("
            UPDATE {$this->table_name}
            SET status = 'Cancelled',
                updated_at = NOW(),
                handled_by_staff_id = :staffId,
                date_closed = NOW()
            WHERE id = :id
        ");
            $updateSuccess = $stmt->execute([
                ':id' => $transactionId,
                ':staffId' => $staffId
            ]);

            if (!$updateSuccess || $stmt->rowCount() === 0) {
                $this->conn->rollBack();
                return false;
            }

            // Step 3: Update transaction_services table
            $stmt = $this->conn->prepare("
            UPDATE transaction_services
            SET status = 'Cancelled',
                reason = 'No Show',
                completed_at = NOW()
            WHERE transaction_id = :transactionId
        ");
            $stmt->execute([':transactionId' => $transactionId]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
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

    //rateTransaction
    public function rateTransaction($transactionCode, $rating)
    {
        // Start a transaction to ensure atomicity
        $this->conn->beginTransaction();

        // Update the transaction with the rating and review
        $query = "UPDATE transactions SET rating = :rating WHERE transaction_code = :transactionCode";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':rating' => $rating, ':transactionCode' => $transactionCode]);

        // Commit the transaction
        $this->conn->commit();

        return true;
    }

    public function getUserMobileNumber($userId)
    {
        $query = "SELECT mobile_number FROM users WHERE id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':userId' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['mobile_number'] : null;
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

    // Rule: Check if the user has booked the same service(s) on the scheduled date
    public function hasUserBookedSameService($userId, $scheduledTime, array $serviceIds)
    {
        // Extract date part only
        $bookingDate = date('Y-m-d', strtotime($scheduledTime));

        // Build a list of service IDs for the query
        $placeholders = implode(',', array_fill(0, count($serviceIds), '?'));

        $query = "
        SELECT COUNT(*) 
        FROM transactions t
        JOIN transaction_services ts ON ts.transaction_id = t.id
        WHERE DATE(t.created_at) = ?
          AND t.user_id = ?
          AND ts.service_id IN ($placeholders)
    ";

        $stmt = $this->conn->prepare($query);

        // Bind the values dynamically
        $params = array_merge([$bookingDate, $userId], $serviceIds);

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0; // return true if user already booked
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
    public function getTodaysTransactionCount($bookingDateTime)
    {
        // Extract only the DATE part from the provided datetime
        $bookingDate = date('Y-m-d', strtotime($bookingDateTime));

        // SQL query to count today's transactions
        $query = "SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = :bookingDate";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":bookingDate", $bookingDate, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // Rule: Count user's transactions by booking date only (ignore the time part)
    public function countUserTransactionByIdAndDate($userId, $bookingDateTime)
    {
        // Extract only the DATE part from the provided datetime
        $bookingDate = date('Y-m-d', strtotime($bookingDateTime));

        $query = "
             SELECT COUNT(*) 
             FROM transactions 
             WHERE DATE(created_at) = :bookingDate
             AND user_id = :userId
         ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':userId' => $userId,
            ':bookingDate' => $bookingDate
        ]);

        return (int) $stmt->fetchColumn();
    }
}
