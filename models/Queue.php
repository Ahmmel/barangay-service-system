<?php
class Queue
{
    private $conn;
    private $table_name = "queue";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addQueue($user_id, $transaction_type, $transaction_code, $scheduled_date = null)
    {
        // Set the scheduled date to the current date and time if not provided
        if (!$scheduled_date) {
            $scheduled_date = date('Y-m-d H:i:s');
        }

        // SQL query to insert the record into the table
        $query = "INSERT INTO " . $this->table_name . " (transaction_code, user_id, type, status, scheduled_date, created_at) 
              VALUES (:transaction_code, :user_id, :type, 'open', :scheduled_date, NOW())";

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


    public function getCurrentQueue()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 'pending' ORDER BY created_at ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function nextQueue($queue_id)
    {
        $query = "UPDATE " . $this->table_name . " SET status = 'completed' WHERE queue_id = :queue_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":queue_id", $queue_id);
        return $stmt->execute();
    }
}
