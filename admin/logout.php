<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ActivityLog.php';

// 1) Capture actor info from session
$actorId   = $_SESSION['user_id']   ?? null;
$actorRole = $_SESSION['role_id']   ?? null;
$actorName = $_SESSION['username']  ?? 'Unknown';

// 2) Initialize DB & logger
$database = new Database();
$db       = $database->getConnection();
$logger   = new ActivityLog($db);

// 3) Write the logout activity
$logger->logActivity(
    $actorId,
    $actorRole,
    'auth',
    "User '{$actorName}' (ID: {$actorId}) logged out",
    'Success',
    $actorId
);

// 4) Clear cookies and session
setcookie('user_email', '', time() - 3600, "/");
setcookie('user_role',  '', time() - 3600, "/");
session_destroy();

// 5) Redirect to login
header("Location: ../admin/login.php");
exit();
