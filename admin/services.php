<?php
// Include necessary files
include_once '../views/templates/admin_header.php';
include_once '../models/Service.php';

$_SESSION["page_title"] = "Services";
$service = new Service($db);
$services = $service->getServices();
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
            <button type="button" class="btn btn-success mb-3" onclick="openAddServiceModal()">
                <i class="fas fa-plus"></i> Add Service
            </button>

            <!-- Table for Displaying Services -->
            <table class="table table-bordered table-striped" id="serviceTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamically Generated Service Data from the Database -->
                    <?php
                    if ($services) {
                        foreach ($services as $service) {
                            echo "<tr id ='serviceData_{$service['id']}'>
                                    <td>{$service['id']}</td>
                                    <td>{$service['service_name']}</td>
                                    <td>{$service['description']}</td>
                                    <td>
                                        <button class='btn btn-info btn-sm' onclick='openEditServiceModal({$service['id']})'>Edit</button>
                                        <button class='btn btn-danger btn-sm' onclick='openDeleteServiceModal({$service['id']})'>Delete</button>
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