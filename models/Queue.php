<?php
class Queue {
    private $conn;
    private $table_name = "queue";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addQueue($user_id, $service) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, service, status, created_at) VALUES (:user_id, :service, 'pending', NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":service", $service);
        return $stmt->execute();
    }

    public function getCurrentQueue() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 'pending' ORDER BY created_at ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function nextQueue($queue_id) {
        $query = "UPDATE " . $this->table_name . " SET status = 'completed' WHERE queue_id = :queue_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":queue_id", $queue_id);
        return $stmt->execute();
    }
}
?>