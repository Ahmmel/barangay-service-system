<?php
// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../config/database.php';
include_once '../models/Transaction.php';

// start the session
session_start();
$_SESSION["page_title"] = "Transactions";

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ["admin", "staff"])) {
    // Redirect to login page if not an admin
    header("Location: ../admin/login.php");
    exit();
}

$isAdmin = $_SESSION['user_role'] == 'admin' ? true : false;
$currentSessionId = $_SESSION['user_id'];

$database = new Database();
$db = $database->getConnection();
$transaction = new Transaction($db);
$transactions = $isAdmin ? $transaction->getAllTransactions() : $transaction->getAllStaffTransactions($_SESSION['user_id']);
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
    <li class="nav-item">
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

    <?php if ($isAdmin) : ?>
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
    <?php else : ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#queueManagement" aria-expanded="false" aria-controls="queueManagement">
                <i class="fas fa-fw fa-tasks"></i>
                <span>Manage Queue</span>
            </a>
            <div id="queueManagement" class="collapse" aria-labelledby="headingUtilities">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Queue Types:</h6>
                    <a class="collapse-item active" href="queue-sched.php">Scheduled Queue</a>
                    <a class="collapse-item" href="queue-walkin.php">Walk-in Queue</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Nav Item - Transactions -->
    <li class="nav-item active">
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
            <!-- Button Group: Add and Search Transaction -->
            <div class="d-flex mb-3">
                <button type="button" class="btn btn-success mr-2" onclick="openAddTransactionModal()">
                    <i class="fas fa-plus"></i> Add Transaction
                </button>

                <!-- Search Transaction Button -->
                <button type="button" class="btn btn-primary" onclick="openSearchTransactionModal()">
                    <i class="fas fa-search"></i> Search Transaction
                </button>
            </div>

            <!-- Table for Displaying Transactions -->
            <table class="table table-bordered table-striped" id="transactionTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Transaction Code</th>
                        <th>Applicant Name</th>
                        <th>Service Details</th>
                        <th>Date Requested</th>
                        <th>Date Last Update</th>
                        <th>Date Closed</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamically Generated Transaction Data from the Database -->
                    <?php
                    if ($transactions) {
                        foreach ($transactions as $transaction) {
                            $fullname = trim($transaction['first_name'] . ' ' . $transaction['last_name'] . ' ' . ($transaction['middle_name'] ?? '') . ' ' . ($transaction['suffix'] ?? ''));
                            $services = explode(',', $transaction['services']);
                            $servicesList = '<ul class="bullet-list">';
                            foreach ($services as $service) {
                                $servicesList .= '<li>' . htmlspecialchars(trim($service)) . '</li>';
                            }
                            $servicesList .= '</ul>';
                            echo "<tr id='transactionData_{$transaction['transaction_id']}'>
                                    <td>{$transaction['transaction_code']}</td>
                                    <td>{$fullname}</td>
                                    <td>$servicesList</td>
                                    <td>{$transaction['created_at']}</td>
                                    <td>{$transaction['updated_at']}</td>
                                    <td>{$transaction['date_closed']}</td>
                                    <td>{$transaction['status']}</td>
                                    <td>
                                        <button class='btn btn-info btn-sm' style='display: " . (!in_array($transaction['status'], ['Closed', 'Cancelled']) ? 'block' : 'none') . ";' onclick='openUpdateTransactionModal({$transaction['transaction_id']})'>Update Status</button>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No transactions found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; QPILA <?php echo date("Y") ?></span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
</div>
<!-- End of Content Wrapper -->

<script>
    const isTransactionPage = true;
</script>

<?php
// Include footer template
include_once '../views/templates/admin_footer.php';
?>