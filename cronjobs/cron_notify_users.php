<?php
// - Add this : */30 * * * * php /path/to/cron_notify_users.php
require_once '../config/database.php';
require_once '../models/SMSNotification.php';

$sms = new SMSNotification();

$current_date = date('Y-m-d');
$current_time = date('H:i:s', strtotime('+30 minutes'));

$stmt = $pdo->prepare("SELECT t.*, u.mobile_number FROM transactions t
JOIN users u ON t.user_id = u.id
WHERE scheduled_date = ? AND scheduled_time = ? AND status = 'Scheduled'");
$stmt->execute([$current_date, $current_time]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($transactions as $transaction) {
    $sms->sendReminder($transaction['mobile_number'], $transaction['scheduled_time']);
}

echo "User notifications sent.";
