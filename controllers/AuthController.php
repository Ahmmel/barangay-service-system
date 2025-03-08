<?php
session_start();
include '../config/database.php';
include '../models/User.php';
include '../models/Admin.php';

$database = new Database();
$db = $database->getConnection();

// Ensure that the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize email and password inputs
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $remember = isset($_POST['remember']) ? true : false;

    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
        header("Location: ../admin");
        exit();
    }

    // Function to set authentication cookies securely
    function setAuthCookies($email, $role)
    {
        $cookieExpiry = time() + (86400 * 30);  // 30 days expiry time
        setcookie('user_email', $email, $cookieExpiry, '/', '', isset($_SERVER['HTTPS']), true);
        setcookie('user_role', $role, $cookieExpiry, '/', '', isset($_SERVER['HTTPS']), true);
    }

    // Check if user is an Admin
    $adminModel = new Admin($db);
    $admin = $adminModel->login($email, $password);

    if ($admin) {
        // Set session variables and redirect to admin dashboard
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_role'] = 'admin';

        if ($remember) {
            setAuthCookies($email, 'admin');
        }

        header("Location: ../admin/dashboard.php");
        exit();
    }

    // Check if user is a regular user or staff
    $userModel = new User($db);
    $user = $userModel->login($email, $password);

    if ($user) {
        // Store user ID and role in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = ($user['role_id'] != 2) ? 'staff' : 'user';

        // Set authentication cookies if 'remember me' is checked
        if ($remember) {
            setAuthCookies($email, $user['role_id'] != 2 ? 'staff' : 'user');
        }

        // Redirect based on role
        if ($_SESSION['user_role'] == 'staff') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit();
    }

    // If no matching user or admin found
    $_SESSION['error'] = "Invalid email or password.";
    header("Location: ../admin");
    exit();
}
