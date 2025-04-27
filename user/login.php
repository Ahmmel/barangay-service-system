<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QPILA - login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../css/user-login.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-box login">
            <form id="loginForm">
                <div class="logo">
                    <img src="../images/logo-black.png" alt="Logo" style=" width: 120px; height: auto;">
                </div>
                <div class="input-box">
                    <input type="text" id="loginUsername" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box" style="margin-bottom: 0;">
                    <input type="password" id="loginPassword" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <!-- Remember Me Checkbox -->
                <small class="remember-me">
                    <label>
                        <input type="checkbox" id="rememberMe">
                        Remember me
                    </label>
                </small>

                <button type="submit" class="btn">Login</button>
                <div id="loginError" style="color: red; font-size: 14px; margin-top: 10px;"></div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box register">
            <form id="registerForm">
                <h1>Registration</h1>
                <div class="input-box">
                    <input type="text" id="registerUsername" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" id="registerEmail" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" id="registerPassword" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box">
                    <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn">Register</button>
                <div id="responseMessage" style="color: red; font-size: 14px; margin-top: 10px;"></div>
            </form>
        </div>

        <!-- Toggle panels -->
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>

            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Toggle between login and register forms
            $('.register-btn').click(function() {
                // clear any previous error messages
                $('#responseMessage').text('');
                $('.container').addClass('active');
            });

            $('.login-btn').click(function() {
                $('.container').removeClass('active');
            });

            // AJAX request for Login
            $('#loginForm').submit(function(e) {
                e.preventDefault();

                var email = $('#loginUsername').val();
                var password = $('#loginPassword').val();
                var rememberMe = $('#rememberMe').is(':checked'); // Get the state of the "Remember Me" checkbox

                $.ajax({
                    url: '../controllers/AuthController.php',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password,
                        rememberMe: rememberMe // Send the checkbox value to the server
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            window.location.href = 'index.php'; // Redirect on success
                        } else {
                            $('#loginError').text(data.message || 'Login failed. Try again.');
                        }
                    },
                    error: function() {
                        $('#loginError').text('An error occurred, please try again later.');
                    }
                });
            });

            // AJAX request for Registration
            $('#registerForm').submit(function(e) {
                e.preventDefault();

                var username = $('#registerUsername').val();
                var email = $('#registerEmail').val();
                var password = $('#registerPassword').val();
                var confirmPassword = $('#confirmPassword').val();

                if (password !== confirmPassword) {
                    $('#responseMessage').text('Passwords do not match');
                    return;
                }

                $.ajax({
                    url: '../controllers/UserController.php?action=register',
                    type: 'POST',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            // Show a success message
                            $('#responseMessage')
                                .text('Registration successful! Redirecting to login...')
                                .css({
                                    'color': 'green',
                                    'display': 'none'
                                })
                                .fadeIn(500); // fade in smoothly

                            // Wait a moment, then click the login button
                            setTimeout(function() {
                                $('.login-btn').click();
                            }, 1500); // 1.5 seconds delay for user to see the message
                        } else {
                            $('#responseMessage').text(data.message || 'Registration failed. Try again.');
                        }
                    },
                    error: function() {
                        $('#responseMessage').text('An error occurred, please try again later.');
                    }
                });
            });
        });
    </script>
</body>

</html>