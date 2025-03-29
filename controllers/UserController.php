<?php
require_once '../config/database.php';
require_once '../models/User.php';

class UserController
{
    private $conn;
    private $userModel;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->userModel = new User($db);
    }

    // Handle Add User Request
    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect all necessary parameters from the POST request
            $first_name = htmlspecialchars($_POST['firstName']);
            $middle_name = htmlspecialchars($_POST['middleName']);
            $last_name = htmlspecialchars($_POST['lastName']);
            $email = htmlspecialchars($_POST['email']);
            $userName = htmlspecialchars($_POST['userName']);
            $password = htmlspecialchars($_POST['password']);
            $confirmPassword = htmlspecialchars($_POST['confirmPassword']);
            $mobileNumber = htmlspecialchars($_POST['mobileNumber']);
            $address = htmlspecialchars($_POST['address']);
            $user_role = (int)$_POST['role'];  // Role ID as an integer
            $suffix = htmlspecialchars($_POST['suffix']);
            $gender = (int) $_POST['gender'];
            $maritalStatus = (int) $_POST['maritalStatus'];
            $isVerified = (int)$_POST['isVerified'];  // Role ID as an integer
            $birthdate = $_POST['birthdate'];  // In YYYY-MM-DD format

            // Handle password validation
            if ($password !== $confirmPassword) {
                echo json_encode(["error" => "Passwords do not match."]);
                return;
            }

            // Handle profile image upload (if any)
            $profileImagePath = null;
            if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "../uploads/";
                $profileImagePath = $targetDir . basename($_FILES["profileImage"]["name"]);

                // Get the original file name and its extension
                $fileName = basename($_FILES["profileImage"]["name"]);
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                // Generate a unique file name to avoid duplicates (using timestamp and a random string)
                $newFileName = uniqid('profile_', true) . '.' . $fileExtension;

                // Define the full path to where the file will be uploaded
                $profileImagePath = $targetDir . $newFileName;

                if (!move_uploaded_file($_FILES["profileImage"]["tmp_name"], $profileImagePath)) {
                    echo json_encode(["error" => "Error uploading profile image"]);
                    return;
                }
            }


            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Call the createUser method from the model to add the new user
            $result = $this->userModel->createUser(
                $userName,
                $email,
                $hashedPassword,
                $gender,
                $birthdate,
                $address,
                $isVerified,
                $profileImagePath,
                $user_role,
                $first_name,
                $middle_name,
                $last_name,
                $suffix,
                $maritalStatus,
                $mobileNumber
            );


            // Return the result as JSON
            echo json_encode(["success" => $result]);
        }
    }


    // Fetch all users
    public function getUsers()
    {
        $users = $this->userModel->getUsers();
        echo json_encode($users);
    }

    // Fetch a single user
    public function getUserById()
    {
        // Validate the user_id parameter
        if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
            $userId = $_GET['user_id'];

            // Create a new database connection
            $database = new Database();
            $db = $database->getConnection();

            // Instantiate the User model
            $user = new User($db);

            // Get the user details by ID
            $userDetails = $user->getUserById($userId);
            $userRoles = $user->getUserRoles();
            $userGenders = $user->getUserGenders();
            $maritalStatuses = $user->getMaritalStatus();

            // Check if user details are found
            if (!empty($userDetails)) {
                // Return the success response with user details
                echo json_encode([
                    'success' => true,
                    'user' => $userDetails,
                    'roles' => $userRoles,
                    'genders' => $userGenders,
                    'marital_statuses' => $maritalStatuses
                ]);
            } else {
                // If no user found, return an error response
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
            }
        } else {
            // If user_id is not valid, return an error response
            echo json_encode([
                'success' => false,
                'message' => 'Invalid user ID.'
            ]);
        }
    }

    public function getUserPrequisite()
    {
        $database = new Database();
        $db = $database->getConnection();

        // Instantiate the User model
        $user = new User($db);

        $userRoles = $user->getUserRoles();
        $userGenders = $user->getUserGenders();
        $maritalStatuses = $user->getMaritalStatus();

        // Return the success response with user details
        echo json_encode([
            'success' => true,
            'roles' => $userRoles,
            'genders' => $userGenders,
            'marital_statuses' => $maritalStatuses
        ]);
    }

    // Handle Edit User Request
    public function editUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['userId'];
            $userName = htmlspecialchars($_POST['userName']);
            $email = htmlspecialchars($_POST['email']);
            $user_role = (int)$_POST['role'];
            $gender = (int)$_POST['gender'];
            $maritalStatus = (int)$_POST['maritalStatus'];
            $birthdate = $_POST['birthdate'];
            $mobileNumber = htmlspecialchars($_POST['mobileNumber']);
            $address = htmlspecialchars($_POST['address']);
            $isVerified = isset($_POST['isVerified']) ? (int)$_POST['isVerified'] : 1;
            $profileImage = isset($_FILES['profileImage']) ? $_FILES['profileImage']['name'] : null; // Handling file upload
            $first_name = htmlspecialchars($_POST['firstName']);
            $middle_name = htmlspecialchars($_POST['middleName']);
            $last_name = htmlspecialchars($_POST['lastName']);
            $suffix = htmlspecialchars($_POST['suffix']);

            // Handle profile image upload (if any)
            $profileImagePath = null;
            if ($profileImage) {
                // Define the target directory for uploads
                $targetDir = "../uploads/";

                // Get the original file name and its extension
                $fileName = basename($_FILES["profileImage"]["name"]);
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                // Generate a unique file name to avoid duplicates (using timestamp and a random string)
                $newFileName = uniqid('profile_', true) . '.' . $fileExtension;

                // Define the full path to where the file will be uploaded
                $profileImagePath = $targetDir . $newFileName;

                // Move the uploaded file to the target directory
                if (!move_uploaded_file($_FILES["profileImage"]["tmp_name"], $profileImagePath)) {
                    echo json_encode(["error" => "Error uploading profile image"]);
                    return;
                }
            }


            // Initialize hashedPassword as null, so password will only be updated if provided
            $hashedPassword = null;

            // Check if password was provided and hash it
            if (!empty($_POST['password'])) {
                $password = htmlspecialchars($_POST['password']);
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            }

            // Call the updateUser method from the model
            $result = $this->userModel->updateUser(
                $id,
                $userName,
                $email,
                $gender,
                $birthdate,
                $address,
                $isVerified,
                $user_role,
                $first_name,
                $middle_name,
                $last_name,
                $suffix,
                $maritalStatus,
                $mobileNumber
            );

            // Only update password if it was changed
            if ($hashedPassword) {
                $result = $this->userModel->updatePassword($id, $hashedPassword);
            }

            if ($profileImagePath) {
                $result = $this->userModel->updateProfile($id, $profileImagePath);
            }

            // Return the result as JSON
            echo json_encode(["success" => $result]);
        }
    }


    // Handle Delete User Request
    public function deleteUser()
    {
        if (isset($_POST['user_id'])) {
            $result = $this->userModel->deleteUser($_POST['user_id']);
            echo json_encode(["success" => $result]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid user ID.'
            ]);
        }
    }

    // Check if a user exists
    public function getUserDetailsById()
    {
        if (isset($_POST['userId'])) {
            $userId = $_POST['userId'];
            $user = $this->userModel->getUserDetailsById($userId);
            if ($user) {
                echo json_encode(["success" => true, "user" => $user[0]]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid User.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid User.'
            ]);
        }
    }

    // Handle User Registration
    public function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email']);
            $userName = htmlspecialchars($_POST['userName']);
            $password = htmlspecialchars($_POST['password']);
            $confirmPassword = htmlspecialchars($_POST['confirmPassword']);

            // Check if the email and username are provided
            if (empty($email) || empty($userName) || empty($password) || empty($confirmPassword)) {
                echo json_encode(["error" => "All fields are required."]);
                return;
            }

            // Check user if exists
            $checkUserExist = $this->userModel->checkUserExist($email, $userName);
            if ($checkUserExist) {
                echo json_encode(["error" => "User already exists."]);
                return;
            }

            // Handle password validation
            if ($password !== $confirmPassword) {
                echo json_encode(["error" => "Passwords do not match."]);
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Call the createUser method from the model to add the new user
            $result = $this->userModel->registerUser(
                $userName,
                $email,
                $hashedPassword
            );


            // Return the result as JSON
            echo json_encode(["success" => $result]);
        }
    }
}


$database = new Database();
$db = $database->getConnection();

// Instantiate the controller with the database connection
$controller = new UserController($db);

// Get the action parameter from the URL query string
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $controller->addUser();
        break;
    case 'edit':
        $controller->editUser();
        break;
    case 'delete':
        $controller->deleteUser();
        break;
    case 'getUsers':
        $controller->getUsers();
        break;
    case 'getUserById':
        $controller->getUserById();
        break;
    case 'getUserPrequisite':
        $controller->getUserPrequisite();
        break;
    case 'getUserDetails':
        $controller->getUserDetailsById();
        break;

    case 'register':
        $controller->registerUser();
        break;
    default:
        echo json_encode(["error" => "Invalid request"]);
}
