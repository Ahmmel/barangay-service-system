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

$isAdmin = $_SESSION['user_role'] !== 'admin' ? true : false;
$database = new Database();
$db = $database->getConnection();
$transaction = new Transaction($db);
$transactions = $transaction->getAllTransactions();
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
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button
                id="sidebarToggleTop"
                class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
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
                        <span class="badge badge-danger badge-counter">3+</span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    <div
                        class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="alertsDropdown">
                        <!-- Add alert content here -->
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
                        <!-- Add user actions here -->
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
                            echo "<tr id='transactionData_{$transaction['transaction_id']}'>
                                    <td>{$transaction['transaction_code']}</td>
                                    <td>{$fullname}</td>
                                    <td>{$transaction['services']}</td>
                                    <td>{$transaction['created_at']}</td>
                                    <td>{$transaction['updated_at']}</td>
                                    <td>{$transaction['date_closed']}</td>
                                    <td>{$transaction['status']}</td>
                                    <td>
                                        <button class='btn btn-info btn-sm' style='display: " . ($transaction['status'] != 'Closed' ? 'block' : 'none') . ";' onclick='openUpdateTransactionModal({$transaction['transaction_id']})'>Update Status</button>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No transactions found</td></tr>";
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

<?php
// Include footer template
include_once '../views/templates/admin_footer.php';
?>