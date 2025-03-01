<!-- Transaction Management Interface -->
<div class="container mt-4">
    <h2 class="text-center">Transaction Management</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Current Transaction</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="transactionTableBody">
                    <!-- Transaction data will be dynamically populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transaction Update Modal -->
    <div class="modal fade" id="updateTransactionModal" tabindex="-1" aria-labelledby="updateTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="updateTransactionModalLabel">Update Transaction Status</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="transactionId">
                    <label for="transactionStatus">Status:</label>
                    <select class="form-control" id="transactionStatus">
                        <option value="">Select Status</option>
                        <option value="Closed">Closed</option>
                        <option value="Pending">Pending</option>
                    </select>
                    <div id="pendingReasonContainer" style="display: none;" class="mt-2">
                        <label for="pendingReason">Reason:</label>
                        <textarea class="form-control" id="pendingReason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="setTransactionStatusBtn" class="btn btn-warning">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <button id="nextTransactionBtn" class="btn btn-primary mt-3" disabled>Next Transaction</button>
</div>

<script>
    function updateNextTransactionButton() {
        let currentTransactionStatus = $("#transactionStatus").val();
        let pendingReason = $("#pendingReason").val();
        let nextTransactionBtn = $("#nextTransactionBtn");

        if (currentTransactionStatus === "Closed" || (currentTransactionStatus === "Pending" && pendingReason.trim() !== "")) {
            nextTransactionBtn.prop("disabled", false);
        } else {
            nextTransactionBtn.prop("disabled", true);
        }
    }

    $(document).ready(function() {
        $("#transactionStatus").on("change", function() {
            if ($(this).val() === "Pending") {
                $("#pendingReasonContainer").show();
            } else {
                $("#pendingReasonContainer").hide();
            }
            updateNextTransactionButton();
        });

        $("#pendingReason").on("keyup", function() {
            updateNextTransactionButton();
        });

        $("#setTransactionStatusBtn").click(function() {
            let transactionId = $("#transactionId").val();
            let status = $("#transactionStatus").val();
            let reason = $("#pendingReason").val();

            if (status === "Pending" && reason.trim() === "") {
                alert("Please provide a reason for setting the transaction to pending.");
                return;
            }

            $.ajax({
                url: "../controllers/TransactionController.php?action=updateStatus",
                type: "POST",
                data: {
                    transactionId: transactionId,
                    status: status,
                    reason: reason
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        alert("Transaction status updated successfully.");
                        location.reload();
                    } else {
                        alert("Error updating transaction status.");
                    }
                },
                error: function() {
                    alert("Failed to update transaction status.");
                }
            });
        });

        $("#nextTransactionBtn").click(function() {
            $.ajax({
                url: "../controllers/TransactionController.php?action=setNextTransaction",
                type: "POST",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        alert("Moved to next transaction successfully.");
                        location.reload();
                    } else {
                        alert("Error moving to next transaction.");
                    }
                },
                error: function() {
                    alert("Failed to process next transaction.");
                }
            });
        });
    });
</script>

<?php
require_once '../models/TransactionModel.php';
require_once '../models/SMSNotification.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_GET['action'] === 'updateStatus') {
        $transactionId = $_POST['transactionId'];
        $status = $_POST['status'];
        $reason = $_POST['reason'] ?? null;

        $transactionModel = new TransactionModel();
        $updated = $transactionModel->updateTransactionStatus($transactionId, $status, $reason);
        echo json_encode(["success" => $updated]);
    }

    if ($_GET['action'] === 'setNextTransaction') {
        $transactionModel = new TransactionModel();
        $nextTransaction = $transactionModel->setNextTransaction();

        if ($nextTransaction) {
            $sms = new SMSNotification();
            $sms->sendNotification($nextTransaction['user_phone'], "You are next in queue. Please arrive on time.");
        }

        echo json_encode(["success" => (bool)$nextTransaction]);
    }
}
?>


<?php
require_once '../config/Database.php';

class TransactionModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 🟢 Rule: Transactions must be booked at least 30 minutes from now
    public function isValidBookingTime($scheduledTime)
    {
        $currentTime = new DateTime();
        $bookingTime = new DateTime($scheduledTime);
        $difference = $currentTime->diff($bookingTime);

        return ($difference->i >= 30 || $difference->h > 0 || $difference->d > 0);
    }

    // 🟢 Rule: Booking should be between 8:00 AM - 4:30 PM (Monday - Saturday)
    public function isWithinBookingHours($scheduledTime)
    {
        $bookingTime = new DateTime($scheduledTime);
        $dayOfWeek = $bookingTime->format('N'); // 1 = Monday, 7 = Sunday
        $hour = (int) $bookingTime->format('H');
        $minute = (int) $bookingTime->format('i');

        return ($dayOfWeek >= 1 && $dayOfWeek <= 6) && // Monday-Saturday
            ($hour >= 8 && ($hour < 16 || ($hour == 16 && $minute <= 30)));
    }

    // 🟢 Rule: A user can only make one transaction per day
    public function hasUserMadeTransactionToday($userId)
    {
        $query = "SELECT COUNT(*) FROM transactions WHERE user_id = :userId AND DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // 🟢 Rule: Get the max allowed transactions per day from settings
    public function getMaxTransactionsPerDay()
    {
        $query = "SELECT value FROM settings WHERE name = 'max_transactions_per_day'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // 🟢 Rule: If the user is late by more than 25 minutes, cancel the transaction
    public function checkAndCancelLateTransactions()
    {
        $query = "UPDATE transactions 
                  SET status = 'Cancelled', cancel_reason = 'No Show'
                  WHERE status = 'In Progress' 
                  AND TIMESTAMPDIFF(MINUTE, scheduled_time, NOW()) > 25";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // 🟢 Rule: Get the count of today's transactions
    public function getTodaysTransactionCount()
    {
        $query = "SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ✅ Ensure a transaction follows all rules before booking
    public function createTransaction($userId, $scheduledTime, $services) {
        if (!$this->isValidBookingTime($scheduledTime)) {
            return ["success" => false, "message" => "Booking must be at least 30 minutes in advance."];
        }
    
        if (!$this->isWithinBookingHours($scheduledTime)) {
            return ["success" => false, "message" => "Bookings can only be made between 8:00 AM - 4:30 PM, Monday to Saturday."];
        }
    
        if ($this->hasUserMadeTransactionToday($userId)) {
            return ["success" => false, "message" => "You can only book one transaction per day."];
        }
    
        $maxTransactions = $this->getMaxTransactionsPerDay();
        if ($this->getTodaysTransactionCount() >= $maxTransactions) {
            return ["success" => false, "message" => "The maximum number of transactions for today has been reached."];
        }
    
        // 🛑 Generate a unique transaction code (Format: TRX-YYYYMMDD-RANDOM)
        $transactionCode = "TRX-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -5));
    
        // Insert transaction into database
        $query = "INSERT INTO transactions (user_id, scheduled_time, status, transaction_code, created_at) 
                  VALUES (:userId, :scheduledTime, 'On Queue', :transactionCode, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":scheduledTime", $scheduledTime);
        $stmt->bindParam(":transactionCode", $transactionCode);
    
        if ($stmt->execute()) {
            return ["success" => true, "message" => "Transaction booked successfully.", "transaction_code" => $transactionCode];
        }
    
        return ["success" => false, "message" => "Failed to create transaction."];
    }
    
}
?>


<?php
require_once '../config/SemaphoreAPI.php'; // Assuming you have a Semaphore SMS API handler

class SMSNotification
{
    private $api;

    public function __construct()
    {
        $this->api = new SemaphoreAPI();
    }

    public function sendNotification($phoneNumber, $message)
    {
        return $this->api->sendSMS($phoneNumber, $message);
    }

    public function sendTransactionConfirmation($phoneNumber, $transactionCode, $scheduledTime) {
        $message = "Your transaction has been successfully booked! \n\n"
                 . "Transaction Code: $transactionCode\n"
                 . "Scheduled Time: $scheduledTime\n"
                 . "Please arrive at least 10 minutes before your scheduled time. Thank you!";
        
        return $this->sendSMS($phoneNumber, $message);
    }
    
}
?>

<?php
class SemaphoreAPI
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = "YOUR_SEMAPHORE_API_KEY"; // Replace with your actual API key
    }

    public function sendSMS($phoneNumber, $message)
    {
        $url = "https://semaphore.co/api/v4/messages";
        $data = [
            'apikey' => $this->apiKey,
            'number' => $phoneNumber,
            'message' => $message,
            'sendername' => "BarangayQueue" // Customize sender name if needed
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if necessary

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
?>

<?php
require_once '../models/TransactionModel.php';

$transactionModel = new TransactionModel();
$transactionModel->checkAndCancelLateTransactions();

echo "Checked and updated late transactions.\n";
?>

// Cron job to check and cancel late transactions
// Add this to your crontab configuration (crontab -e)
*/30 8-16 * * 1-6 php /path-to-project/cron/checkLateTransactions.php



$.ajax({
    url: "../controllers/TransactionController.php?action=createTransaction",
    type: "POST",
    data: { userId: userId, scheduledTime: scheduledTime, services: selectedServices },
    dataType: "json",
    success: function(response) {
        if (response.success) {
            alert("Transaction booked successfully! Your transaction code: " + response.transaction_code);
        } else {
            alert(response.message);
        }
    },
    error: function() {
        alert("Failed to book transaction.");
    }
});
