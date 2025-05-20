<?php
require_once __DIR__ . '/../models/SystemSettings.php';
class Queue
{
    private $conn;
    private $table_name = "queue";
    private $systemSettings;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->systemSettings = SystemSettings::getInstance($this->conn);
    }

    public function addQueue($user_id, $transaction_type, $transaction_code, $scheduled_date = null)
    {
        // Set the scheduled date to the current date and time if not provided
        if (!$scheduled_date) {
            $scheduled_date = date('Y-m-d H:i:s');
        }

        // SQL query to insert the record into the table
        $query = "INSERT INTO " . $this->table_name . " (transaction_code, user_id, type, status, scheduled_date, created_at) 
              VALUES (:transaction_code, :user_id, :type, 'Pending', :scheduled_date, NOW())";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":transaction_code", $transaction_code);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":type", $transaction_type);
        $stmt->bindParam(":scheduled_date", $scheduled_date);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // Return the last inserted ID if the query is successful
            return $this->conn->lastInsertId();
        } else {
            // Return false if execution failed
            return false;
        }
    }

    public function getTodayNextQueueCode()
    {
        $query = "SELECT transaction_code FROM " . $this->table_name . " 
                  WHERE status = 'Pending' 
                  AND DATE(created_at) = CURDATE()
                  ORDER BY created_at ASC 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['transaction_code'] : null;
    }

    public function nextQueue($queue_id, $staff_id)
    {
        $query = "UPDATE " . $this->table_name . " 
              SET status = 'Assigned', updated_by_staff_id = :staff_id 
              WHERE id = :queue_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":queue_id", $queue_id);
        $stmt->bindParam(":staff_id", $staff_id);
        return $stmt->execute();
    }

    // Get today queue by type
    public function getTodayPendingQueues($type)
    {
        $start = $this->systemSettings->get('booking_time_start', '08:00');
        $end = $this->systemSettings->get('booking_time_end', '16:30');
        $query = "
        SELECT 
            q.id,
            q.transaction_code,
            u.first_name,
            CONCAT(u.first_name, ' ', LEFT(u.last_name, 1), '.') AS display_name,
            q.type,
            q.status,
            q.scheduled_date,
            q.created_at
        FROM queue q
        INNER JOIN users u ON q.user_id = u.id
        WHERE q.type = :type
        AND q.status = 'Pending'
        AND q.scheduled_date BETWEEN 
            CONCAT(CURDATE(), ' ', :start_time)
            AND 
            CONCAT(CURDATE(), ' ', :end_time)
        ORDER BY q.scheduled_date ASC
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":start_time", $start);
        $stmt->bindParam(":end_time", $end);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setToAssignedTransaction($transactionCode, $staffId)
    {
        $query = "UPDATE " . $this->table_name . " SET status = 'Assigned', updated_by_staff_id = :staffId WHERE transaction_code = :transactionCode";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":transactionCode", $transactionCode);
        $stmt->bindParam(":staffId", $staffId);
        return $stmt->execute();
    }
}
