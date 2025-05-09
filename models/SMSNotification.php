<?php
require_once '../config/Database.php';
require_once '../config/SemaphoreAPI.php';
require_once '../models/SystemSettings.php';

class SMSNotification
{
    private $semaphore;
    private $conn;
    private $systemSettings;

    public function __construct($db)
    {
        $this->semaphore = new SemaphoreAPI();
        $this->conn = $db;
        $this->systemSettings = SystemSettings::getInstance($this->conn);
    }

    public function isSMSEnabled()
    {
        return $this->systemSettings->get('enable_sms_notifications', '0') === '1';
    }

    public function getSenderName()
    {
        return $this->systemSettings->get('sms_sender_name', 'QPILAPH');
    }

    public function sendTransactionConfirmation($phoneNumber, $transactionCode, $scheduledTime)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "Your transaction has been successfully booked! \n\n"
            . "Transaction Code: $transactionCode\n"
            . "Scheduled Time: $scheduledTime\n"
            . "Please arrive at least 10 minutes before your scheduled time. Thank you!";

        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }

    public function sendWalkInTransactionNotification($phoneNumber, $transactionCode, $scheduledTime)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "You have successfully booked a walk-in transaction! \n\n"
            . "Transaction Code: $transactionCode\n"
            . "Transaction Time: $scheduledTime\n"
            . "Please observe the queue and wait for your turn. Thank you for utilizing the Barangay services.";
        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }

    public function sendNextTransactionNotification($phoneNumber, $transactionCode, $scheduledTime)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "You are next in queue. \n\n"
            . "Transaction Code: $transactionCode\n"
            . "Scheduled Time: $scheduledTime\n"
            . "Please arrive 10 minutes before your scheduled time. If you're more than 25 minutes late, your transaction may be canceled.";

        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }


    public function sendPasswordResetSMS($phoneNumber, $newPassword)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "Your password has been successfully reset.\n\n"
            . "Temporary Password: $newPassword\n"
            . "For your security, please log in and change your password immediately.\n\n"
            . "Thank you,\n"
            . "QPila Support";

        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }


    public function sendReminder($phoneNumber, $scheduledTime)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "Reminder: Your transaction is scheduled at $scheduledTime today. Please arrive 10 minutes early.";
        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }
}
