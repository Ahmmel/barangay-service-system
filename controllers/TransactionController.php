<?php
require_once '../models/TransactionModel.php';
require_once '../models/SMSNotification.php';

$transactionModel = new TransactionModel();
$sms = new SMSNotification();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_GET['action'] === 'createTransaction') {
        $userId = $_POST['userId'];
        $scheduledTime = $_POST['scheduledTime'];
        $services = $_POST['services'];

        $result = $transactionModel->createTransaction($userId, $scheduledTime, $services);

        if ($result['success']) {
            $userPhone = $transactionModel->getUserPhoneNumber($userId);
            if ($userPhone) {
                $sms->sendTransactionConfirmation($userPhone, $result['transaction_code'], $scheduledTime);
            }
        }

        echo json_encode($result);
    }

    if ($_GET['action'] === 'updateStatus') {
        $transactionId = $_POST['transactionId'];
        $status = $_POST['status'];
        $reason = $_POST['reason'] ?? null;

        $updated = $transactionModel->updateTransactionStatus($transactionId, $status, $reason);
        echo json_encode(["success" => $updated]);
    }

    if ($_GET['action'] === 'setNextTransaction') {
        $nextTransaction = $transactionModel->setNextTransaction();

        if ($nextTransaction) {
            $userPhone = $transactionModel->getUserPhoneNumber($nextTransaction['user_id']);
            if ($userPhone) {
                $sms->sendNextTransactionNotification($userPhone, $nextTransaction['transaction_code'], $nextTransaction['scheduled_time']);
            }
        }

        echo json_encode(["success" => (bool)$nextTransaction]);
    }
}
