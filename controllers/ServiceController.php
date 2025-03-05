<?php
require_once '../config/database.php';
require_once '../models/Service.php';

class ServiceController
{
    private $serviceModel;

    public function __construct($db)
    {
        $this->serviceModel = new Service($db);
    }

    // Handle Add Service Request
    public function addService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect all necessary parameters from the POST request
            $serviceName = htmlspecialchars($_POST['serviceName']);
            $serviceDescription = isset($_POST['serviceDescription']) ? htmlspecialchars($_POST['serviceDescription']) : null;

            // Call the createService method from the model to add the new service
            $result = $this->serviceModel->createService(
                $serviceName,
                $serviceDescription
            );

            // Return the result as JSON
            echo json_encode(["success" => $result]);
        }
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
        if (isset($_GET['service_id']) && is_numeric($_GET['service_id'])) {
            $serviceId = $_GET['service_id'];

            // Get the service details by ID
            $serviceDetails = $this->serviceModel->getServiceById($serviceId);

            if (!empty($serviceDetails)) {
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
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid service ID.'
            ]);
        }
    }

    // Handle Edit Service Request
    public function editService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['serviceId'];
            $serviceName = htmlspecialchars($_POST['serviceName']);
            $serviceDescription = isset($_POST['serviceDescription']) ? htmlspecialchars($_POST['serviceDescription']) : null;

            // Call the updateService method from the model
            $result = $this->serviceModel->updateService(
                $id,
                $serviceName,
                $serviceDescription
            );

            echo json_encode(["success" => $result]);
        }
    }

    // Handle Delete Service Request
    public function deleteService()
    {
        if (isset($_POST['service_id'])) {
            $result = $this->serviceModel->deleteService($_POST['service_id']);
            echo json_encode(["success" => $result]);
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
