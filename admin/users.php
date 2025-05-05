<?php
// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../models/User.php';
$_SESSION["page_title"] = "Users";
$user = new User($db);
$users = $user->getUsers();
?>

<!-- Sidebar -->
<?php include('../views/templates/side_bar.php'); ?>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <?php include('../views/templates/top_bar.php'); ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Button to Trigger Modal -->
            <button type="button" class="btn btn-success mb-3" onclick="openAddUserModal()">
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
                            $fullName = $user['first_name'] . " " . $user['middle_name'] . " " . $user['last_name'] . " " . $user['suffix'];
                            $status = $user['is_verified'] ? 'Verified' : 'Pending';
                            $statusClass = $user['is_verified'] ? 'badge-success' : 'badge-warning';
                            $defaultImage = $user['gender_id'] == 2 ? "../images/default_male.png" : "../images/default_female.png";
                            $profilePicture = !empty($user['profile_picture']) ? "../uploads/" . $user['profile_picture'] : $defaultImage;
                            $gender = $user['gender'];
                            $id = $user['id'];
                            $mobileNumber  =  (isset($user['mobile_number']) && $user['mobile_number'] ? $user['mobile_number'] : 'n/a');
                            echo "<tr id ='userData_{$id}'>
                                    <td>{$id}</td>
                                    <td>{$fullName}</td>
                                    <td>{$gender}</td>
                                    <td>{$user['username']}</td>
                                    <td>{$user['email']}</td>
                                    <td>{$mobileNumber}</td>
                                    <td>{$user['role_name']}</td>
                                    <td><span class='badge {$statusClass}'>{$status}</span></td>
                                    <td>
                                        <img src='{$profilePicture}' alt='Profile' 
                                            style='
                                                margin:auto;
                                                width: 40px; 
                                                height: 40px;
                                                border-radius: 50%;
                                                display: flex;
                                                justify-content: center;
                                            '
                                        >
                                    </td>
                                    <td>
                                        <button class='btn btn-info btn-sm' onclick='openEditUserModal({$id})'>Edit</button>
                                        <button class='btn btn-danger btn-sm' onclick='openDeleteUserModal({$id})'>Delete</button>
                                    </td>
                                </tr>";
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
    <?php include('../views/templates/footer.php'); ?>
    <!-- End of Footer -->
</div>
<!-- End of Content Wrapper -->
<?php
// Include footer template
include_once '../views/templates/admin_footer.php';
?>