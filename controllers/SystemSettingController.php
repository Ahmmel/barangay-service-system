<?php
require_once '../config/Database.php';
require_once '../models/SystemSettings.php';

class SystemSettingController
{
    private $systemSettings;

    public function __construct($db)
    {
        $this->systemSettings = SystemSettings::getInstance($db);
    }

    public function updateSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatableKeys = [
                'booking_time_start',
                'booking_time_end',
                'no_show_timeout_minutes',
                'sms_sender_name',
                'max_transactions_per_day',
                'enable_sms_notifications',
                'minimum_booking_lead_time_minutes',
                'staff_update_cutoff_time',
                'staff_update_start_time',
                'enable_saturday',
                'saturday_start_time',
                'saturday_end_time'
            ];

            foreach ($updatableKeys as $key) {
                $value = $_POST[$key] ?? null;

                if ($key === 'enable_sms_notifications' || $key === 'enable_saturday') {
                    $value = $value ? '1' : '0';
                }

                if ($value !== null) {
                    $this->systemSettings->update($key, trim($value));
                }
            }

            echo json_encode(['success' => true, 'message' => 'System settings updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    public function getSettings()
    {
        $settings = [
            'booking_time_start' => $this->systemSettings->get('booking_time_start', '08:00'),
            'booking_time_end' => $this->systemSettings->get('booking_time_end', '20:00'),
            'minimum_booking_lead_time_minutes' => $this->systemSettings->get('minimum_booking_lead_time_minutes', 30),
            'max_transactions_per_day' => $this->systemSettings->get('max_transactions_per_day', 10),
            'no_show_timeout_minutes' => $this->systemSettings->get('no_show_timeout_minutes', 15),
            'sms_sender_name' => $this->systemSettings->get('sms_sender_name', ''),
            'enable_sms_notifications' => $this->systemSettings->get('enable_sms_notifications', '0'),
            'staff_update_cutoff_time' => $this->systemSettings->get('enable_sms_notifications', '18:00'),
            'staff_update_start_time' => $this->systemSettings->get('staff_update_start_time', '07:00'),
            'saturday_start_time' => $this->systemSettings->get('saturday_start_time', '08:00'),
            'saturday_end_time' => $this->systemSettings->get('saturday_end_time', '12:00'),
            'enable_saturday' => $this->systemSettings->get('enable_saturday', '0') === '1' ? 'checked' : ''
        ];

        echo json_encode(['success' => true, 'settings' => $settings]);
    }
}

$database = new Database();
$db = $database->getConnection();
$controller = new SystemSettingController($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'save':
        $controller->updateSettings();
        break;
    case 'get':
        $controller->getSettings();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
