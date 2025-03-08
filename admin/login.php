<?php
session_start();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']); // Remove error after displaying it
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Administrator Login for QPILA">
    <meta name="author" content="QPILA">

    <title>QPILA - Login</title>

    <!-- Custom fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom styles -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/admin-login.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image">
                        <img src="../images/logo-black.png" alt="QPILA Logo" class="img-fluid mx-auto d-block" style="max-width: 250px; margin-top: 84px;">
                    </div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Administrator Login</h1>
                            </div>

                            <!-- Login Form -->
                            <form class="user" action="../controllers/AuthController.php" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="email" placeholder="Enter Email Address Or Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="password" placeholder="Enter Password" required>
                                </div>
                                <div class="form-group d-flex justify-content-between">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="rememberMe" name="remember">
                                        <label class="custom-control-label" for="rememberMe">Remember Me</label>
                                    </div>
                                </div>

                                <!-- Error message -->
                                <?php if ($error) : ?>
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Error!</strong> <?= htmlspecialchars($error); ?>
                                    </div>
                                <?php endif; ?>

                                <button type="submit" class="btn btn-default-black btn-user btn-block">Login</button>
                                <hr>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>