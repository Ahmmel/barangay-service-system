<?php
require_once '../config/database.php';
require_once '../models/User.php';
require_once '../models/ActivityLog.php';
require_once '../models/Notification.php';
require_once '../models/SMSNotification.php';
class UserController
{
    private $user;
    private $sms;
    private $notifier;
    private $logger;

    public function __construct($db)
    {
        $this->user = new User($db);
        $this->sms = new SMSNotification($db);
        $this->notifier = new Notification($db);
        $this->logger   = new ActivityLog($db);
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
            $actorId        = (int) $_POST['session_user_id'] ?? null;
            $actorName      = $_POST['session_username'] ?? 'An administrator';
            $actorRole      = (int) $_POST['session_role_id'] ?? null;

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

            if ($result) {
                $newUserId = (int)$this->user->getLastInsertId();

                $this->notifier->createNotification(
                    $newUserId,
                    null,
                    'account_created',
                    $newUserId,
                    'Welcome to QPILA',
                    "Hi {$first_name}, your account has been created successfully."
                );

                $this->notifier->createNotification(
                    null,
                    2,
                    'new_user_registered',
                    $newUserId,
                    'New User Registered',
                    "Admin (ID: {$actorId}): {$actorName} added user '{$userName}' (ID: {$newUserId})."
                );

                $this->logger->logActivity(
                    $actorId,
                    $actorRole,
                    'user',
                    "Created user '{$userName}' (ID: {$newUserId})",
                    'Success',
                    $newUserId,
                    ['email' => $email, 'role_id' => $user_role]
                );
                echo json_encode(["success" => $result]);
            } else {
                $this->logger->logActivity(
                    $actorId,
                    $actorRole,
                    'user',
                    "Failed to create user '{$userName}'",
                    'Failed'
                );
                echo json_encode(["success" => false, "error" => "Unable to add user."]);
            }
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // 1) Gather inputs
        $id             = (int) $_POST['userId'];
        $first_name     = htmlspecialchars($_POST['firstName']);
        $middle_name    = htmlspecialchars($_POST['middleName']);
        $last_name      = htmlspecialchars($_POST['lastName']);
        $suffix         = htmlspecialchars($_POST['suffix']);
        $userName       = htmlspecialchars($_POST['userName']);
        $email          = htmlspecialchars($_POST['email']);
        $user_role      = (int) $_POST['role'];
        $gender         = (int) $_POST['gender'];
        $maritalStatus  = (int) $_POST['maritalStatus'];
        $birthdate      = $_POST['birthdate'];
        $mobileNumber   = htmlspecialchars($_POST['mobileNumber']);
        $address        = htmlspecialchars($_POST['address']);
        $isVerified     = isset($_POST['isVerified']) ? (int) $_POST['isVerified'] : 1;
        $actorId        = (int) $_POST['session_user_id'] ?? null;
        $actorName      = $_POST['session_username'] ?? 'An administrator';
        $actorRole      = (int) $_POST['session_role_id'] ?? null;

        // 2) Load current record for diffing
        $old = $this->user->getUserById($id);

        // 3) Handle optional password change
        $hashedPassword = null;
        if (!empty($_POST['password'])) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        // 4) Handle optional profile image upload
        $profileImagePath = null;
        if (!empty($_FILES['profileImage']['name']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $targetDir   = "../uploads/";
            $ext         = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
            $newName     = uniqid('profile_', true) . '.' . $ext;
            $profileImagePath = $targetDir . $newName;

            if (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $profileImagePath)) {
                echo json_encode(["success" => false, "error" => "Error uploading profile image"]);
                return;
            }
        }

        // 5) Perform the main update
        $ok = $this->user->updateUser(
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

        // 6) Apply password/image updates if needed
        if ($ok && $hashedPassword) {
            $ok = $this->user->updatePassword($id, $hashedPassword);
        }
        if ($ok && $profileImagePath) {
            $ok = $this->user->updateProfile($id, $profileImagePath);
        }

        // 8) Build diff of changed fields
        $fieldLabels = [
            'first_name'    => 'First Name',
            'middle_name'   => 'Middle Name',
            'last_name'     => 'Last Name',
            'suffix'        => 'Suffix',
            'userName'      => 'Username',
            'email'         => 'Email Address',
            'gender'        => 'Gender',
            'maritalStatus' => 'Marital Status',
            'birthdate'     => 'Birthdate',
            'mobileNumber'  => 'Mobile Number',
            'address'       => 'Address',
            'isVerified'    => 'Verification Status',
            'user_role'     => 'Role',
            'password'      => 'Password',
            'profileImage'  => 'Profile Picture',
        ];

        $changes = [];
        foreach ($fieldLabels as $key => $label) {
            switch ($key) {
                case 'userName':
                    $new = $userName;
                    $oldVal = $old['username'] ?? null;
                    break;
                case 'email':
                    $new = $email;
                    $oldVal = $old['email'] ?? null;
                    break;
                case 'first_name':
                    $new = $first_name;
                    $oldVal = $old['first_name'] ?? null;
                    break;
                case 'middle_name':
                    $new = $middle_name;
                    $oldVal = $old['middle_name'] ?? null;
                    break;
                case 'last_name':
                    $new = $last_name;
                    $oldVal = $old['last_name'] ?? null;
                    break;
                case 'suffix':
                    $new = $suffix;
                    $oldVal = $old['suffix'] ?? null;
                    break;
                case 'gender':
                    $new = $gender;
                    $oldVal = $old['gender'] ?? null;
                    break;
                case 'maritalStatus':
                    $new = $maritalStatus;
                    $oldVal = $old['marital_status'] ?? null;
                    break;
                case 'birthdate':
                    $new = $birthdate;
                    $oldVal = $old['birthdate'] ?? null;
                    break;
                case 'mobileNumber':
                    $new = $mobileNumber;
                    $oldVal = $old['mobile_number'] ?? null;
                    break;
                case 'address':
                    $new = $address;
                    $oldVal = $old['address'] ?? null;
                    break;
                case 'isVerified':
                    $new = $isVerified;
                    $oldVal = $old['is_verified'] ?? null;
                    break;
                case 'user_role':
                    $new = $user_role;
                    $oldVal = $old['role_id'] ?? null;
                    break;
                case 'password':
                    $new = $hashedPassword ? '(updated)' : null;
                    $oldVal = $hashedPassword ? '(hidden)' : null;
                    break;
                case 'profileImage':
                    $new = $profileImagePath;
                    $oldVal = $old['profile_image_path'] ?? null;
                    break;
                default:
                    continue 2;
            }
            if ($new !== null && $new != $oldVal) {
                $changes[$label] = ['old' => $oldVal, 'new' => $new];
            }
        }

        // 9) On success: notify & log
        if ($ok) {
            // Build notification message
            if ($changes) {
                $fieldsText = implode(', ', array_keys($changes));
                $message = "Your profile was updated by {$actorName}. Changed: {$fieldsText}. If you didn't request this, please contact support.";
            } else {
                $message = "Your profile was updated by {$actorName}. If you didn't request this, please contact support.";
            }

            // Send notification
            $this->notifier->createNotification(
                $id,
                null,
                'account_updated',
                $id,
                'Profile Updated',
                $message
            );

            // Log activity
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'user',
                "Edited user '{$userName}' (ID: {$id})",
                'Success',
                $id,
                ['changes' => $changes]
            );

            echo json_encode(["success" => true]);
            return;
        }

        // 10) On failure: log it
        $this->logger->logActivity(
            $actorId,
            $actorRole,
            'user',
            "Failed to edit user '{$userName}' (ID: {$id})",
            'Failed',
            $id
        );

        echo json_encode(["success" => false, "error" => "Unable to update user."]);
    }

    // Handle Delete User Request
    public function deleteUser()
    {
        // 1) Gather actor info
        $actorId   = isset($_POST['session_user_id'])   ? (int)$_POST['session_user_id']   : null;
        $actorName = $_POST['session_username']        ?? 'An administrator';
        $actorRole = isset($_POST['session_role_id'])  ? (int)$_POST['session_role_id']   : null;

        // 2) Check for target user
        if (empty($_POST['user_id'])) {
            // Log invalid request
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'user',
                'Attempted delete with invalid user ID',
                'Failed',
                null,
                ['payload' => $_POST]
            );

            echo json_encode([
                'success' => false,
                'message' => 'Invalid user ID.'
            ]);
            return;
        }

        $targetId = (int)$_POST['user_id'];
        $target    = $this->user->getUserById($targetId);
        $targetName = $target['username'] ?? "ID {$targetId}";

        // 3) Perform deletion
        $ok = $this->user->deleteUser($targetId);

        if ($ok) {
            // 4) Notify all staff/admins (role_id = 2)
            $this->notifier->createNotification(
                null,
                2,
                'user_deleted',
                $targetId,
                'User Removed',
                "User “{$targetName}” (ID: {$targetId}) was removed by {$actorName}."
            );

            // 5) Log success
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'user',
                "Deleted user “{$targetName}” (ID: {$targetId})",
                'Success',
                $targetId
            );

            echo json_encode(['success' => true]);
        } else {
            // 6) Log failure
            $this->logger->logActivity(
                $actorId,
                $actorRole,
                'user',
                "Failed to delete user “{$targetName}” (ID: {$targetId})",
                'Failed',
                $targetId
            );

            echo json_encode([
                'success' => false,
                'message' => 'Unable to delete user.'
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

            // a) Notify the new user
            $actorId   = $this->user->getLastInsertId();

            // a) Notify the new user
            $this->notifier->createNotification(
                $actorId,
                null,
                'welcome',
                $actorId,
                'Welcome to QPILA',
                "Hi {$firstName}, your account has been created! Please verify your email to get started."
            );

            // b) Notify staff/admins of new registration
            $this->notifier->createNotification(
                null,
                2,
                'new_registration',
                $actorId,
                'New User Registered',
                "User '{$userName}' (ID {$actorId}) just signed up."
            );

            $this->logger->logActivity(
                $actorId,
                2,
                'user',
                "User '{$userName}' registered",
                'Success',
                $actorId,
                ['email' => $email]
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

        $this->notifier->createNotification(
            $userId,
            null,
            'password_reset',
            $userId,
            'Password Reset',
            "Your password was reset successfully and sent to your phone."
        );

        $this->logger->logActivity(
            $userId,
            2,
            'auth',
            'Password reset via SMS',
            'Success',
            $userId,
            ['via' => 'SMS']
        );


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

            // 3) Notify user
            $this->notifier->createNotification(
                $userId,
                null,
                'password_changed',
                $userId,
                'Password Changed',
                'Your password was changed successfully. If this wasn’t you, please contact support.'
            );

            // 4) Log success
            $this->logger->logActivity(
                $userId,
                $_SESSION['role_id'],
                'auth',
                'User changed own password',
                'Success',
                $userId
            );

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
