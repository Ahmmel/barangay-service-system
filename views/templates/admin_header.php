<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../config/database.php';
include_once '../models/Notification.php';

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ["admin", "staff"])) {
    // Redirect to login page if not an admin
    header("Location: ../admin/login.php");
    exit();
}

// Create database connection
$database = new Database();
$db = $database->getConnection();

$isAdmin = $_SESSION['user_role'] == 'admin' ? 1 : 0;
$currentSessionId = $_SESSION['user_id'];
$sessionUsername = $_SESSION['username'];

// Get notifications
$notification = new Notification($db);
$notifications = $notification->getUnreadNotifications($_SESSION['user_id'], $_SESSION['user_role']);
$notificationCount = count($notifications);

$currentPage = basename($_SERVER['PHP_SELF']);

switch ($currentPage) {
    case 'dashboard.php':
        $_SESSION['page_title'] = 'Dashboard';
        break;
    case 'users.php':
        $_SESSION['page_title'] = 'User Management';
        break;
    case 'services.php':
        $_SESSION['page_title'] = 'Services';
        break;
    case 'requirements.php':
        $_SESSION['page_title'] = 'Service Requirements';
        break;
    case 'transactions.php':
        $_SESSION['page_title'] = 'Transactions';
        break;
    case 'queue-sched.php':
        $_SESSION['page_title'] = 'Scheduled Queue';
        break;
    case 'queue-walkin.php':
        $_SESSION['page_title'] = 'Walk-in Queue';
        break;
    case 'system-settings.php':
        $_SESSION['page_title'] = 'System Settings';
        break;
    default:
        $_SESSION['page_title'] = 'Admin';
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Admin - <?php echo $_SESSION["page_title"] ?? 'Dashboard'; ?></title>
    <link rel="icon" href="../images/qpila-logo-favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">

    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/admin-sidebar.css" rel="stylesheet">
    <link href="../vendor/font/font-awesome.css" rel="stylesheet">
    <link href="../vendor/font/googleapis.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

    <!-- NProgress CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />

    <!-- NProgress JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <?php
    if ($_SESSION["page_title"] == "User") {
        echo '<link href="../css/common-pages.css" rel="stylesheet">';
    }
    ?>
    <style>
        #nprogress .bar {
            background: #28a745 !important;
            /* green like success */
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">