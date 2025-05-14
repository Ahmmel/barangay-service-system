<?php
require_once '../config/Database.php';
require_once '../models/SystemSettings.php';

class Transaction
{
    private $conn;
    private $table_name = "transactions";  // Table name for transactions
    private $systemSettings;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->systemSettings = SystemSettings::getInstance($this->conn);
    }

    function isStaffAllowedToUpdate(): bool
    {
        // Get cutoff time from settings (default to 17:00 if not set)
        $cutoff = $this->systemSettings->get('staff_update_cutoff_time', '17:00');
        $staffStartTime = $this->systemSettings->get('staff_update_start_time', '07:00'); // Assuming the correct setting name is `staff_update_start_time`

        // Get current time in 'Asia/Manila' timezone
        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $currentTime = $now->format('H:i');

        // Staff can only update if current time is >= staff start time and < cutoff time
        return $currentTime >= $staffStartTime && $currentTime < $cutoff;
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

    public function validateTransaction($userId, array $serviceIds, string $scheduledTime)
    {
        // 1. Parse scheduledTime and ensure it’s a valid DateTime
        try {
            $dt = new DateTime($scheduledTime);
        } catch (Exception $e) {
            return [
                "success"    => false,
                "message"    => "Invalid date/time format.",
                "error_type" => "schedule"
            ];
        }

        $now = new DateTime();

        // 2. Cannot book in the past
        if ($dt < $now) {
            return [
                "success"    => false,
                "message"    => "Scheduled time must be in the future.",
                "error_type" => "schedule"
            ];
        }

        // 3. Max transactions per day
        $maxTx = (int) $this->systemSettings->get('max_transactions_per_day', 1);
        if ($this->countUserTransactionByIdAndDate($userId, $scheduledTime) >= $maxTx) {
            return [
                "success"    => false,
                "message"    => "You have reached your daily limit of {$maxTx} transaction(s).",
                "error_type" => "service"
            ];
        }

        // 4. Has existing transaction on the scheduled day
        if ($this->hasExistingTransaction($userId, $scheduledTime)) {
            return [
                "success"    => false,
                "message"    => "You already have a transaction scheduled at this day.",
                "error_type" => "schedule"
            ];
        }

        // 6.  Prevent double-booking the same service slot if it’s still pending/in-progress
        if ($this->isServiceSlotTaken($scheduledTime)) {
            return [
                "success"    => false,
                "message"    => "The service is already booked and still pending at that time.",
                "error_type" => "service"
            ];
        }

        // 7. Business hours & days (8:00–16:30, Mon–Sat)
        if (!$this->isValidBookingSchedule($scheduledTime)) {
            return [
                "success"    => false,
                "message"    => "Bookings may only be made 8:00 AM–4:30 PM, Monday–Saturday.",
                "error_type" => "schedule"
            ];
        }

        // 8. Lead time (at least 30 min before)
        if (!$this->isValidBookingTime($scheduledTime)) {
            return [
                "success"    => false,
                "message"    => "Bookings must be made at least 30 minutes in advance.",
                "error_type" => "schedule"
            ];
        }

        // 9. No duplicate service at same slot
        if ($this->hasUserBookedSameService($userId, $scheduledTime, $serviceIds)) {
            return [
                "success"    => false,
                "message"    => "You already have a booking for this service on the chosen date. Please select a different time or service.",
                "error_type" => "service"
            ];
        }

        // 10. Check for any pending services and collect their names
        $pending = $this->getPendingBookedServices($userId, $serviceIds);
        if (!empty($pending)) {
            // Build natural-language and array versions
            $last    = array_pop($pending);
            $nlList  = $pending
                ? implode(', ', $pending) . " and {$last}"
                : $last;
            $allItems = array_merge($pending, [$last]);

            // Build HTML message using <p> and <ul>
            $messageHtml  = '<p>You already have pending transactions for the following service(s):</p>'
                . '<ul style="text-align:left; margin:0 0 .5em 1em;">'
                . '<li>' . implode('</li><li>', $allItems) . '</li>'
                . '</ul>'
                . '<p>Please remove ' . $nlList . ' before booking again.</p>';

            return [
                "success"    => false,
                "message"    => $messageHtml,
                "error_type" => "service"
            ];
        }

        return ["success" => true];
    }

    function getServiceName($serviceId)
    {
        $query = "SELECT service_name FROM services WHERE id = :serviceId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':serviceId', $serviceId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['service_name'] : null;
    }

    protected function hasExistingTransaction($userId, $scheduledTime)
    {
        $sql = "
        SELECT COUNT(*) AS cnt
        FROM transactions t
        WHERE t.user_id = :user_id
          AND DATE(t.created_at) = DATE(:scheduled_date)
          AND t.status NOT IN ('Closed', 'Cancelled')
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':scheduled_date', $scheduledTime, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ((int)$row['cnt']) > 0;
    }

    /**
     * Returns true if there’s any Pending/Assigned queue entry
     * at $scheduledTime for $serviceId.
     */
    protected function isServiceSlotTaken(string $scheduledTime): bool
    {
        $sql = "
        SELECT COUNT(*) AS cnt
        FROM queue q
        JOIN transactions t
          ON t.transaction_code = q.transaction_code
        WHERE q.scheduled_date  = :scheduled_date
          AND t.status IN ('Pending', 'In Progress')
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':scheduled_date', $scheduledTime, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ((int)$row['cnt']) > 0;
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
        // Get minimum lead time from system settings (fallback to 30 if not set)
        $minLeadTime = (int) $this->systemSettings->get('minimum_booking_lead_time_minutes', 30);

        // Parse scheduled time and current time
        $scheduled = new DateTime($scheduledTime);
        $now = new DateTime();

        // Add lead time to current time
        $minAllowedTime = $now->modify("+$minLeadTime minutes");

        // Return true only if scheduled time is after the required lead time
        return $scheduled >= $minAllowedTime;
    }

    //Rule: Booking should be between 8:00 AM - 4:30 PM (Monday - Saturday)
    public function isValidBookingSchedule(string $scheduledTime): bool
    {
        $start = $this->systemSettings->get('booking_time_start', '08:00');
        $end = $this->systemSettings->get('booking_time_end', '16:30');

        $scheduled = new DateTime($scheduledTime);

        // 0 = Sunday, 6 = Saturday — valid if day is 1 (Mon) to 6 (Sat)
        $dayOfWeek = (int) $scheduled->format('w');
        if ($dayOfWeek === 0) {
            return false; // Sunday — not allowed
        }

        $timeOnly = $scheduled->format('H:i');
        return $timeOnly >= $start && $timeOnly <= $end;
    }

    // Rule: Check if the user has booked the same service(s) on the scheduled date
    public function hasUserBookedSameService($userId, $scheduledTime, array $serviceIds)
    {
        // 1. Get the calendar date of the requested slot
        try {
            $date = (new DateTime($scheduledTime))->format('Y-m-d');
        } catch (Exception $e) {
            // If the scheduledTime is invalid, treat as “no conflict”
            return false;
        }

        if (empty($serviceIds)) {
            return false;
        }

        // 2. Build the IN-list placeholders
        $placeholders = implode(',', array_fill(0, count($serviceIds), '?'));

        // 3. Query any non-closed booking on that date for those services
        $sql = "
        SELECT COUNT(*) 
          FROM transactions t
          JOIN transaction_services ts 
            ON ts.transaction_id = t.id
         WHERE DATE(t.created_at) = ?
           AND t.user_id           = ?
           AND t.status  NOT IN ('Closed','Cancelled')
           AND ts.service_id IN ($placeholders)
    ";

        $stmt = $this->conn->prepare($sql);

        // 4. Bind: first the date, then userId, then each service ID
        $params = array_merge([$date, $userId], $serviceIds);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }


    // Rule: Check if the user has a pending transaction with the same service(s)
    /**
     * Returns an array of service names for which the user
     * has a pending or assigned transaction on the same day.
     */
    protected function getPendingBookedServices(int $userId, array $serviceIds)
    {
        if (empty($serviceIds)) {
            return [];
        }

        // build placeholders for IN clause
        $ph = implode(',', array_fill(0, count($serviceIds), '?'));

        // note: adjust the date comparison if you want to filter by scheduled_time date
        $sql = "
        SELECT DISTINCT ts.service_id
          FROM transactions t
          JOIN transaction_services ts
            ON ts.transaction_id = t.id
         WHERE t.user_id     = ?
           AND t.status     NOT IN ('Closed','Cancelled')
           AND ts.service_id IN ($ph)
    ";

        $stmt = $this->conn->prepare($sql);

        // first param is userId, then each serviceId
        $params = array_merge([$userId], $serviceIds);
        $stmt->execute($params);

        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!$ids) {
            return [];
        }

        // now fetch their names in one query
        $ph2    = implode(',', array_fill(0, count($ids), '?'));
        $sql2   = "SELECT service_name FROM services WHERE id IN ($ph2)";
        $stmt2  = $this->conn->prepare($sql2);
        $stmt2->execute($ids);
        return $stmt2->fetchAll(PDO::FETCH_COLUMN);
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

    public function getTransactionCodeById($transactionId)
    {
        $query = "SELECT transaction_code FROM transactions WHERE id = :transactionId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':transactionId' => $transactionId]);
        return $stmt->fetchColumn();
    }
}
