</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include_once 'admin_modals.php'; ?>
<script>
    var isAdmin = <?= json_encode($isAdmin) ?>;
    var currentSessionId = <?= json_encode($currentSessionId) ?>;
</script>
<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.js"></script>
<!-- Custom scripts for all pages-->
<script src="../js/admin.js"></script>
<script src="../js/custom_process.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.js"></script>
<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.js"></script>
<script>
    $(document).ready(function() {
        const contentContainer = $("#content-wrapper");

        // Function to load content dynamically
        function loadContent(url, pushState = true) {
            NProgress.start();
            contentContainer.addClass("fade-out");

            setTimeout(function() {
                $.get(url, function(data) {
                    const tempDiv = $("<div>").html(data);

                    // ✅ Extract and update <title>
                    const newTitle = tempDiv.filter("title").text() || tempDiv.find("title").text();
                    if (newTitle) document.title = newTitle;

                    // ✅ Extract and inject content
                    const newContent = tempDiv.find("#content-wrapper").html();
                    if (newContent) {
                        contentContainer.html(newContent);

                        if (pushState) {
                            history.pushState(null, '', url);
                            initTablesAndSelects();

                            $('.nav-item').removeClass('active');
                            const currentNavItem = $('.nav-item a[href="' + url + '"]').closest('.nav-item');
                            currentNavItem.addClass('active');

                            $('#queueManagement').removeClass('show');
                        }
                    }

                    window.scrollTo(0, 0);
                    contentContainer.removeClass("fade-out").addClass("fade-in");
                    NProgress.done();
                });
            }, 100);
        }


        // Handle link clicks
        $('a[data-ajax="true"]').on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            if (url) {
                loadContent(url);
            }
        });

        // Handle back/forward browser navigation
        window.addEventListener("popstate", function() {
            loadContent(location.pathname, false);
        });
    });
</script>
</body>

</html>