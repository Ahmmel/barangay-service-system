<?php
require_once '../config/database.php';
require_once '../models/Requirement.php';

class RequirementController
{
    private $conn;
    private $requirementModel;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->requirementModel = new Requirement($db);
    }

    // Handle Add Requirement Request
    public function addRequirement()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect all necessary parameters from the POST request
            $serviceId = isset($_POST['serviceId']) ? $_POST['serviceId'] : null;
            $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;

            // Check if required parameters are provided
            if ($serviceId && $description) {
                // Call the createRequirement method from the model to add the new requirement
                $result = $this->requirementModel->createRequirement(
                    $serviceId,
                    $description
                );

                // Return the result as JSON
                echo json_encode(["success" => $result]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Service ID and Description are required.'
                ]);
            }
        }
    }

    // Fetch all requirements
    public function getRequirements()
    {
        $requirements = $this->requirementModel->getRequirements();
        echo json_encode($requirements);
    }

    // Fetch a single requirement by its ID
    public function getRequirementById()
    {
        if (isset($_GET['requirement_id']) && is_numeric($_GET['requirement_id'])) {
            $requirementId = $_GET['requirement_id'];

            // Get the requirement details by ID
            $requirementDetails = $this->requirementModel->getRequirementById($requirementId);

            if (!empty($requirementDetails)) {
                echo json_encode([
                    'success' => true,
                    'requirement' => $requirementDetails
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Requirement not found.'
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['requirementId'];
            $serviceId = isset($_POST['serviceId']) ? $_POST['serviceId'] : null;
            $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;

            if ($serviceId && $description) {
                // Call the updateRequirement method from the model
                $result = $this->requirementModel->updateRequirement(
                    $id,
                    $serviceId,
                    $description
                );

                echo json_encode(["success" => $result]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Service ID and Description are required.'
                ]);
            }
        }
    }

    // Handle Delete Requirement Request
    public function deleteRequirement()
    {
        if (isset($_POST['requirement_id'])) {
            $result = $this->requirementModel->deleteRequirement($_POST['requirement_id']);
            echo json_encode(["success" => $result]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid requirement ID.'
            ]);
        }
    }

    // Fetch requirements by service ID
    public function getRequirementsByServiceId()
    {
        if (isset($_GET['service_id']) && is_numeric($_GET['service_id'])) {
            $serviceId = $_GET['service_id'];

            // Get the requirements associated with the service ID
            $requirementsForService = $this->requirementModel->getRequirementsByServiceId($serviceId);

            echo json_encode($requirementsForService);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid service ID.'
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
    default:
        echo json_encode(["error" => "Invalid request"]);
}
?>
