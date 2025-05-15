<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Requirement.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class RequirementController
{
    private $requirementModel;
    private $notifier;
    private $logger;

    public function __construct($db)
    {
        $this->requirementModel = new Requirement($db);
        $this->notifier         = new Notification($db);
        $this->logger           = new ActivityLog($db);
    }

    // Handle Add Requirement Request
    public function addRequirement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        // Actor info (always passed)
        $actorId   = (int) ($_POST['session_user_id']   ?? 0);
        $actorName =         ($_POST['session_username'] ?? 'An administrator');
        $actorRole = (int) ($_POST['session_role_id']   ?? 0);

        $serviceId   = $_POST['serviceId'] ?? null;
        $description = !empty($_POST['description'])
            ? htmlspecialchars($_POST['description'])
            : null;

        if ($serviceId && $description) {
            $ok = $this->requirementModel->createRequirement($serviceId, $description);
            if ($ok) {
                $newId = (int) $this->requirementModel->getLastInsertId();
                // Notify staff/admins
                $this->notifier->createNotification(
                    null,
                    2,
                    'requirement_created',
                    $newId,
                    'New Requirement Added',
                    "Requirement '{$description}' (ID: {$newId}) for Service ID {$serviceId} was created by {$actorName}."
                );
                // Log success
                $this->logger->logActivity(
                    $actorId,
                    $actorRole,
                    'requirement',
                    "Created requirement ID {$newId} for service ID {$serviceId}",
                    'Success',
                    $newId
                );
            } else {
                // Log failure
                $this->logger->logActivity(
                    $actorId,
                    $actorRole,
                    'requirement',
                    "Failed to create requirement for service ID {$serviceId}",
                    'Failed'
                );
            }
            echo json_encode(['success' => $ok]);
        } else {
            // Log invalid input
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'requirement',
                'Attempted add requirement with missing data',
                'Failed',
                null,
                ['serviceId' => $serviceId, 'description' => $description]
            );
            echo json_encode([
                'success' => false,
                'message' => 'Service ID and Description are required.'
            ]);
        }
    }

    // Fetch all requirements
    public function getRequirements()
    {
        $requirements = $this->requirementModel->getAllRequirements();
        echo json_encode($requirements);
    }

    // Fetch a single requirement by its ID
    public function getRequirementById()
    {
        if (isset($_GET['requirement_id']) && is_numeric($_GET['requirement_id'])) {
            $id                = (int) $_GET['requirement_id'];
            $services          = $this->requirementModel->getServices();
            $requirementDetail = $this->requirementModel->getRequirementById($id);
            if ($requirementDetail && $services) {
                echo json_encode([
                    'success'     => true,
                    'requirement' => $requirementDetail,
                    'services'    => $services
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Requirement or Service not found.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid requirement ID.'
            ]);
        }
    }

    // Handle Edit Requirement Request
    public function editRequirement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $actorId   = (int) ($_POST['session_user_id']   ?? 0);
        $actorName =         ($_POST['session_username'] ?? 'An administrator');
        $actorRole = (int) ($_POST['session_role_id']   ?? 0);

        $id          = (int) $_POST['requirementId'];
        $description = !empty($_POST['description'])
            ? htmlspecialchars($_POST['description'])
            : null;
        if ($description) {
            $old = $this->requirementModel->getRequirementById($id);
            $ok  = $this->requirementModel->updateRequirement($id, $description);
            if ($ok) {
                $this->notifier->createNotification(
                    null,
                    2,
                    'requirement_updated',
                    $id,
                    'Requirement Updated',
                    "Requirement (ID: {$id}) was updated by {$actorName}."
                );
                $changes = [];
                if ($old && $old['description'] !== $description) {
                    $changes['Description'] = ['old' => $old['description'], 'new' => $description];
                }
                $this->logger->logActivity(
                    $actorId,
                    $actorRole,
                    'requirement',
                    "Updated requirement ID {$id}",
                    'Success',
                    $id,
                    ['changes' => $changes]
                );
            } else {
                $this->logger->logActivity(
                    $actorId,
                    $actorRole,
                    'requirement',
                    "Failed to update requirement ID {$id}",
                    'Failed',
                    $id
                );
            }
            echo json_encode(['success' => $ok]);
        } else {
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'requirement',
                'Attempted update requirement with missing description',
                'Failed',
                $id
            );
            echo json_encode([
                'success' => false,
                'message' => 'Description is required.'
            ]);
        }
    }

    // Handle Delete Requirement Request
    public function deleteRequirement()
    {
        $actorId   = (int) ($_POST['session_user_id']   ?? 0);
        $actorName =         ($_POST['session_username'] ?? 'An administrator');
        $actorRole = (int) ($_POST['session_role_id']   ?? 0);

        if (empty($_POST['requirement_id'])) {
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'requirement',
                'Attempted delete with invalid requirement ID',
                'Failed',
                null,
                ['payload' => $_POST]
            );
            echo json_encode([
                'success' => false,
                'message' => 'Invalid requirement ID.'
            ]);
            return;
        }
        $id   = (int) $_POST['requirement_id'];
        $old  = $this->requirementModel->getRequirementById($id);
        $desc = $old['description'] ?? "ID {$id}";
        $ok   = $this->requirementModel->deleteRequirement($id);
        if ($ok) {
            $this->notifier->createNotification(
                null,
                2,
                'requirement_deleted',
                $id,
                'Requirement Deleted',
                "Requirement '{$desc}' (ID: {$id}) was deleted by {$actorName}."
            );
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'requirement',
                "Deleted requirement '{$desc}' (ID: {$id})",
                'Success',
                $id
            );
            echo json_encode(['success' => true]);
        } else {
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'requirement',
                "Failed to delete requirement ID {$id}",
                'Failed',
                $id
            );
            echo json_encode([
                'success' => false,
                'message' => 'Unable to delete requirement.'
            ]);
        }
    }

    // Fetch requirements by service ID
    public function getRequirementsByServiceId()
    {
        if (isset($_GET['service_id']) && is_numeric($_GET['service_id'])) {
            $serviceId = (int) $_GET['service_id'];
            $reqs = $this->requirementModel->getRequirementsByServiceId($serviceId);
            echo json_encode($reqs);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid service ID.'
            ]);
        }
    }

    // Fetch all services
    public function getServices()
    {
        $services = $this->requirementModel->getServices();
        echo json_encode($services);
    }

    public function getRequirementsByServiceIds()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $serviceIds = $input['service_ids'] ?? [];

            if (empty($serviceIds)) {
                echo json_encode(['success' => false, 'message' => 'No service IDs provided.']);
                return;
            }

            $requirements = $this->requirementModel->getRequirementsForServices($serviceIds);
            echo json_encode([
                'success' => true,
                'data' => $requirements
            ]);
        }
    }
}

$database = new Database();
$db = $database->getConnection();

// Instantiate the controller with the database connection
$controller = new RequirementController($db);

// Get the action parameter from the URL query string
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $controller->addRequirement();
        break;
    case 'edit':
        $controller->editRequirement();
        break;
    case 'delete':
        $controller->deleteRequirement();
        break;
    case 'getRequirements':
        $controller->getRequirements();
        break;
    case 'getRequirementById':
        $controller->getRequirementById();
        break;
    case 'getRequirementsByServiceId':
        $controller->getRequirementsByServiceId();
        break;
    case 'getServices':
        $controller->getServices();
        break;
    case 'getRequirementsByServiceIds':
        $controller->getRequirementsByServiceIds();
        break;
    default:
        echo json_encode(["error" => "Invalid request2"]);
}
