<?php
class ActivityLog
{
    private PDO $conn;
    private string $table_name = 'activity_logs';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Create a new activity log (legacy signature)
     */
    public function createLog(
        int $userId,
        int $roleId,
        string $activity,
        string $status = 'Success',
        ?int $referenceId = null
    ): bool {
        $query = "
            INSERT INTO {$this->table_name}
                (user_id, role_id, activity, status, reference_id, created_at)
            VALUES
                (:user_id, :role_id, :activity, :status, :reference_id, NOW())
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id',     $userId,      PDO::PARAM_INT);
        $stmt->bindParam(':role_id',     $roleId,      PDO::PARAM_INT);
        $stmt->bindParam(':activity',    $activity,    PDO::PARAM_STR);
        $stmt->bindParam(':status',      $status,      PDO::PARAM_STR);
        $stmt->bindParam(':reference_id', $referenceId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Log an activity with extended fields
     */
    public function logActivity(
        ?int $userId,
        ?int $roleId,
        string $entityType,
        string $activity,
        string $status = 'Success',
        ?int $referenceId = null,
        ?array $meta = null
    ): bool {
        $query = "
            INSERT INTO {$this->table_name}
                (user_id, role_id, entity_type, activity, status, reference_id, meta)
            VALUES
                (:user_id, :role_id, :entity_type, :activity, :status, :reference_id, :meta)
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id',      $userId,      $userId      ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':role_id',      $roleId,      $roleId      ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':entity_type',  $entityType,  PDO::PARAM_STR);
        $stmt->bindValue(':activity',     $activity,    PDO::PARAM_STR);
        $stmt->bindValue(':status',       $status,      PDO::PARAM_STR);
        $stmt->bindValue(':reference_id', $referenceId, $referenceId ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':meta',         $meta ? json_encode($meta) : null, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Get recent logs (legacy)
     */
    public function getRecentLogs(int $limit = 20): array
    {
        $sql = "
        SELECT
            a.id,
            a.user_id,
            COALESCE(
                CONCAT(u.first_name, ' ', u.last_name),
                ad.name,
                'System'
            ) AS fullname,
            a.activity,
            a.status,
            a.created_at
        FROM {$this->table_name} a
        -- try to find an admin in the users table first
        LEFT JOIN users u
            ON a.user_id = u.id
           AND u.role_id = 1
        -- if not found there, fall back to the dedicated admins table
        LEFT JOIN admins ad
            ON a.user_id = ad.id
        ORDER BY a.created_at DESC
        LIMIT :limit
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get recent logs by staff ID (legacy)
     */
    public function getRecentLogsByStaffId(int $loggedId, bool $isStaff, int $limit = 20): array
    {
        $query = "
            SELECT
                a.id,
                a.user_id,
                CONCAT(u.first_name, ' ', u.last_name) AS fullname,
                a.activity,
                a.status,
                a.created_at
            FROM {$this->table_name} a
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.user_id = :staff_id
            AND u.role_id = 3
            ORDER BY a.created_at DESC
            LIMIT :limit
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':staff_id', $loggedId, PDO::PARAM_INT);
        $stmt->bindParam(':limit',    $limit,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get logs by entity type and reference ID
     */
    public function getLogsByEntity(string $entityType, int $referenceId): array
    {
        $query = "
            SELECT *
            FROM {$this->table_name}
            WHERE entity_type = :entity_type AND reference_id = :ref_id
            ORDER BY created_at DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':entity_type', $entityType, PDO::PARAM_STR);
        $stmt->bindParam(':ref_id',      $referenceId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
