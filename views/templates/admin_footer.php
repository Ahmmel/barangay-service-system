</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include_once 'admin_modals.php'; ?>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.js"></script>
<!-- Custom scripts for all pages-->
<script src="../js/admin.js"></script>
<script src="../js/admin_user.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.js"></script>
<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    // Initialize DataTables
    $(document).ready(function() {
        $("#activityLogTable").DataTable({
            pageLength: 10, // Max 10 entries per page
            lengthChange: false, // Disable length change dropdown
            searching: true, // Enable search bar
            ordering: true, // Enable sorting
            info: true, // Display the info text (e.g., showing 1 to 10 of 12 entries)
            paging: false, // Enable pagination
        });

        $("#addServices").select2({
            placeholder: "Select services",
            allowClear: true,
            width: "100%",
            maximumSelectionLength: "3",
        });
    });
</script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.js"></script>
</body>

</html>