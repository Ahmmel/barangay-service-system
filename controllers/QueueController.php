<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['next_queue'])) {
    $currentQueue = $queue->getCurrentQueue();
    if ($currentQueue) {
        $queue->nextQueue($currentQueue['queue_id']);
        header("Location: ../dashboard.php");
        exit();
    }
}
?>