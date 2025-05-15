<?php
require_once '../config/Database.php';
require_once '../config/SemaphoreAPI.php';
require_once '../models/SystemSettings.php';
require_once '../models/Requirement.php';

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

    public function sendTransactionConfirmation($phoneNumber, $transactionCode, $scheduledTime, array $serviceIds = [])
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "Your transaction has been successfully booked!\n\n"
            . "Transaction Code: $transactionCode\n"
            . "Scheduled Time: $scheduledTime\n";

        // Optional: Include requirements if available
        if (!empty($serviceIds)) {
            $requirementsText = $this->getServiceRequirementsText($serviceIds);
            if (!empty($requirementsText)) {
                $message .= "\nRequirements:\n$requirementsText\n";
            }
        }

        $message .= "\nPlease arrive at least 10 minutes before your scheduled time.\nThank you!";

        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }

    public function sendWalkInTransactionNotification($phoneNumber, $transactionCode, $scheduledTime, array $serviceIds = [])
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "You have successfully booked a walk-in transaction!\n\n"
            . "Transaction Code: $transactionCode\n"
            . "Transaction Time: $scheduledTime\n";

        // Optional: Include requirements
        if (!empty($serviceIds)) {
            $requirementsText = $this->getServiceRequirementsText($serviceIds);
            if (!empty($requirementsText)) {
                $message .= "\nRequirements:\n$requirementsText\n";
            }
        }

        $message .= "\nPlease observe the queue and wait for your turn.\nThank you for utilizing the Barangay services.";

        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }


    public function sendNextTransactionNotification($phoneNumber, $transactionCode, $scheduledTime)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "You are next in queue. \n\n"
            . "Transaction Code: $transactionCode\n"
            . "Scheduled Time: $scheduledTime\n"
            . "Please arrive 10 minutes before your scheduled time. If you're late, your transaction might be cancelled.";

        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }

    public function sendOptionalEarlyArrivalNotification($phoneNumber)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "An earlier slot has become available for your scheduled transaction.\n\n"
            . "You are welcome to arrive ahead of your original time if it is more convenient, or you may proceed as scheduled. "
            . "Kindly ensure you are present at least 10 minutes before your chosen time. Thank you for your cooperation.";

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

    //registration welcome message
    public function welcomeMessage($phoneNumber, $firstName, $lastName)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "Welcome to QPila, $firstName $lastName!\n\n"
            . "You can now book your transactions online—thank you for registering!\n"
            . "Be sure to verify your account to make the most of our services.\n"
            . "Whether you prefer to log in and book online or visit us in person, we’re here to help.";

        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }


    public function sendReminder($phoneNumber, $scheduledTime)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "Reminder: Your transaction is scheduled at $scheduledTime today. Please arrive 10 minutes early.";
        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }

    private function getServiceRequirementsText(array $serviceIds)
    {
        $requirementModel = new Requirement($this->conn);
        $requirements = $requirementModel->getRequirementsForServices($serviceIds);

        if (empty($requirements)) return '';

        $grouped = [];
        foreach ($requirements as $req) {
            $grouped[$req['service_id']][] = $req['description'];
        }

        $services = $this->getServiceNamesByIds($serviceIds);

        $lines = [];
        foreach ($serviceIds as $id) {
            $serviceName = $services[$id] ?? "Service #$id";
            $lines[] = "- $serviceName:";
            $items = $grouped[$id] ?? ['No specific requirements'];
            foreach ($items as $desc) {
                $lines[] = "  • $desc";
            }
        }

        return implode("\n", $lines);
    }

    private function getServiceNamesByIds(array $ids)
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->conn->prepare("SELECT id, service_name FROM services WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [id => service_name]
    }

    public function sendNoShowNotification($transactionCode, $phoneNumber)
    {
        if (!$this->isSMSEnabled()) return false;

        $message = "Your transaction with code $transactionCode has been marked as no-show. Please contact us for further assistance.";
        return $this->semaphore->sendSMS($phoneNumber, $message, $this->getSenderName());
    }
}
