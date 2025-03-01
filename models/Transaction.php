<?php
require_once '../config/Database.php';

class Transaction
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createTransaction($userId, $scheduledTime, $services)
    {
        try {
            // Generate unique transaction code (Format: TRX-YYYYMMDD-RANDOMSTRING)
            $transactionCode = "TRX-" . date("Ymd") . "-" . strtoupper(substr(md5(uniqid()), 0, 5));

            // Insert transaction
            $query = "INSERT INTO transactions (user_id, transaction_code, scheduled_time, status) 
                      VALUES (:userId, :transactionCode, :scheduledTime, 'In Progress')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':userId' => $userId,
                ':transactionCode' => $transactionCode,
                ':scheduledTime' => $scheduledTime
            ]);

            // Get last inserted transaction ID
            $transactionId = $this->conn->lastInsertId();

            // Insert services linked to the transaction
            foreach ($services as $serviceId) {
                $serviceQuery = "INSERT INTO transaction_services (transaction_id, service_id) VALUES (:transactionId, :serviceId)";
                $serviceStmt = $this->conn->prepare($serviceQuery);
                $serviceStmt->execute([
                    ':transactionId' => $transactionId,
                    ':serviceId' => $serviceId
                ]);
            }

            return ['success' => true, 'transaction_code' => $transactionCode];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateTransactionStatus($transactionId, $status, $reason = null)
    {
        $query = "UPDATE transactions SET status = :status, updated_at = NOW() WHERE id = :transactionId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':status' => $status, ':transactionId' => $transactionId]);

        // Insert reason if status is Pending
        if ($status === "Pending" && $reason) {
            $reasonQuery = "INSERT INTO transaction_logs (transaction_id, status, reason) VALUES (:transactionId, :status, :reason)";
            $reasonStmt = $this->conn->prepare($reasonQuery);
            $reasonStmt->execute([':transactionId' => $transactionId, ':status' => $status, ':reason' => $reason]);
        }

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
}
