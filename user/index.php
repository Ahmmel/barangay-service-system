<?php
// Include database connection and user model
include_once '../config/database.php';
include_once '../models/User.php';
include_once '../models/Service.php';
include_once '../models/Transaction.php';

// Start the session
session_start();
// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// get user details
$userId = $_SESSION['user_id'];
$userModel = new User($db);
$userDetails = $userModel->getUserDetailsById($userId);
if (!$userDetails) {
    // Handle error: user not found
    header("Location: logout.php");
    exit();
}
$userDetails = $userDetails[0]; // Assuming you want the first result
$email = $userDetails['email'];
$username = $userDetails['username'];
$isVerified = $userDetails['is_verified'];
$fullName = $isVerified ? $userDetails['full_name'] : "-";
$gender = $isVerified ? $userDetails['gender'] : "-";
$birthdate = $isVerified ? date('F j, Y', strtotime($userDetails['birthdate'])) : "-";
$address = $isVerified ? $userDetails['address'] : "-";
$maritalStatus = $isVerified ? $userDetails['marital_status_name']  : "-";
$mobileNumber = $isVerified ? $userDetails['mobile_number'] : "-";
$profilePicture = $isVerified ? $userDetails['profile_picture'] : "../images/default.svg";
$headerName = $isVerified ? $userDetails['full_name'] : $username;

$words = explode(' ', $headerName);
// Get the first name
$firstName = isset($words[0]) ? $words[0] : null;
// Get the rest of the name
$withoutFirstName = count($words) > 1 ? implode(' ', array_slice($words, 1)) : null;

// get service details
$serviceModel = new Service($db);
$services = $serviceModel->getServices();

// get transaction details
$transactionModel = new Transaction($db);
$transactions = $transactionModel->getTransactionsByUserId($userId);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="QPILA Services Overview" />
    <meta name="author" content="QPILA Dev" />
    <title>QPILA Services</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />

    <!-- Google Fonts: Poppins -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <script
        src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
        crossorigin="anonymous"></script>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />

    <!-- Flatpickr Airbnb Theme -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css" />

    <!-- Include Select2 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        rel="stylesheet" />

    <!-- Custom Styles -->
    <link href="../css/user-index.css" rel="stylesheet" />

    <style>
        body {
            font-family: "Poppins", "Segoe UI", Roboto, "Helvetica Neue", Arial,
                sans-serif;
            background-color: #f8f9fa;
            scroll-behavior: smooth;
        }
    </style>
</head>

<body
    id="page-top"
    data-bs-spy="scroll"
    data-bs-target="#sideNav"
    data-bs-offset="100"
    tabindex="0">
    <!-- Navigation -->
    <nav
        class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top"
        id="sideNav">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
            <span class="d-block d-lg-none"><?php echo $headerName; ?></span>
            <span class="d-none d-lg-block"><img
                    class="img-fluid img-profile rounded-circle mx-auto mb-2"
                    src="<?php echo $profilePicture; ?>"
                    alt="<?php echo $username; ?>" /></span>
        </a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#transactions">Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="#officials">Officials</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                    <!-- notice no js-scroll-trigger -->
                </li>
            </ul>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container-fluid p-0">
        <!-- Home -->
        <section class="profile-section py-5" id="home">
            <div class="container">
                <div class="row align-items-start mb-5">
                    <!-- Profile Image and Name -->
                    <div class="col-md-4 text-center text-md-start mb-4 mb-md-0">
                        <img
                            src="<?php echo $profilePicture; ?>"
                            alt="<?php echo $headerName; ?>"
                            class="rounded-circle img-fluid mb-3"
                            style="max-width: 150px" />
                        <h2 class="mb-1">
                            <span class="text-primary"><?= $firstName; ?></span> <?= !empty($withoutFirstName) ? $withoutFirstName : ''; ?>
                        </h2>
                        <?php if (!$isVerified): ?>
                            <a href="#services" class="btn btn-outline-primary mb-2">
                                <i class="fas fa-id-badge me-2"></i>Request Verification
                            </a>
                        <?php endif; ?>
                        <!-- Change Password Button -->
                        <button
                            class="btn btn-outline-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>

                    <!-- User Details -->
                    <div class="col-md-8">
                        <h4 class="mb-4">My Information</h4>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <i class="fas fa-user me-2"></i><strong>Username:</strong> <?php echo $username; ?>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <i class="fas fa-envelope me-2"></i><strong>Email:</strong> <?php echo $email; ?>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <i class="fas fa-venus-mars me-2"></i><strong>Gender:</strong> <?php echo $gender; ?>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <i class="fas fa-calendar me-2"></i><strong>Birthdate:</strong> <?php echo $birthdate; ?>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i><strong>Address:</strong> <?php echo $address; ?>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <i class="fas fa-ring me-2"></i><strong>Marital Status:</strong> <?php echo $maritalStatus; ?>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <i class="fas fa-phone me-2"></i><strong>Mobile Number:</strong> <?php echo $mobileNumber; ?>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <?php if ($isVerified): ?>
                                    <i class="fas fa-user-check me-2"></i>
                                    <strong>Verification Status:</strong>
                                    <span class="badge badge-verified">Verified</span>
                                <?php else: ?>
                                    <i class="fas fa-user-times me-2"></i>
                                    <strong>Verification Status:</strong>
                                    <span class="badge badge-not-verified">Not Verified</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="profile-section py-5" id="services">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">
                        <i class="fas fa-concierge-bell me-2"></i> Request a Barangay
                        Service
                    </h2>
                    <p class="text-muted">Please complete the 3 easy steps below.</p>
                </div>

                <div class="wizard-container p-4 rounded shadow-sm bg-white">
                    <!-- Progress Bar -->
                    <div
                        class="progressbar d-flex justify-content-between mb-4 position-relative">
                        <div class="progress-step active" id="bar-step-1">1</div>
                        <div class="progress-step" id="bar-step-2">2</div>
                        <div class="progress-step" id="bar-step-3">3</div>
                    </div>

                    <!-- Step 1 -->
                    <div class="step active" id="step-1">
                        <h5 class="mb-3 fw-semibold">Select Services (Max 3)</h5>
                        <form id="service-form">
                            <select
                                id="brgy-services"
                                name="services[]"
                                class="form-select"
                                multiple="multiple"
                                style="width: 100%">
                                <?php if ($isVerified) : ?>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?= htmlspecialchars($service['id']) ?>">
                                            <?= htmlspecialchars($service['service_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled selected>
                                        Please verify your account to access services.
                                    </option>
                                <?php endif; ?>

                            </select>
                        </form>
                        <div class="d-flex justify-content-end mt-4">
                            <button
                                type="button"
                                class="btn btn-primary"
                                onclick="nextStep(1)">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step" id="step-2">
                        <h5 class="mb-3 fw-semibold">Select Date and Time</h5>
                        <div class="input-group mb-3 datetime-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input
                                type="text"
                                id="datetime-picker"
                                class="form-control form-control-lg"
                                placeholder="Select Date & Time"
                                readonly />
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                onclick="showStep(1)">
                                Back
                            </button>
                            <button
                                type="button"
                                class="btn btn-primary"
                                onclick="nextStep(2)">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="step" id="step-3">
                        <h5 class="mb-3 fw-semibold">Confirm Your Request</h5>
                        <div class="mb-3">
                            <p class="mb-1 fw-bold">Selected Services:</p>
                            <ul
                                id="confirmation-list"
                                class="list-group list-group-flush"></ul>
                        </div>
                        <div class="mb-4">
                            <p class="mb-1 fw-bold">Date & Time:</p>
                            <span id="confirm-datetime" class="text-primary"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                onclick="showStep(2)">
                                Back
                            </button>
                            <button
                                type="button"
                                class="btn btn-success"
                                onclick="confirmBooking()">
                                <i class="fas fa-paper-plane me-1"></i> Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Transactions -->
        <section class="profile-section py-5" id="transactions">
            <div class="profile-section-content">
                <h2 class="mb-5">Recent Transactions</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Transaction Code</th>
                                <th>Services</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?= htmlspecialchars($transaction['transaction_code']) ?></td>

                                    <!-- Services -->
                                    <td>
                                        <?php
                                        // Decode the services string manually (because it's not true JSON array)
                                        $servicesString = '[' . $transaction['services'] . ']'; // Wrap with [] to make it valid JSON
                                        $servicesArray = json_decode($servicesString, true);

                                        if (is_array($servicesArray)) {
                                            foreach ($servicesArray as $service) {
                                                echo '<span class="badge bg-primary me-1 mb-1">' . htmlspecialchars($service['name']) . '</span>';
                                            }
                                        } else {
                                            echo '<span class="text-muted">No services found</span>';
                                        }
                                        ?>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        <?php if ($transaction['status'] === 'Closed'): ?>
                                            <span class="badge bg-success">Closed</span>
                                        <?php elseif ($transaction['status'] === 'Pending'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-info text-dark"><?= htmlspecialchars($transaction['status']) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Date -->
                                    <td>
                                        <?= htmlspecialchars(date('F d, Y', strtotime($transaction['created_at']))) ?>
                                    </td>

                                    <!-- Review Button -->
                                    <td>
                                        <?php if ($transaction['status'] === 'Closed' && !$transaction['rating']): ?>
                                            <button class="btn btn-outline-primary btn-sm" onclick="openReviewModal('<?= htmlspecialchars($transaction['transaction_code']) ?>')">
                                                Leave a Review
                                            </button>
                                        <?php elseif ($transaction['rating']): ?>
                                            <?php
                                            // Build the stars in PHP
                                            $stars = '';
                                            for ($i = 1; $i <= 5; $i++) {
                                                $stars .= ($i <= $transaction['rating']) ? '⭐' : '☆';
                                            }
                                            ?>
                                            <span style="font-size: 1.2rem;"><?= $stars ?></span>
                                        <?php else: ?>
                                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                                Not Available
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- Review Modal -->
        <div
            class="modal fade"
            id="reviewModal"
            tabindex="-1"
            aria-labelledby="reviewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">
                            Rate Your Transaction
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p id="transactionIdText" class="mb-3"></p>
                        <div id="rating" class="d-flex justify-content-center gap-2">
                            <span class="rate" data-value="1">😡</span>
                            <span class="rate" data-value="2">😕</span>
                            <span class="rate" data-value="3">😐</span>
                            <span class="rate" data-value="4">😊</span>
                            <span class="rate" data-value="5">😍</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button
                            type="button"
                            class="btn btn-success"
                            onclick="submitRating()">
                            Submit Review
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Government Officials -->
        <section class="profile-section py-5" id="officials">
            <div class="profile-section-content">
                <!-- Organizational Chart -->
                <div class="org-chart-wrapper">
                    <h2>Government Officials Organizational Chart</h2>
                    <div class="org-chart">
                        <!-- Level 1 -->
                        <div class="level">
                            <div class="member">
                                <img src="../images/default_male.png" alt="Juan Dela Cruz" />
                                <div class="title">Barangay Captain</div>
                                <div class="name">Juan Dela Cruz</div>
                                <div class="tooltip">
                                    Juan Dela Cruz, Barangay Captain since 2018
                                </div>
                            </div>
                        </div>

                        <!-- Level 2 -->
                        <div class="level">
                            <div class="member">
                                <img src="../images/default_male.png" alt="Maria Santos" />
                                <div class="title">Barangay Secretary</div>
                                <div class="name">Maria Santos</div>
                                <div class="tooltip">Maria Santos, Barangay Secretary</div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Pedro Reyes" />
                                <div class="title">Barangay Treasurer</div>
                                <div class="name">Pedro Reyes</div>
                                <div class="tooltip">Pedro Reyes, Barangay Treasurer</div>
                            </div>
                        </div>

                        <!-- Level 3 -->
                        <div class="level">
                            <div class="member">
                                <img src="../images/default_male.png" alt="Ana Cruz" />
                                <div class="title">Kagawad</div>
                                <div class="name">Ana Cruz</div>
                                <div class="tooltip">Ana Cruz, Kagawad (Health)</div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Luis Garcia" />
                                <div class="title">Kagawad</div>
                                <div class="name">Luis Garcia</div>
                                <div class="tooltip">
                                    Luis Garcia, Kagawad (Public Safety)
                                </div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Ella Navarro" />
                                <div class="title">Kagawad</div>
                                <div class="name">Ella Navarro</div>
                                <div class="tooltip">
                                    Ella Navarro, Kagawad (Social Welfare)
                                </div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Joseph Lim" />
                                <div class="title">Kagawad</div>
                                <div class="name">Joseph Lim</div>
                                <div class="tooltip">Joseph Lim, Kagawad (Environment)</div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="May Mendoza" />
                                <div class="title">Kagawad</div>
                                <div class="name">May Mendoza</div>
                                <div class="tooltip">May Mendoza, Kagawad (Education)</div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Carlo Dizon" />
                                <div class="title">Kagawad</div>
                                <div class="name">Carlo Dizon</div>
                                <div class="tooltip">
                                    Carlo Dizon, Kagawad (Infrastructure)
                                </div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Nica Ramos" />
                                <div class="title">Kagawad</div>
                                <div class="name">Nica Ramos</div>
                                <div class="tooltip">Nica Ramos, Kagawad (Finance)</div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Mark De Leon" />
                                <div class="title">SK Chairperson</div>
                                <div class="name">Mark De Leon</div>
                                <div class="tooltip">Mark De Leon, SK Chairperson</div>
                            </div>
                        </div>

                        <!-- Level 4 -->
                        <div class="level">
                            <div class="member">
                                <img src="../images/default_male.png" alt="Ricardo Ramos" />
                                <div class="title">Tanod Head</div>
                                <div class="name">Ricardo Ramos</div>
                                <div class="tooltip">Ricardo Ramos, Tanod Head</div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Leo Santos" />
                                <div class="title">Tanod</div>
                                <div class="name">Leo Santos</div>
                                <div class="tooltip">Leo Santos, Tanod</div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Lorna Bautista" />
                                <div class="title">Health Worker</div>
                                <div class="name">Lorna Bautista</div>
                                <div class="tooltip">Lorna Bautista, Health Worker</div>
                            </div>
                            <div class="member">
                                <img src="../images/default_male.png" alt="Ella Tan" />
                                <div class="title">Day Care Worker</div>
                                <div class="name">Ella Tan</div>
                                <div class="tooltip">Ella Tan, Day Care Worker</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div style="text-align: center; padding: 1rem; font-family: 'Poppins', 'Segoe UI', sans-serif; font-size: 0.9rem; color: #6c757d;">
                Powered by: <strong style="color: #bd5932;">QPILA</strong> © <?= date('Y') ?>
            </div>
        </section>
    </div>

    <!-- Change Password Modal -->
    <div
        class="modal fade"
        id="changePasswordModal"
        tabindex="-1"
        aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="changePasswordForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">
                            Change Password
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="oldPassword" class="form-label">Current Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="oldPassword"
                                name="oldPassword"
                                required />
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="newPassword"
                                name="newPassword"
                                required />
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="confirmPassword"
                                name="confirmPassword"
                                required />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Error alert -->
    <div
        id="passwordError"
        class="alert alert-danger d-none mx-3 mt-2"
        role="alert"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        var userId = "<?php echo $userId; ?>";
        let selectedRating = 0;
        let currentTransactionCode = "";

        function openReviewModal(transactionId) {
            currentTransactionCode = transactionId;
            $("#transactionIdText").text(`Transaction: ${transactionId}`);
            $("#rating .rate").removeClass("selected");
            selectedRating = 0;
            $("#reviewModal").modal("show");
        }

        $("#rating .rate").on("click", function() {
            $("#rating .rate").removeClass("selected");
            $(this).addClass("selected");
            selectedRating = $(this).data("value");
        });

        function submitRating() {
            if (selectedRating === 0) {
                Swal.fire({
                    toast: true,
                    icon: 'warning',
                    title: 'Please select a rating before submitting!',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                return;
            }

            // Hide the modal immediately
            $("#reviewModal").modal("hide");

            // Send the rating to server via AJAX first
            $.ajax({
                url: '../controllers/TransactionController.php?action=rateTransaction', // Adjust to your real endpoint
                method: 'POST',
                data: {
                    transaction_code: currentTransactionCode,
                    rating: selectedRating
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Build the stars based on rating
                        let stars = "";
                        for (let i = 1; i <= 5; i++) {
                            stars += (i <= selectedRating) ? "⭐" : "☆";
                        }

                        // Find the correct table row and update the Review column
                        $(`td:contains(${currentTransactionCode})`).each(function() {
                            if ($(this).text() === currentTransactionCode) {
                                const reviewCell = $(this).siblings().last();
                                reviewCell.html(`<span style="font-size: 1.2rem;">${stars}</span>`);
                            }
                        });

                        // Show success toast
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: 'Thank you for your review!',
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed!',
                            text: response.message || 'Unable to submit rating. Please try again.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error!',
                        text: 'Something went wrong. Please try again later.'
                    });
                }
            });
        }

        $(document).ready(function() {
            $("#brgy-services").select2({
                placeholder: "Select up to 3 services",
                maximumSelectionLength: 3,
                allowClear: true,
            });
        });

        flatpickr("#datetime-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            time_24hr: false, // show AM/PM
            minuteIncrement: 30, // Only 30 minutes
            position: "above",
            locale: {
                firstDayOfWeek: 1, // Monday as first day
            },
            disable: [
                function(date) {
                    return date.getDay() === 0; // Disable Sundays
                },
            ],
            onChange: function(selectedDates, dateStr, instance) {
                const selected = selectedDates[0];
                if (!selected) return;

                const hour = selected.getHours();
                const minute = selected.getMinutes();

                // Allow only between 8:00 AM and 4:30 PM
                if (hour < 8 || hour > 16 || (hour === 16 && minute > 30)) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end', // Top right corner
                        icon: 'warning',
                        title: '⏰ Invalid Time',
                        text: 'Please select between 8:00 AM and 4:30 PM.',
                        showConfirmButton: false,
                        timer: 3000, // Auto-close after 3 seconds
                        timerProgressBar: true
                    });

                    instance.clear();
                }
            },
        });

        function nextStep(step) {
            // Validation before moving to next step
            if (step === 1) {
                const selectedServices = $("#brgy-services").val() || [];
                if (selectedServices.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Services',
                        text: '⚠️ Please select at least one service before proceeding.',
                    });
                    return;
                }
            }

            if (step === 2) {
                const selectedDateTime = $("#datetime-picker").val().trim();
                if (!selectedDateTime) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Date and Time',
                        text: '⚠️ Please select a date and time before proceeding.',
                    });
                    return;
                }
            }

            // If passed validation, proceed
            $("#step-" + step).removeClass("active");
            $("#bar-step-" + step).removeClass("active");

            step++;

            $("#step-" + step).addClass("active");
            $("#bar-step-" + step).addClass("active");

            if (step === 3) {
                updateConfirmation();
            }
        }

        function showStep(step) {
            $(".step").removeClass("active");
            $(".progress-step").removeClass("active");
            $("#step-" + step).addClass("active");
            $("#bar-step-" + step).addClass("active");
        }

        function updateConfirmation() {
            const selectedServiceIds = $("#brgy-services").val() || [];
            const datetime = $("#datetime-picker").val();

            let formattedDateTime = "";
            if (datetime) {
                const dateObj = new Date(datetime.replace(/-/g, "/"));
                const options = {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                    hour: "numeric",
                    minute: "2-digit",
                    hour12: true,
                };
                formattedDateTime = dateObj.toLocaleString("en-PH", options);
            }

            // Get the actual service names based on selected options
            const selectedServiceNames = [];
            selectedServiceIds.forEach(function(serviceId) {
                const serviceName = $(`#brgy-services option[value='${serviceId}']`).text();
                selectedServiceNames.push(serviceName);
            });

            // Update UI
            $("#confirmation-list").html(
                selectedServiceNames.map((name) => `<li class="list-group-item">${name}</li>`).join("")
            );
            $("#confirm-datetime").text(formattedDateTime);
        }

        function confirmBooking() {
            const selectedServices = $("#brgy-services").val() || [];
            const selectedDateTime = $("#datetime-picker").val().trim();

            // Validation first
            if (selectedServices.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete!',
                    text: 'Please select at least one service.',
                });
                return;
            }

            if (!selectedDateTime) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete!',
                    text: 'Please select a date and time.',
                });
                return;
            }

            // Build the data to send
            const bookingData = {
                serviceIds: selectedServices,
                scheduledTime: selectedDateTime,
                userId: userId, // make sure userId is available globally
                transactionType: 2 // scheduled transaction
            };

            // Send AJAX request
            $.ajax({
                url: '../controllers/TransactionController.php?action=add',
                method: 'POST',
                data: bookingData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Booking Confirmed!',
                            text: 'Thank you for your service request.',
                            confirmButtonText: 'View My Transactions'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // After confirmation, reload transactions dynamically
                                loadTransactions(userId);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Booking Failed',
                            text: response.message || "Something went wrong. Please try again.",
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Unable to process your booking at the moment.',
                    });
                }
            });
        }

        function loadTransactions(userId) {
            $.ajax({
                url: '../controllers/TransactionController.php?action=getTransactionsByUserId',
                method: 'POST',
                data: {
                    userId: userId
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Load',
                            text: response.message || 'Unable to load transactions.',
                        });
                        return;
                    }

                    const transactions = response.transactions || [];
                    const tbody = $("#transactions table tbody");

                    // Clear existing table data
                    tbody.empty();

                    if (transactions.length === 0) {
                        tbody.append('<tr><td colspan="5" class="text-center">No transactions found.</td></tr>');
                    } else {
                        transactions.forEach(transaction => {
                            let servicesHTML = '';

                            try {
                                // Fix the invalid JSON by wrapping in []
                                const servicesArray = JSON.parse('[' + transaction.services + ']');

                                servicesArray.forEach(service => {
                                    servicesHTML += `<span class="badge bg-primary me-1 mb-1">${service.name}</span>`;
                                });
                            } catch (e) {
                                console.error("Failed to parse services JSON for transaction: " + transaction.transaction_code);
                                servicesHTML = `<span class="text-danger">Error loading services</span>`;
                            }

                            const reviewButton = transaction.status === 'Closed' ?
                                `<button class="btn btn-outline-primary btn-sm" onclick="openReviewModal('${transaction.transaction_code}')">Leave a Review</button>` :
                                `<button class="btn btn-outline-secondary btn-sm" disabled>Not Available</button>`;

                            tbody.append(`
                                <tr>
                                <td>${transaction.transaction_code}</td>
                                <td>${servicesHTML}</td>
                                <td>
                                    <span class="badge
                                        ${
                                        transaction.status === 'Closed'
                                            ? 'bg-success'
                                            : transaction.status === 'Pending'
                                            ? 'bg-warning text-dark'
                                            : 'bg-info text-dark'
                                        }
                                    ">
                                        ${transaction.status}
                                    </span>

                                </td>
                                <td>${new Date(transaction.created_at).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</td>
                                <td>${reviewButton}</td>
                                </tr>
                            `);
                        });
                    }

                    // After DOM is updated, smooth scroll
                    setTimeout(() => {
                        const transactionSection = document.getElementById("transactions");
                        if (transactionSection) {
                            transactionSection.scrollIntoView({
                                behavior: "smooth"
                            });
                        }
                    }, 200);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Unable to load transactions.',
                    });
                }
            });
        }



        document.querySelectorAll(".js-scroll-trigger").forEach((link) => {
            link.addEventListener("click", function() {
                // Remove 'active' from all nav-links
                document
                    .querySelectorAll(".nav-link")
                    .forEach((el) => el.classList.remove("active"));

                // Add 'active' to the clicked link
                this.classList.add("active");
            });
        });

        $(document).ready(function() {
            $(".member").hover(
                function() {
                    $(this).find(".tooltip").fadeIn(200);
                },
                function() {
                    $(this).find(".tooltip").fadeOut(200);
                }
            );
        });
        // Limit to 3 checkbox selections
        document.querySelectorAll("input[name='services']").forEach((cb) => {
            cb.addEventListener("change", () => {
                const selected = document.querySelectorAll(
                    "input[name='services']:checked"
                );
                if (selected.length > 3) {
                    cb.checked = false;
                    alert("You can select a maximum of 3 services.");
                }
            });
        });
    </script>
    <script>
        $("#changePasswordForm").on("submit", function(e) {
            e.preventDefault();

            const oldPassword = $("#oldPassword").val();
            const newPassword = $("#newPassword").val();
            const confirmPassword = $("#confirmPassword").val();

            if (newPassword !== confirmPassword) {
                alert("New password and confirm password do not match!");
                return;
            }

            $.ajax({
                url: "/change-password.php", // 👉 Adjust this URL based on your backend
                method: "POST",
                data: {
                    oldPassword: oldPassword,
                    newPassword: newPassword,
                },
                success: function(response) {
                    if (response.success) {
                        alert("Password changed successfully!");
                        $("#changePasswordModal").modal("hide");
                        $("#changePasswordForm")[0].reset();
                    } else {
                        alert(
                            response.message ||
                            "Failed to change password. Please try again."
                        );
                    }
                },
                error: function() {
                    alert("Something went wrong. Please try again.");
                },
            });
        });
    </script>
</body>

</html>