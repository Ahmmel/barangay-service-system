<?php
// - Add this : 0 0 * * * php /path/to/cron_cleanup.php
require_once '../config/database.php';

// Delete queue entries older than 7 days
$stmt = $pdo->prepare("DELETE FROM queue WHERE status IN ('Closed', 'Cancelled') AND created_at < NOW() - INTERVAL 7 DAY");
$stmt->execute();

// Archive transactions older than 30 days
$stmt = $pdo->prepare("DELETE FROM transactions WHERE status = 'Closed' AND created_at < NOW() - INTERVAL 30 DAY");
$stmt->execute();

echo "Old records cleaned up.";
