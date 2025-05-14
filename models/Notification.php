<?php
class Notification
{
    private PDO $conn;
    private string $table_name = 'notifications';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Create a new notification
     *
     * @param int|null $userId
     * @param int|null $roleId
     * @param string   $type
     * @param int|null $referenceId
     * @param string   $title
     * @param string   $message
     * @return bool
     */
    public function createNotification(
        ?int $userId,
        ?int $roleId,
        string $type,
        ?int $referenceId,
        string $title,
        string $message
    ): bool {
        $query = "
            INSERT INTO {$this->table_name}
                (user_id, role_id, type, reference_id, title, message)
            VALUES
                (:user_id, :role_id, :type, :reference_id, :title, :message)
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id',     $userId,     $userId     ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':role_id',     $roleId,     $roleId     ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':type',        $type,       PDO::PARAM_STR);
        $stmt->bindValue(':reference_id', $referenceId, $referenceId ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':title',       $title,      PDO::PARAM_STR);
        $stmt->bindValue(':message',     $message,    PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Get all notifications for a user
     *
     * @param int  $userId
     * @param bool $onlyUnread
     * @return array
     */
    public function getNotificationsByUser(int $userId, bool $onlyUnread = false): array
    {
        $query = "SELECT * FROM {$this->table_name} WHERE user_id = :user_id";
        if ($onlyUnread) {
            $query .= " AND is_read = 0";
        }
        $query .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get only unread notifications for a user
     *
     * @param int $userId
     * @return array
     */
    public function getUnreadNotifications(int $userId): array
    {
        return $this->getNotificationsByUser($userId, true);
    }

    /**
     * Get unread notification count for a user
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount(int $userId): int
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Mark a single notification as read
     *
     * @param int $notificationId
     * @return bool
     */
    public function markAsReadNotification(int $notificationId): bool
    {
        $query = "UPDATE {$this->table_name} SET is_read = 1, read_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $notificationId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Mark all notifications as read for a user
     *
     * @param int $userId
     * @return bool
     */
    public function markAllAsRead(int $userId): bool
    {
        $query = "UPDATE {$this->table_name} SET is_read = 1, read_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
