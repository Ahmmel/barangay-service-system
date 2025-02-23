<?php
session_start();
include '../config/database.php';
include '../models/User.php';
include '../models/Admin.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
    } else {
        // Function to set cookies securely
        function setAuthCookies($email, $role)
        {
            setcookie('user_email', $email, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'domain' => '',
                'secure' => false, // Change to `true` if using HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            setcookie('user_role', $role, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'domain' => '',
                'secure' => false, // Change to `true` if using HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        // Check Admins First
        $adminModel = new Admin($db);
        $admin = $adminModel->login($email, $password);
        if ($admin) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_role'] = 'admin';

            if ($remember) {
                setAuthCookies($email, 'admin');
            }

            header("Location: ../admin/dashboard.php");
            exit();
        }

        // Check Normal Users
        $userModel = new User($db);
        $user = $userModel->login($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = 'user';

            if ($remember) {
                setAuthCookies($email, 'user');
            }

            header("Location: ../user/dashboard.php");
            exit();
        }

        $_SESSION['error'] = "Invalid email or password.";
    }
    header("Location: ../admin");
    exit();
}
