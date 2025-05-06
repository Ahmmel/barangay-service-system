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
function sendAjaxResponse($success, $message, $redirect = null)
{
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "redirect" => $redirect
    ]);
    exit();
}

// Validate inputs
function validateInputs($email, $password)
{
    if (empty($email) || empty($password)) {
        return "Email/Username and password are required.";
    }
    return null;
}

// Handle user authentication
function authenticateUser($email, $password, $db, $remember)
{
    $adminModel = new Admin($db);
    $admin = $adminModel->login($email, $password);
    if ($admin) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_role'] = 'admin';
        $_SESSION['username'] = $admin['username'];
        if ($remember) {
            setAuthCookies($email, 'admin');
        }
        return ['role' => 'admin', 'redirect' => '../admin/dashboard.php'];
    }

    $userModel = new User($db);
    $user = $userModel->login($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = ($user['role_id'] != 2) ? 'staff' : 'user';
        $_SESSION['username'] = $user['username'];
        if ($remember) {
            setAuthCookies($email, $_SESSION['user_role']);
        }

        if ($_SESSION['user_role'] === 'staff') {
            return ['role' => 'staff', 'redirect' => '../admin/dashboard.php'];
        } else {
            return ['role' => 'user', 'redirect' => null];
        }
    }

    return null;
}

// Main logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    $error_message = validateInputs($email, $password);
    if ($error_message) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            sendAjaxResponse(false, $error_message);
        } else {
            $_SESSION['error'] = $error_message;
            header("Location: ../admin");
            exit();
        }
    }

    $authResult = authenticateUser($email, $password, $db, $remember);
    if ($authResult) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            sendAjaxResponse(true, "Login successful", $authResult['redirect']);
        } else {
            header("Location: " . $authResult['redirect']);
            exit();
        }
    }

    $error_message = "Invalid email or password.";
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        sendAjaxResponse(false, $error_message);
    } else {
        $_SESSION['error'] = $error_message;
        header("Location: ../admin");
        exit();
    }
}
