<?php
class Notification
{
    private $conn;
    private $table_name = "notifications";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createNotification($userId, $roleId, $title, $message)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, role_id, title, message, is_read, created_at)
                  VALUES (:user_id, :role_id, :title, :message, 0, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":role_id", $roleId);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":message", $message);

        return $stmt->execute();
    }

    public function getUnreadNotifications($userId, $roleId)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE user_id = :user_id AND role_id = :role_id AND is_read = 0
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":role_id", $roleId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($notificationId)
    {
        $query = "UPDATE " . $this->table_name . " SET is_read = 1 WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $notificationId);

        return $stmt->execute();
    }
}
