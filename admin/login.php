<?php
session_start();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>QPILA - Admin Login</title>
    <link rel="icon" href="../images/qpila-logo-favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="../css/admin-login.css" rel="stylesheet">
</head>

<body>

    <div id="particles-js"></div>

    <div class="dark-toggle" onclick="toggleDarkMode()">
        <i class="fas"></i>
    </div>

    <div class="login-container">
        <div class="logo">
            <img src="../images/qpila-logo.png" alt="QPila Logo" />
        </div>
        <h2>Administrator Login</h2>

        <div id="login-error" class="enhanced-alert">
            <i class="fas fa-exclamation-triangle"></i>
            <div><strong>Oops!</strong> <span id="error-text"></span></div>
        </div>

        <form id="login-form">
            <input type="hidden" name="is_admin" value="1" id="is-admin">
            <div class="form-group">
                <input type="text" name="identifier" placeholder="Email or Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-check">
                <input type="checkbox" id="rememberMe" name="remember">
                <label for="rememberMe">Remember Me</label>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/admin-login.js"></script>

</body>

</html>