<?php
class ActivityLog
{
    private $conn;
    private $table_name = "activity_logs";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new activity log
    public function createLog($userId, $roleId, $activity, $status = 'Success', $referenceId = null)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, role_id, activity, status, reference_id, created_at)
                  VALUES (:user_id, :role_id, :activity, :status, :reference_id, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":role_id", $roleId);
        $stmt->bindParam(":activity", $activity);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":reference_id", $referenceId);

        return $stmt->execute();
    }

    // Get recent logs with user full name
    public function getRecentLogs($limit = 20)
    {
        $query = "
            SELECT 
                a.id,
                a.user_id,
                CONCAT(u.first_name, ' ', u.last_name) AS fullname,
                a.activity,
                a.status,
                a.created_at
            FROM " . $this->table_name . " a
            LEFT JOIN users u ON a.user_id = u.id
            ORDER BY a.created_at DESC
            LIMIT :limit
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Get recent logs by staff id
    public function getRecentLogsByStaffId($staffId, $limit = 20)
    {
        $query = "
            SELECT 
                a.id,
                a.user_id,
                CONCAT(u.first_name, ' ', u.last_name) AS fullname,
                a.activity,
                a.status,
                a.created_at
            FROM " . $this->table_name . " a
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.user_id = :staff_id
            ORDER BY a.created_at DESC
            LIMIT :limit
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":staff_id", $staffId);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
