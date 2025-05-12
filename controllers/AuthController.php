<?php
ob_start();
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Admin.php';

$database = new Database();
$db = $database->getConnection();

function respond(bool $success, string $message, ?string $redirect = null): void
{
    if (ob_get_length()) ob_clean();
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(compact('success', 'message', 'redirect'));
    } else {
        if ($success && $redirect) {
            header("Location: $redirect");
        } else {
            $_SESSION['error'] = $message;
            header("Location: ../admin");
        }
    }
    exit;
}

function remember(string $identifier, string $role): void
{
    $expiry = time() + 86400 * 30;
    $secure = !empty($_SERVER['HTTPS']);
    setcookie('user_identifier', $identifier, $expiry, '/', '', $secure, true);
    setcookie('user_role',       $role,       $expiry, '/', '', $secure, true);
}

$identifier = trim($_POST['identifier'] ?? '');
$password   = trim($_POST['password']   ?? '');
$remember   = !empty($_POST['remember']);
$isAdmin    = !empty($_POST['is_admin']);

if ($identifier === '' || $password === '') {
    respond(false, 'Identifier and password are required.');
}

if ($isAdmin) {
    // 1) Try true admins
    $adminModel = new Admin($db);
    if ($admin = $adminModel->login($identifier, $password)) {
        $_SESSION['user_id']   = $admin['id'];
        $_SESSION['user_role'] = 'admin';
        $_SESSION['username']  = $admin['username'];
        $remember && remember($identifier, 'admin');
        respond(true, 'Admin login successful.', '../admin/dashboard.php');
    }

    // 2) Try “staff” from users table
    $userModel = new User($db);
    if ($user = $userModel->login($identifier, $password)) {
        // Assuming role_id = 2 means “regular user”; any other value = staff
        if ($user['role_id'] != 2) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_role'] = 'staff';
            $_SESSION['username']  = $user['username'];
            $remember && remember($identifier, 'staff');
            respond(true, 'Staff login successful.', '../admin/dashboard.php');
        }
    }

    // Neither admin nor staff matched:
    respond(false, 'Invalid admin/staff credentials.');
}

// NON-admin side: only regular users
$userModel = new User($db);
if ($user = $userModel->login($identifier, $password)) {
    if ($user['role_id'] == 2) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_role'] = 'user';
        $_SESSION['username']  = $user['username'];
        $remember && remember($identifier, 'user');
        respond(true, 'Login successful.', null);
    }
}

respond(false, 'Invalid user credentials.');
