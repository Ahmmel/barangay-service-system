<?php
// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../config/database.php';
include_once '../models/User.php';

// Start the session
session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ["admin", "staff"])) {
    // Redirect to login page if not an admin
    header("Location: ../admin/login.php");
    exit();
}

// Create database connection
$database = new Database();
$db = $database->getConnection();

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

?>
<!-- Sidebar -->
<ul
    class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
    id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a
        class="sidebar-brand d-flex align-items-center justify-content-center p-3"
        href="admin_index.html">
        <div class="sidebar-brand-icon">
            <img
                src="../images/brand_only_white.png"
                alt="QPila Logo"
                style="width: 35px" />
        </div>
        <div class="sidebar-brand-text mx-2">QPila</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - User -->
    <li class="nav-item">
        <a class="nav-link" href="users.php">
            <i class="fas fa-fw fa-user"></i>
            <span>User</span>
        </a>
    </li>

    <!-- Nav Item - Services -->
    <li class="nav-item">
        <a class="nav-link" href="services.php">
            <i class="fas fa-fw fa-suitcase"></i>
            <span>Services</span>
        </a>
    </li>

    <!-- Nav Item - Requirement -->
    <li class="nav-item">
        <a class="nav-link" href="requirements.php">
            <i class="fas fa-fw fa-suitcase"></i>
            <span>Service Requirements</span>
        </a>
    </li>

    <!-- Transaction -->
    <li class="nav-item">
        <a class="nav-link" href="transactions.php">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Transactions</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block toggle-sidebar-divider" />

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav
            class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button
                id="sidebarToggleTop"
                class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="searchDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                </li>

                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="alertsDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter">3+</span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    <div
                        class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">Staff Alerts Center</h6>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">February 15, 2025</div>
                                <span class="font-weight-bold">New appointment scheduled for <strong>John Doe</strong> tomorrow at 9:00 AM.</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-user-check text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">February 15, 2025</div>
                                <span class="font-weight-bold">Queue number <strong>#23</strong> is now up for <strong>Maria Santos</strong>.</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">February 15, 2025</div>
                                <span class="font-weight-bold">Appointment Alert:</span> <strong>John Doe</strong> has missed their scheduled time.
                            </div>
                        </a>
                        <a
                            class="dropdown-item text-center small text-gray-500"
                            href="#">Show All Alerts</a>
                    </div>

                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="userDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">John Doe</span>
                        <img
                            class="img-profile rounded-circle"
                            src="../images/undraw_profile.svg" />
                    </a>
                    <!-- Dropdown - User Information -->
                    <div
                        class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            Activity Log
                        </a>
                        <div class="dropdown-divider"></div>
                        <a
                            class="dropdown-item"
                            href="#"
                            data-toggle="modal"
                            data-target="#logoutModal">
                            <i
                                class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->

            <!-- Content Row -->
            <?php
            // Include the appropriate dashboard based on user role
            if ($_SESSION['user_role'] == 'admin') {
                include('../views/templates/admin_dashboard.php');  // Admin dashboard
            } elseif ($_SESSION['user_role'] == 'staff') {
                include('../views/templates//templates/taff_dashboard.php');  // Staff dashboard
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
                                <th>#</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Timestamp</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>Logged in</td>
                                <td>2025-02-04 10:15:32</td>
                                <td><span class="badge badge-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Alice Smith</td>
                                <td>Updated Profile</td>
                                <td>2025-02-04 11:02:10</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Bob Johnson</td>
                                <td>Changed Password</td>
                                <td>2025-02-04 12:30:01</td>
                                <td><span class="badge badge-danger">Failed</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Charlie Brown</td>
                                <td>Logged out</td>
                                <td>2025-02-04 13:45:55</td>
                                <td><span class="badge badge-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>John Doe</td>
                                <td>Updated Profile</td>
                                <td>2025-02-04 14:05:40</td>
                                <td><span class="badge badge-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Alice Smith</td>
                                <td>Logged in</td>
                                <td>2025-02-04 14:30:22</td>
                                <td><span class="badge badge-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Bob Johnson</td>
                                <td>Logged out</td>
                                <td>2025-02-04 15:10:56</td>
                                <td><span class="badge badge-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Charlie Brown</td>
                                <td>Updated Password</td>
                                <td>2025-02-04 15:45:30</td>
                                <td><span class="badge badge-danger">Failed</span></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>John Doe</td>
                                <td>Changed Password</td>
                                <td>2025-02-04 16:25:18</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Alice Smith</td>
                                <td>Logged out</td>
                                <td>2025-02-04 17:00:05</td>
                                <td><span class="badge badge-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Bob Johnson</td>
                                <td>Logged in</td>
                                <td>2025-02-04 17:35:44</td>
                                <td><span class="badge badge-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Charlie Brown</td>
                                <td>Updated Profile</td>
                                <td>2025-02-04 18:12:50</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
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
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2021</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
</div>
<!-- End of Content Wrapper -->
<?php
// Include footer template
include_once '../views/templates/admin_footer.php';
?>