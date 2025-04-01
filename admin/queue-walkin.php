<?php
// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../config/database.php';
include_once '../models/Service.php';

// start the session
session_start();
$_SESSION["page_title"] = "Queue";

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ["admin", "staff"])) {
    // Redirect to login page if not an admin
    header("Location: ../admin/login.php");
    exit();
}

$isAdmin = $_SESSION['user_role'] !== 'admin' ? true : false;
$database = new Database();
$db = $database->getConnection();
$service = new Service($db);
$services = $service->getServices();
?>
<style>
    /* Primary Colors */
    :root {
        --primary-black: #251f21;
        --primary-white: #f1f0ef;
        --primary-brown: #bc9a8e;
    }

    .queue-item {
        font-size: 16px;
        font-weight: bold;
        padding: 15px;
        background: var(--primary-white);
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background 0.3s ease, transform 0.3s ease;
        margin-bottom: 10px;
        height: 74px;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--primary-black);
    }

    .queue-item:hover {
        background: #e1d9e5;
        transform: scale(1.02);
    }

    .queue-item .transaction-code {
        color: var(--primary-brown);
    }

    .queue-item .name {
        color: var(--primary-brown);
    }

    .current-serving {
        background-color: var(--primary-white);
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: center;
        height: 400px;
        max-height: 100%;
    }

    .current-number {
        font-size: 70px;
        /* Increased size for large number */
        font-weight: bold;
        color: var(--primary-brown);
    }

    .timer {
        font-size: 48px;
        /* Larger timer font */
        color: var(--primary-brown);
    }

    .btn {
        width: 100%;
        padding: 15px;
        font-size: 18px;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin-bottom: 15px;
    }

    .btn-secondary {
        background: var(--primary-brown);
        color: var(--primary-white);
        border: none;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary:hover {
        background: #a87f6f;
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background: var(--primary-brown);
        color: var(--primary-white);
        border: none;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover {
        background: #a87f6f;
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn:active {
        transform: translateY(2px);
        box-shadow: none;
    }

    .container-fluid {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .queue-item-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
    }

    .queue-item-container::-webkit-scrollbar {
        display: none;
    }

    .clock {
        font-size: 30px;
        /* Large clock font size */
        font-weight: bold;
        color: var(--primary-black);
        margin-bottom: 20px;
        text-align: center;
        font-family: "Courier New", monospace;
        /* Digital clock font style */
    }

    h4 {
        font-weight: 700;
        color: var(--primary-black);
        margin-bottom: 20px;
    }

    .row {
        display: flex;
        justify-content: space-between;
    }

    .col-lg-8,
    .col-md-8 {
        margin-top: 20px;
        flex: 0 0 100%;
    }

    .col-lg-4,
    .col-md-4 {
        margin-top: 20px;
        flex: 0 0 100%;
    }
</style>
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

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#queueManagement" aria-expanded="false" aria-controls="queueManagement">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Manage Queue</span>
        </a>
        <div id="queueManagement" class="collapse" aria-labelledby="headingUtilities">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Queue Types:</h6>
                <a class="collapse-item" href="queue-sched.php">Scheduled Queue</a>
                <a class="collapse-item active" href="queue-walkin.php">Walk-in Queue</a>
            </div>
        </div>
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
            <div class="container-fluid m-2">
                <div class="clock" id="clock">00:00:00</div>
                <!-- Walk-in Queue Section -->
                <div class="mb-4">
                    <h4>Walk-in Queue</h4>
                    <div class="row">
                        <!-- Walk-in Queue List -->
                        <div
                            class="col-lg-8 col-md-8 queue-item-container"
                            id="walkinQueueList">
                            <div class="queue-item" id="walkin-1">
                                <span class="transaction-code">TRX-06</span>
                                <span class="name">MARTIN E.</span>
                                <i
                                    class="fas fa-check-circle"
                                    style="color: var(--primary-brown)"></i>
                            </div>
                            <div class="queue-item" id="walkin-2">
                                <span class="transaction-code">TRX-07</span>
                                <span class="name">LEE C.</span>
                                <i
                                    class="fas fa-check-circle"
                                    style="color: var(--primary-brown)"></i>
                            </div>
                            <div class="queue-item" id="walkin-3">
                                <span class="transaction-code">TRX-08</span>
                                <span class="name">HARRIS D.</span>
                                <i
                                    class="fas fa-check-circle"
                                    style="color: var(--primary-brown)"></i>
                            </div>
                            <div class="queue-item" id="walkin-4">
                                <span class="transaction-code">TRX-09</span>
                                <span class="name">HARRY D.</span>
                                <i
                                    class="fas fa-check-circle"
                                    style="color: var(--primary-brown)"></i>
                            </div>
                        </div>

                        <!-- Current Serving for Walk-in Queue -->
                        <div class="col-lg-4 col-md-4">
                            <div class="current-serving" id="currentServingWalkin">
                                <h4>Now Serving Walk-in</h4>
                                <div class="current-number" id="walkinCurrentNumber">TRX-06</div>
                                <div class="timer" id="walkinTimer">00:00:00</div>
                                <!-- Confirmed button -->
                                <button class="btn btn-secondary" id="walkinConfirmed">
                                    Confirmed
                                </button>
                                <!-- No Show button -->
                                <button class="btn btn-secondary" id="walkinNoShow">
                                    No Show
                                </button>
                                <!-- Done & Next button hidden initially -->
                                <button class="btn btn-primary" id="walkinDoneNext" style="display: none;">
                                    Done & Next
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
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
    // Function to update the clock
    function updateClock() {
        const clockElement = document.getElementById("clock");
        const now = new Date();

        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();
        let day = now.getDate();
        let month = now.getMonth() + 1; // Months are zero-based in JavaScript, so add 1
        let year = now.getFullYear();

        // Determine AM or PM
        let amPm = hours >= 12 ? "PM" : "AM";

        // Convert to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12; // Handle midnight case

        // Add leading zero to minutes and seconds if necessary
        function pad(number) {
            return number < 10 ? "0" + number : number;
        }

        clockElement.textContent =
            pad(hours) +
            ":" +
            pad(minutes) +
            ":" +
            pad(seconds) +
            " " +
            amPm +
            " | " +
            pad(month) +
            "/" +
            pad(day) +
            "/" +
            year;
    }

    // Padding function for single digit numbers
    function pad(num) {
        return num < 10 ? "0" + num : num;
    }

    // Update the clock every second
    setInterval(updateClock, 1000);

    // Function to update timers for scheduled and walk-in
    function updateTimer(id) {
        let timerElement = document.getElementById(id);
        let time = 0;
        setInterval(function() {
            time++;
            let hours = Math.floor(time / 3600);
            let minutes = Math.floor((time % 3600) / 60);
            let seconds = time % 60;
            timerElement.textContent = `${pad(hours)}:${pad(minutes)}:${pad(
            seconds
          )}`;
        }, 1000);
    }
</script>
<?php
// Include footer template
include_once '../views/templates/admin_footer.php';
?>
<script>
    $(document).ready(function() {

        // Store the original margin
        var originalMargin = '0 1rem 28.5rem';

        // Apply the updated margin when the collapse is toggled open
        $('a[data-toggle="collapse"]').on('click', function(event) {
            preventdefault();
            var element = $('.toggle-sidebar-divider')[0]; // Get the actual DOM element
        });

        // Listen for when the collapse is shown (open)
        $('#queueManagement').on('shown.bs.collapse', function() {
            var element = $('.toggle-sidebar-divider')[0]; // Get the actual DOM element
            // When the collapse is open, apply the modified margin
            element.style.setProperty('margin', '0 1rem 26.5rem', 'important');
        });

        // Listen for when the collapse is hidden (closed)
        $('#queueManagement').on('hidden.bs.collapse', function() {
            var element = $('.toggle-sidebar-divider')[0]; // Get the actual DOM element
            // When the collapse is closed, restore the original margin
            element.style.setProperty('margin', originalMargin, 'important');
        });


        // Cache the elements for reuse
        const $walkinConfirmedBtn = $("#walkinConfirmed");
        const $walkinNoShowBtn = $("#walkinNoShow");
        const $walkinDoneNextBtn = $("#walkinDoneNext");

        // Ensure the buttons exist before adding event listeners
        if ($walkinConfirmedBtn.length && $walkinNoShowBtn.length && $walkinDoneNextBtn.length) {

            // Event listener for "Confirmed" button
            $walkinConfirmedBtn.on("click", function() {
                // Change "Confirmed" to "Cancelled" and show "Done & Next" button
                $walkinConfirmedBtn.text("Cancelled");
                $walkinNoShowBtn.hide(); // Hide "No Show" button
                $walkinDoneNextBtn.show(); // Show "Done & Next" button
            });

            // Event listener for "No Show" button
            $walkinNoShowBtn.on("click", function() {
                // Hide both "Confirmed" and "No Show" buttons when "No Show" is clicked
                $walkinConfirmedBtn.hide();
                $walkinNoShowBtn.hide();
                $walkinDoneNextBtn.show(); // Show "Done & Next" button
            });
        } else {
            console.error("Required buttons not found in the DOM");
        }
    });
</script>