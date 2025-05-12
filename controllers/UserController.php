<?php
require_once '../config/database.php';
require_once '../models/User.php';
require_once '../models/SMSNotification.php';
class UserController
{
    private $user;
    private $sms;

    public function __construct($db)
    {
        $this->user = new User($db);
        $this->sms = new SMSNotification($db);
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
                echo json_encode(["success" => false, "error" => "Passwords do not match."]);
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
                    echo json_encode(["success" => false, "error" => "Error uploading profile image"]);
                    return;
                }
            }


            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Call the createUser method from the model to add the new user
            $result = $this->user->createUser(
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
        $users = $this->user->getUsers();
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
                    echo json_encode(["success" => false, "error" => "Error uploading profile image"]);
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
            $result = $this->user->updateUser(
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
                $result = $this->user->updatePassword($id, $hashedPassword);
            }

            if ($profileImagePath) {
                $result = $this->user->updateProfile($id, $profileImagePath);
            }

            // Return the result as JSON
            echo json_encode(["success" => $result]);
        }
    }


    // Handle Delete User Request
    public function deleteUser()
    {
        if (isset($_POST['user_id'])) {
            $result = $this->user->deleteUser($_POST['user_id']);
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
            $user = $this->user->getUserDetailsById($userId);
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
            // Sanitize input data
            $firstName = htmlspecialchars($_POST['firstName'] ?? '');
            $lastName = htmlspecialchars($_POST['lastName'] ?? '');
            $gender = htmlspecialchars($_POST['gender'] ?? '');
            $birthdate = htmlspecialchars($_POST['birthdate'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $mobileNumber = htmlspecialchars($_POST['mobileNumber'] ?? '');
            $userName = htmlspecialchars($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validate required fields
            if (
                empty($firstName) || empty($lastName) || empty($gender) || empty($mobileNumber) ||
                empty($birthdate) || empty($email) || empty($userName) || empty($password)
            ) {
                echo json_encode(["success" => false, "message" => "All fields are required."]);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(["success" => false, "message" => "Invalid email format."]);
                return;
            }

            if (!preg_match("/^[a-zA-Z-' ]*$/", $firstName) || !preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
                echo json_encode(["success" => false, "message" => "Name must contain only letters and spaces."]);
                return;
            }

            if (!preg_match('/^\+?[0-9]{10,15}$/', $mobileNumber)) {
                echo json_encode(["success" => false, "message" => "Invalid mobile number."]);
                return;
            }

            // Check if the user already exists
            if ($this->user->checkUserExist($email, $userName)) {
                echo json_encode(["success" => false, "message" => "User already exists."]);
                return;
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Register the user using the model
            $result = $this->user->registerUser(
                $email,
                $userName,
                $hashedPassword,
                $firstName,
                $lastName,
                $gender,
                $birthdate,
                $mobileNumber
            );

            $this->sms->welcomeMessage($mobileNumber, $firstName, $lastName);
            echo json_encode(["success" => $result]);
        }
    }

    public function resetPassword()
    {
        // Read inputs
        $isMobile = filter_var($_POST['isMobile'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $contact  = trim($_POST['contact'] ?? '');

        // Validate contact
        if ($isMobile) {
            // Allow only '+' and digits, then check length
            $mobile = preg_replace('/[^\d\+]/', '', $contact);
            if (empty($mobile) || !preg_match('/^\+?\d{7,15}$/', $mobile)) {
                echo json_encode([
                    "success" => false,
                    "message" => "A valid mobile number is required."
                ]);
                return;
            }
            // Lookup user by mobile
            $user = $this->user->getByMobile($mobile);
            if (! $user) {
                echo json_encode([
                    "success" => false,
                    "message" => "No account found with that mobile number."
                ]);
                return;
            }
            $userId     = $user['id'];
            $userMobile = $mobile;
        } else {
            // Sanitize & validate email
            $email = filter_var($contact, FILTER_SANITIZE_EMAIL);
            if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode([
                    "success" => false,
                    "message" => "A valid email address is required."
                ]);
                return;
            }
            // Lookup user and their mobile by email
            $userData = $this->user->getUserMobileByEmail($email);
            if (!$userData) {
                echo json_encode([
                    "success" => false,
                    "message" => "No account found with that email address."
                ]);
                return;
            } else if ($userData['mobile_number'] == null || empty($userData['mobile_number'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "No mobile number found for that email address. Please contact the administrator."
                ]);
                return;
            } else {
                $mobileNumber = $userData['mobile_number'];
            }
            // If you need the userId for the update, you can fetch it similarly:
            $user = $this->user->getUserByEmail($email);
            $userId = $user['id'];
        }

        // Generate & hash a new password
        $newPassword    = $this->user->generateNewPassword();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password in DB (by user ID)
        $updated = $this->user->changePassword($userId, $hashedPassword);
        if (! $updated) {
            echo json_encode([
                "success" => false,
                "message" => "Failed to reset password. Please try again."
            ]);
            return;
        }

        // Send the new password via SMS
        $this->sms->sendPasswordResetSMS($mobileNumber, $newPassword);

        echo json_encode([
            "success" => true,
            "message" => "A new password has been sent via SMS."
        ]);
    }

    public function changePassword()
    {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message'   => 'Please use POST to change your password.'
            ]);
            exit;
        }

        // 1. Gather & validate input
        $userId      = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_INT);
        $oldPassword = filter_input(INPUT_POST, 'oldPassword', FILTER_UNSAFE_RAW);
        $newPassword = filter_input(INPUT_POST, 'newPassword', FILTER_UNSAFE_RAW);

        if (!$userId || !$oldPassword || !$newPassword) {
            echo json_encode([
                'success' => false,
                'message'   => 'All fields are required.'
            ]);
            return;
        }

        // 2. Fetch the current password hash
        $currentHash = $this->user->getPasswordHashById($userId);
        if (!$currentHash) {
            echo json_encode([
                'success' => false,
                'message'   => 'User not found.'
            ]);
            return;
        }

        // 3. Verify the old password
        if (!password_verify($oldPassword, $currentHash)) {
            echo json_encode([
                'success' => false,
                'message'   => 'Current password is incorrect.'
            ]);
            return;
        }

        // 4. Hash the new password and update
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updated = $this->user->updatePassword($userId, $newHash);

        if ($updated) {
            echo json_encode([
                'success' => true,
                'message' => 'Password updated successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message'   => 'Unable to update password. Please try again later.'
            ]);
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
    case 'resetPassword':
        $controller->resetPassword();
        break;
    case 'register':
        $controller->registerUser();
        break;
    case 'changePassword':
        $controller->changePassword();
        break;
    default:
        echo json_encode(["error" => "Invalid request"]);
}
