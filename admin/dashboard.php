<?php
// start the session
session_start();

// Initialization
include_once '../views/templates/admin_header.php';
include_once '../models/User.php';
include_once '../models/ActivityLog.php';

$_SESSION["page_title"] = "Dashboard";

$user = new User($db);
$activityLog = new ActivityLog($db);

// Define default queries
$queries = [
    'totalUsers' => "SELECT COUNT(*) FROM users",
    'totalServices' => "SELECT COUNT(*) FROM services",
    'totalTransactions' => "SELECT COUNT(*) FROM transactions",
    'totalMonthlyTransactions' => "SELECT COUNT(*) FROM transactions WHERE MONTH(created_at) = MONTH(CURRENT_DATE())",
    'totalAnnualTransactions' => "SELECT COUNT(*) FROM transactions WHERE YEAR(created_at) = YEAR(CURRENT_DATE())",
    'overAllRatings' => "SELECT AVG(rating) FROM transactions WHERE rating IS NOT NULL"
];

// Prepare count data
$totalCounts = [];

foreach ($queries as $key => $sql) {
    $stmt = $db->query($sql);
    $totalCounts[$key] = $stmt->fetchColumn();
}

// Override with staff-specific queries
if ($_SESSION['user_role'] === 'staff') {
    $staffId = $_SESSION['user_id'];

    $staffQueries = [
        'totalTransactions' => "SELECT COUNT(*) FROM transactions WHERE handled_by_staff_id = :staff_id",
        'totalMonthlyTransactions' => "SELECT COUNT(*) FROM transactions WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND handled_by_staff_id = :staff_id",
        'totalAnnualTransactions' => "SELECT COUNT(*) FROM transactions WHERE YEAR(created_at) = YEAR(CURRENT_DATE()) AND handled_by_staff_id = :staff_id"
    ];

    foreach ($staffQueries as $key => $sql) {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':staff_id', $staffId, PDO::PARAM_INT);
        $stmt->execute();
        $totalCounts[$key] = $stmt->fetchColumn();
    }
}

// Assign to readable vars
extract($totalCounts);

$overallRatingPerTransactions = $overAllRatings ? number_format($overAllRatings, 2) : 0;

// Fetch logs
$isAdmin = ($_SESSION['user_role'] === 'admin');
$currentSessionId = $_SESSION['user_id'];

$activityLogs = $isAdmin
    ? $activityLog->getRecentLogs()
    : $activityLog->getRecentLogsByStaffId($currentSessionId);

?>

<!-- Sidebar -->
<?php include('../views/templates/side_bar.php'); ?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include('../views/templates/top_bar.php'); ?>

        <div class="container-fluid">
            <!-- Role-Specific Dashboard -->
            <?php
            $dashboardFile = $_SESSION['user_role'] === 'admin'
                ? 'admin_dashboard.php'
                : 'staff_dashboard.php';
            include("../views/templates/$dashboardFile");
            ?>

            <!-- Activity Logs -->
            <div class="row mt-4">
                <div class="card-body">
                    <table id="activityLogTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Log ID</th>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Activity</th>
                                <th>Status</th>
                                <th>Timestamp</th>
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
                                        $status = htmlspecialchars($log['status']);
                                        $badgeClass = match ($status) {
                                            'Pending' => 'badge-warning',
                                            'Failed' => 'badge-danger',
                                            default => 'badge-success'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('../views/templates/footer.php'); ?>
</div>

<?php include_once '../views/templates/admin_footer.php'; ?>