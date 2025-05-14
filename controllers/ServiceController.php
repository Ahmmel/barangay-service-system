<?php
require_once '../config/database.php';
require_once '../models/Service.php';
require_once '../models/Notification.php';
require_once '../models/ActivityLog.php';

class ServiceController
{
    private Service $serviceModel;
    private Notification $notifier;
    private ActivityLog $logger;

    public function __construct(PDO $db)
    {
        $this->serviceModel = new Service($db);
        $this->notifier     = new Notification($db);
        $this->logger       = new ActivityLog($db);
    }

    // Handle Add Service Request
    public function addService()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Actor info (always passed)
        $actorId   = (int) ($_POST['session_user_id']   ?? 0);
        $actorName =         ($_POST['session_username'] ?? 'An administrator');
        $actorRole = (int) ($_POST['session_role_id']   ?? 0);

        // 1) Gather input
        $serviceName        = htmlspecialchars($_POST['serviceName']);
        $serviceDescription = $_POST['serviceDescription']
            ? htmlspecialchars($_POST['serviceDescription'])
            : null;

        // 2) Create
        $ok = $this->serviceModel->createService($serviceName, $serviceDescription);

        if ($ok) {
            // 3) Notify staff/admins
            $newId = (int)$this->serviceModel->getLastInsertId();
            $this->notifier->createNotification(
                null,
                2,
                'service_created',
                $newId,
                'New Service Added',
                "Service “{$serviceName}” (ID: {$newId}) was created by {$actorName}."
            );

            // 4) Log success
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'service',
                "Created service “{$serviceName}” (ID: {$newId})",
                'Success',
                $newId
            );
        } else {
            // Log failure
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'service',
                "Failed to create service “{$serviceName}”",
                'Failed'
            );
        }

        echo json_encode(['success' => $ok]);
    }

    // Fetch all services
    public function getServices()
    {
        $services = $this->serviceModel->getServices();
        echo json_encode($services);
    }

    // Fetch a single service
    public function getServiceById()
    {
        if (!isset($_GET['service_id']) || !is_numeric($_GET['service_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid service ID.'
            ]);
            return;
        }

        $serviceDetails = $this->serviceModel->getServiceById((int)$_GET['service_id']);
        if ($serviceDetails) {
            echo json_encode([
                'success' => true,
                'service' => $serviceDetails
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Service not found.'
            ]);
        }
    }

    // Handle Edit Service Request
    public function editService()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Actor info
        $actorId   = (int) ($_POST['session_user_id']   ?? 0);
        $actorName =         ($_POST['session_username'] ?? 'An administrator');
        $actorRole = (int) ($_POST['session_role_id']   ?? 0);

        // 1) Gather input
        $id                 = (int) $_POST['serviceId'];
        $serviceName        = htmlspecialchars($_POST['serviceName']);
        $serviceDescription = $_POST['serviceDescription']
            ? htmlspecialchars($_POST['serviceDescription'])
            : null;

        // 2) Get “before” for diff
        $old = $this->serviceModel->getServiceById($id);

        // 3) Update
        $ok = $this->serviceModel->updateService($id, $serviceName, $serviceDescription);

        if ($ok) {
            // Build diff
            $changes = [];
            if ($old) {
                if ($old['service_name'] !== $serviceName) {
                    $changes['Name'] = [
                        'old' => $old['service_name'],
                        'new' => $serviceName
                    ];
                }
                if ($old['description'] !== $serviceDescription) {
                    $changes['Description'] = [
                        'old' => $old['description'],
                        'new' => $serviceDescription
                    ];
                }
            }

            // Notify
            $this->notifier->createNotification(
                null,
                2,
                'service_updated',
                $id,
                'Service Updated',
                "Service “{$serviceName}” (ID: {$id}) was updated by {$actorName}."
            );

            // Log
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'service',
                "Updated service “{$serviceName}” (ID: {$id})",
                'Success',
                $id,
                ['changes' => $changes]
            );
        } else {
            // Log failure
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'service',
                "Failed to update service ID {$id}",
                'Failed',
                $id
            );
        }

        echo json_encode(['success' => $ok]);
    }

    // Handle Delete Service Request
    public function deleteService()
    {
        // Actor info
        $actorId   = (int) ($_POST['session_user_id']   ?? 0);
        $actorName =         ($_POST['session_username'] ?? 'An administrator');
        $actorRole = (int) ($_POST['session_role_id']   ?? 0);

        if (empty($_POST['service_id'])) {
            // Log invalid attempt
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'service',
                'Attempted delete with invalid service ID',
                'Failed',
                null,
                ['payload' => $_POST]
            );

            echo json_encode([
                'success' => false,
                'message' => 'Invalid service ID.'
            ]);
            return;
        }

        $id          = (int)$_POST['service_id'];
        $old         = $this->serviceModel->getServiceById($id);
        $serviceName = $old['service_name'] ?? "ID {$id}";

        // Perform delete
        $ok = $this->serviceModel->deleteService($id);

        if ($ok) {
            // Notify
            $this->notifier->createNotification(
                null,
                2,
                'service_deleted',
                $id,
                'Service Deleted',
                "Service “{$serviceName}” (ID: {$id}) was deleted by {$actorName}."
            );

            // Log success
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'service',
                "Deleted service “{$serviceName}” (ID: {$id})",
                'Success',
                $id
            );

            echo json_encode(['success' => true]);
        } else {
            // Log failure
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'service',
                "Failed to delete service “{$serviceName}” (ID: {$id})",
                'Failed',
                $id
            );

            echo json_encode([
                'success' => false,
                'message' => 'Unable to delete service.'
            ]);
        }
    }
}

$database = new Database();
$db = $database->getConnection();

// Instantiate the controller with the database connection
$controller = new ServiceController($db);

// Get the action parameter from the URL query string
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $controller->addService();
        break;
    case 'edit':
        $controller->editService();
        break;
    case 'delete':
        $controller->deleteService();
        break;
    case 'getServices':
        $controller->getServices();
        break;
    case 'getServiceById':
        $controller->getServiceById();
        break;
    default:
        echo json_encode(["error" => "Invalid request"]);
}
