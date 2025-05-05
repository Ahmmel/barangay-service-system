<?php
// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../models/User.php';
include_once '../models/ActivityLog.php';

// Initialize User object
$user = new User($db);
// Prepare the base query for total counts
$queries = [
    'totalUsers' => "SELECT COUNT(*) FROM users",
    'totalServices' => "SELECT COUNT(*) FROM services",
    'totalTransactions' => "SELECT COUNT(*) FROM transactions",
    'totalMonthlyTransactions' => "SELECT COUNT(*) FROM transactions WHERE MONTH(created_at) = MONTH(CURRENT_DATE())",
    'totalAnnualTransactions' => "SELECT COUNT(*) FROM transactions WHERE YEAR(created_at) = YEAR(CURRENT_DATE())"
];

// Execute the queries for general counts
$totalCounts = [];
foreach ($queries as $key => $query) {
    $stmt = $db->query($query);
    $totalCounts[$key] = $stmt->fetchColumn();
}

// If the user is staff, modify the transaction counts
if ($_SESSION['user_role'] == 'staff') {
    $staffQueries = [
        'totalTransactions' => "SELECT COUNT(*) FROM transactions WHERE handled_by_staff_id = :staff_id",
        'totalMonthlyTransactions' => "SELECT COUNT(*) FROM transactions WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND handled_by_staff_id = :staff_id",
        'totalAnnualTransactions' => "SELECT COUNT(*) FROM transactions WHERE YEAR(created_at) = YEAR(CURRENT_DATE()) AND handled_by_staff_id = :staff_id"
    ];

    // Prepare and execute staff-specific queries
    foreach ($staffQueries as $key => $query) {
        $stmt = $db->prepare($query);
        $stmt->bindParam(':staff_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $totalCounts[$key] = $stmt->fetchColumn();
    }
}

$totalUsers = $totalCounts['totalUsers'];
$totalServices = $totalCounts['totalServices'];
$totalTransactions = $totalCounts['totalTransactions'];
$totalMonthlyTransactions = $totalCounts['totalMonthlyTransactions'];
$totalAnnualTransactions = $totalCounts['totalAnnualTransactions'];

//get all activity logs
$activityLog = new ActivityLog($db);
$activityLogs = $activityLog->getRecentLogs();

if (!$isAdmin) {
    $activityLogs = $activityLog->getRecentLogsByStaffId($currentSessionId);
}
?>
<!-- Sidebar -->
<?php include('../views/templates/side_bar.php'); ?>
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <?php include('../views/templates/top_bar.php') ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->

            <!-- Content Row -->
            <?php
            // Include the appropriate dashboard based on user role
            if ($_SESSION['user_role'] == 'admin') {
                include('../views/templates/admin_dashboard.php');  // Admin dashboard
            } elseif ($_SESSION['user_role'] == 'staff') {
                include('../views/templates/staff_dashboard.php');  // Staff dashboard
            }
            ?>
            <!-- Content Row -->

            <!-- Activity Logs Row -->
            <div class="row">
                <div class="card-body">
                    <!-- Table for Activity Logs -->
                    <table
                        id="activityLogTable"
                        class="table table-bordered table-striped" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Log ID</th> <!-- from a.id -->
                                <th>User ID</th> <!-- from a.user_id -->
                                <th>Full Name</th> <!-- from CONCAT(u.first_name, ' ', u.last_name) -->
                                <th>Activity</th> <!-- from a.activity -->
                                <th>Status</th> <!-- from a.status -->
                                <th>Timestamp</th> <!-- from a.created_at -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activityLogs as $log): ?>
                                <tr>
                                    <td><?= htmlspecialchars($log['id']) ?></td>
                                    <td><?= htmlspecialchars($log['user_id']) ?></td>
                                    <td><?= htmlspecialchars($log['fullname']) ?></td>
                                    <td><?= htmlspecialchars($log['activity']) ?></td>
                                    <td><?= date('F j, Y h:i A', strtotime($log['created_at'])) ?></td>
                                    <td>
                                        <?php
                                        $status = $log['status'];
                                        $badgeClass = match ($status) {
                                            'Pending' => 'badge-warning',
                                            'Failed' => 'badge-danger',
                                            default => 'badge-success'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Activity Logs Row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php include('../views/templates/footer.php'); ?>
    <!-- End of Footer -->
</div>
<!-- End of Content Wrapper -->
<?php
// Include footer template
include_once '../views/templates/admin_footer.php';
?>