<?php
$_SESSION["page_title"] = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Admin - <?php echo $_SESSION["page_title"]; ?></title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/admin-sidebar.css" rel="stylesheet">
    <link href="../vendor/font/font-awesome.css" rel="stylesheet">
    <link href="../vendor/font/googleapis.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

    <?php
    if ($_SESSION["page_title"] == "User") {
        echo '<link href="../css/common-pages.css" rel="stylesheet">';
    }
    ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">