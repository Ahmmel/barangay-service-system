<?php
session_start();
include '../config/database.php';
include '../models/User.php';
include '../models/Admin.php';

$database = new Database();
$db = $database->getConnection();

// Helper function to set authentication cookies securely
function setAuthCookies($email, $role)
{
    $cookieExpiry = time() + (86400 * 30);  // 30 days expiry time
    setcookie('user_email', $email, $cookieExpiry, '/', '', isset($_SERVER['HTTPS']), true);
    setcookie('user_role', $role, $cookieExpiry, '/', '', isset($_SERVER['HTTPS']), true);
}

// Helper function to handle AJAX responses
function sendAjaxResponse($success, $message)
{
    echo json_encode(array("success" => $success, "message" => $message));
    exit();
}

// Validate inputs
function validateInputs($email, $password)
{
    if (empty($email) || empty($password)) {
        return "Email/Username and password are required.";
    }
    return null; // No errors
}

// Handle user authentication
function authenticateUser($email, $password, $db, $remember)
{
    // Check if user is an Admin
    $adminModel = new Admin($db);
    $admin = $adminModel->login($email, $password);

    if ($admin) {
        // Admin login successful
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_role'] = 'admin';
        if ($remember) {
            setAuthCookies($email, 'admin');
        }
        return ['role' => 'admin', 'redirect' => '../admin/dashboard.php'];
    }

    // Check if user is a regular user or staff
    $userModel = new User($db);
    $user = $userModel->login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = ($user['role_id'] != 2) ? 'staff' : 'user';
        if ($remember) {
            setAuthCookies($email, ($user['role_id'] != 2) ? 'staff' : 'user');
        }

        // Return user role and redirection based on role
        if ($_SESSION['user_role'] == 'staff') {
            return ['role' => 'staff', 'redirect' => '../admin/dashboard.php'];
        } else {
            return ['role' => 'user', 'redirect' => null];
        }
    }

    return null; // No valid user found
}

// Main logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $remember = isset($_POST['remember']) ? true : false;

    // Validate inputs
    $error_message = validateInputs($email, $password);
    if ($error_message) {
        // Handle error for AJAX request
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            sendAjaxResponse(false, $error_message);
        } else {
            $_SESSION['error'] = $error_message;
            header("Location: ../admin");
            exit();
        }
    }

    // Attempt authentication
    $authResult = authenticateUser($email, $password, $db, $remember);
    if ($authResult) {
        if ($authResult['redirect']) {
            header("Location: " . $authResult['redirect']);
            exit();
        } else {
            sendAjaxResponse(true, "Login successful");
        }
    }

    // If no matching user or admin found
    $error_message = "Invalid email or password.";
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        sendAjaxResponse(false, $error_message);
    } else {
        $_SESSION['error'] = $error_message;
        header("Location: ../admin");
        exit();
    }
}
