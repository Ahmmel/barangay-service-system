<?php
require_once '../config/SemaphoreAPI.php';

class SMSNotification
{
    private $semaphore;

    public function __construct()
    {
        $this->semaphore = new SemaphoreAPI();
    }

    public function sendTransactionConfirmation($phoneNumber, $transactionCode, $scheduledTime)
    {
        $message = "Your transaction has been successfully booked! \n\n"
            . "Transaction Code: $transactionCode\n"
            . "Scheduled Time: $scheduledTime\n"
            . "Please arrive at least 10 minutes before your scheduled time. Thank you!";

        return $this->semaphore->sendSMS($phoneNumber, $message);
    }

    public function sendWalkInTransactionNotification($phoneNumber, $transactionCode, $scheduledTime)
    {
        $message = "You have successfully booked a walk-in transaction! \n\n"
            . "Transaction Code: $transactionCode\n"
            . "Transaction Time: $scheduledTime\n"
            . "Please observe the queue and wait for your turn. Thank you for utilizing the Barangay services.";
        return $this->semaphore->sendSMS($phoneNumber, $message);
    }

    public function sendNextTransactionNotification($phoneNumber, $transactionCode, $scheduledTime)
    {
        $message = "You are next in queue. \n\n"
            . "Transaction Code: $transactionCode\n"
            . "Scheduled Time: $scheduledTime\n"
            . "Please arrive 10 minutes before your scheduled time. If you're more than 25 minutes late, your transaction may be canceled.";

        return $this->semaphore->sendSMS($phoneNumber, $message);
    }

    public function sendReminder($phoneNumber, $scheduledTime)
    {
        $message = "Reminder: Your transaction is scheduled at $scheduledTime today. Please arrive 10 minutes early.";
        return $this->semaphore->sendSMS($phoneNumber, $message);
    }
}
