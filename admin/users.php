<?php
// start the session
session_start();

// Include necessary files
include_once __DIR__ . '/../views/templates/admin_header.php';
include_once __DIR__ . '/../models/User.php';

$_SESSION["page_title"] = "Users";
$user = new User($db);
$users = $user->getUsers();
$isStaffAllowedToUpdate = !$isAdmin ? $user->isStaffAllowedToUpdate() : true;
$disabledAttr = !$isStaffAllowedToUpdate ? 'disabled' : '';
?>

<!-- Sidebar -->
<?php include(__DIR__ . '/../views/templates/side_bar.php'); ?>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <?php include(__DIR__ . '/../views/templates/top_bar.php'); ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Button to Trigger Modal -->
            <button type="button" class="btn btn-success mb-3" <?php echo $disabledAttr; ?> onclick="openAddUserModal()">
                <i class="fas fa-user-plus"></i> Add User
            </button>

            <!-- Table for Displaying Users -->
            <table class="table table-bordered table-striped" id="userTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Profile Picture</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamically Generated User Data from the Database -->
                    <?php
                    if ($users) {
                        foreach ($users as $user) {
                            // Constructing the full name
                            $fullName = trim("{$user['first_name']} {$user['middle_name']} {$user['last_name']} {$user['suffix']}");

                            // Determine user status
                            $status = $user['is_verified'] ? 'Verified' : 'Pending';
                            $statusClass = $user['is_verified'] ? 'badge-success' : 'badge-warning';

                            // Default profile image based on gender
                            $defaultImage = ($user['gender_id'] == 2) ? "../images/default_male.png" : "../images/default_female.png";
                            $profilePicture = !empty($user['profile_picture']) ? "../uploads/" . $user['profile_picture'] : $defaultImage;

                            // Mobile number fallback
                            $mobileNumber = $user['mobile_number'] ?: 'n/a';

                            // Output the user row
                            echo "<tr id='userData_{$user['id']}'>
                                <td>{$user['id']}</td>
                                <td>{$fullName}</td>
                                <td>{$user['gender']}</td>
                                <td>{$user['username']}</td>
                                <td>{$user['email']}</td>
                                <td>{$mobileNumber}</td>
                                <td>{$user['role_name']}</td>
                                <td><span class='badge {$statusClass}'>{$status}</span></td>
                                <td>
                                    <img src='{$profilePicture}' alt='Profile' style='
                                        margin:auto;
                                        width: 40px; 
                                        height: 40px;
                                        border-radius: 50%;
                                        display: flex;
                                        justify-content: center;
                                    '>
                                </td>
                                <td>
                                    <!-- Edit button (always visible) -->
                                    <button class='btn btn-info btn-sm' {$disabledAttr} onclick='openEditUserModal({$user['id']})'>
                                        <i class='fas fa-edit'></i> Edit
                                    </button>";

                            // Only display the Delete button if the user is an admin
                            if ($isAdmin) {
                                echo "<button class='btn btn-danger btn-sm' onclick='openDeleteUserModal({$user['id']})'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>";
                            }

                            echo "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No users found</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php include(__DIR__ . '/../views/templates/footer.php'); ?>
    <!-- End of Footer -->
</div>
<!-- End of Content Wrapper -->
<?php
// Include footer template
include_once __DIR__ . '/../views/templates/admin_footer.php';
?>