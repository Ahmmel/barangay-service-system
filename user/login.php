<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QPILA - Login</title>
    <link rel="icon" href="../images/qpila-logo-favicon.png" type="image/x-icon">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="../css/user-login.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="particles-js"></div>
    <button class="dark-toggle" aria-label="Toggle dark mode" onclick="toggleDarkMode()">
        <i class='bx bx-moon'></i>
    </button>

    <div class="container">
        <!-- Login Form -->
        <div class="form-box login">
            <form id="loginForm">
                <div class="logo">
                    <img src="../images/qpila-logo.png" alt="QPILA Logo">
                </div>
                <div class="input-box">
                    <input type="text" id="loginUsername" placeholder="Username / email" required>
                    <i class='bx bx-user-circle'></i>
                </div>
                <div class="input-box">
                    <input type="password" id="loginPassword" placeholder="Password" required>
                    <i class='bx bx-lock'></i>
                </div>
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" id="rememberMe"> Remember me
                    </label>
                    <a href="#" class="forgot-link forgot-btn">Forgot password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <div id="loginError" class="form-error"></div>
                <div class="footer">
                    Powered by QPila &copy; <span id="year"></span>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form-box register">
            <form id="registerForm">
                <h1>Registration</h1>
                <div class="input-box">
                    <input type="text" id="registerUsername" placeholder="Username" required>
                    <i class='bx bx-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" id="registerEmail" placeholder="Email" required>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" id="registerPassword" placeholder="Password" required>
                    <i class='bx bx-lock-open'></i>
                </div>
                <div class="input-box">
                    <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
                    <i class='bx bx-lock-alt'></i>
                </div>
                <button type="submit" class="btn">Register</button>
                <div id="responseMessage" class="form-error"></div>
            </form>
        </div>

        <!-- Forgot Password Form -->
        <div class="form-box forgot-password">
            <form id="forgotForm">
                <h1>Forgot Password</h1>
                <p>Please enter your email to reset your password.</p>
                <div class="input-box">
                    <input type="email" id="forgotEmail" placeholder="Enter your email" required>
                    <i class='bx bx-envelope'></i>
                </div>
                <button type="submit" class="btn">Reset Password</button>
                <div id="forgotMessage" class="form-error"></div>
            </form>
        </div>

        <!-- Toggle Panels -->
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button type="button" class="btn register-btn">Register</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button type="button" class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script src="../js/user-login.js"></script>
</body>

</html>