<?php
// - Add this : */30 * * * * php /path/to/cron_mark_no_show.php
require_once '../config/database.php';

$current_date = date('Y-m-d');
$current_time = date('H:i:s', strtotime('-25 minutes'));

$stmt = $pdo->prepare("SELECT * FROM queue WHERE status = 'Open' AND created_at <= ?");
$stmt->execute([$current_time]);
$no_show_transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($no_show_transactions as $queue) {
    $pdo->beginTransaction();

    // Update queue status to Cancelled
    $stmt = $pdo->prepare("UPDATE queue SET status = 'Cancelled', reason = 'No Show' WHERE id = ?");
    $stmt->execute([$queue['id']]);

    // Update transaction status
    $stmt = $pdo->prepare("UPDATE transactions SET status = 'Cancelled' WHERE id = ?");
    $stmt->execute([$queue['transaction_id']]);

    $pdo->commit();
}

echo "No-show transactions updated.";
