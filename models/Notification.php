<?php
class Notification
{
    private $pdo;
    private $smsApiUrl = "https://your-sms-api.com/send"; // Replace with actual SMS API URL
    private $smsApiKey = "your-api-key"; // Replace with actual API Key

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function sendNotification($userId, $roleId, $type, $data)
    {
        $messages = $this->getMessageTemplates($type, $data);
        if (!$messages) {
            return false;
        }

        // Insert into notifications table
        $stmt = $this->pdo->prepare("
            INSERT INTO notifications (user_id, role_id, type, message, created_at)
            VALUES (:user_id, :role_id, :type, :message, NOW())
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':role_id' => $roleId,
            ':type' => $type,
            ':message' => $messages['notification']
        ]);

        // Send SMS if mobile number is provided
        if (!empty($data['mobile'])) {
            $this->sendSMS($data['mobile'], $messages['sms']);
        }

        return true;
    }

    private function getMessageTemplates($type, $data)
    {
        $templates = [
            'user_booked' => [
                'sms' => "Hello {$data['user_name']}, your appointment for {$data['service_name']} is set on {$data['date']} at {$data['time']}.",
                'notification' => "Your service transaction for {$data['service_name']} is confirmed for {$data['date']} at {$data['time']}."
            ],
            'user_cancelled' => [
                'sms' => "Hello {$data['user_name']}, your appointment for {$data['service_name']} on {$data['date']} has been cancelled.",
                'notification' => "Your service transaction for {$data['service_name']} on {$data['date']} has been cancelled."
            ],
            'transaction_reminder' => [
                'sms' => "Reminder: Your appointment for {$data['service_name']} is in 10 minutes.",
                'notification' => "Reminder: Your scheduled service transaction for {$data['service_name']} is in 10 minutes."
            ],
            'missed_transaction' => [
                'sms' => "Hello {$data['user_name']}, you missed your appointment for {$data['service_name']}. Please reschedule.",
                'notification' => "You missed your service transaction for {$data['service_name']}. Please reschedule."
            ],
            'staff_new_booking' => [
                'sms' => null, // No SMS for staff
                'notification' => "New appointment: {$data['user_name']} scheduled {$data['service_name']} for {$data['date']} at {$data['time']}."
            ],
            'staff_pending_reminder' => [
                'sms' => null, // No SMS for staff
                'notification' => "Reminder: Pending service transactions require your attention."
            ]
        ];

        return $templates[$type] ?? null;
    }

    private function sendSMS($mobile, $message)
    {
        if (empty($message)) return false;

        $postData = [
            'api_key' => $this->smsApiKey,
            'to' => $mobile,
            'message' => $message
        ];

        $ch = curl_init($this->smsApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
