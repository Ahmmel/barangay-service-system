<?php
// - Add this : */30 * * * * php /path/to/cron_move_transactions.php
require_once '../config/database.php';

$current_date = date('Y-m-d');
$current_time = date('H:i:s');

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE scheduled_date = ? AND scheduled_time <= ? AND status='Scheduled'");
$stmt->execute([$current_date, $current_time]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($transactions as $transaction) {
    $pdo->beginTransaction();

    // Insert into queue
    $stmt = $pdo->prepare(" INSERT INTO queue (transaction_id, user_id) VALUES (?, ?)");
    $stmt->execute([$transaction['id'], $transaction['user_id']]);
    $queue_id = $pdo->lastInsertId();

    // Update transaction to link queue entry
    $stmt = $pdo->prepare("UPDATE transactions SET queue_id = ?, status = 'In Progress' WHERE id = ?");
    $stmt->execute([$queue_id, $transaction['id']]);

    $pdo->commit();
}

echo "Queue processing complete.";
