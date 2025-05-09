<?php
// start the session
session_start();

// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../models/Requirement.php';

$_SESSION["page_title"] = "Service Requirements";
$requirement = new Requirement($db);
$requirements = $requirement->getAllRequirements();
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
                                    <td>{$requirement['service_name']}</td>
                                    <td>{$requirement['description']}</td>
                                    <td>
                                        <button class='btn btn-info btn-sm' onclick='openEditRequirementModal({$requirement['id']})'>Edit</button>
                                        <button class='btn btn-danger btn-sm' onclick='openDeleteRequirementModal({$requirement['id']})'>Delete</button>
                                    </td>
                                </tr>";
                        }
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

<!-- Add Requirement Modal -->
<div class="modal" id="addRequirementModal" tabindex="-1" role="dialog">
    <!-- Modal content similar to other modals -->
</div>