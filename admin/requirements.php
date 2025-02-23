<?php
// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../config/database.php';
include_once '../models/Requirement.php';

// start the session
session_start();
$_SESSION["page_title"] = "Service Requirements";

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ["admin", "staff"])) {
    // Redirect to login page if not an admin
    header("Location: ../admin/login.php");
    exit();
}

$isAdmin = $_SESSION['user_role'] !== 'admin' ? true : false;
$database = new Database();
$db = $database->getConnection();
$requirement = new Requirement($db);
$requirements = $requirement->getAllRequirements();
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

    <!-- Nav Item - Services -->
    <li class="nav-item">
        <a class="nav-link" href="services.php">
            <i class="fas fa-fw fa-suitcase"></i>
            <span>Services</span>
        </a>
    </li>

    <!-- Nav Item - Requirement -->
    <li class="nav-item active">
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

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">John Doe</span>
                        <img class="img-profile rounded-circle" src="../images/undraw_profile.svg" />
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
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
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <button type="button" class="btn btn-success mb-3" onclick="openAddRequirementModal()">
                <i class="fas fa-plus"></i> Add Requirement
            </button>

            <table class="table table-bordered table-striped" id="requirementTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Service ID</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($requirements) {
                        foreach ($requirements as $requirement) {
                            echo "<tr id='requirementData_{$requirement['id']}'>
                                    <td>{$requirement['id']}</td>
                                    <td>{$requirement['service_id']}</td>
                                    <td>{$requirement['description']}</td>
                                    <td>
                                        <button class='btn btn-info btn-sm' onclick='openEditRequirementModal({$requirement['id']})'>Edit</button>
                                        <button class='btn btn-danger btn-sm deleteRequirement' data-id='{$requirement['id']}'>Delete</button>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No requirements found</td></tr>";
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

<!-- Add Requirement Modal -->
<div class="modal" id="addRequirementModal" tabindex="-1" role="dialog">
    <!-- Modal content similar to other modals -->
</div>
