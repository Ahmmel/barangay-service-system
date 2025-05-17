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
                    <input type="text" id="identifier" placeholder="Enter your email or username" required>
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
                <div class="footer">
                    Powered by QPila &copy; <span id="year"></span>
                </div>
            </form>
        </div>
        <div class="form-box register wizard">
            <div class="container-wizard">
                <ul class="wizard-progress">
                    <li class="active">Personal</li>
                    <li>Account</li>
                    <li>Security</li>
                </ul>
                <form id="registerForm">
                    <!-- Step 1: Personal -->
                    <div class="wizard-step active" data-step="0">
                        <div class="input-group">
                            <input type="text" id="firstName" name="first_name" placeholder="First Name" required>
                            <i class='bx bx-id-card'></i>
                        </div>
                        <div class="input-group">
                            <input type="text" id="lastName" name="last_name" placeholder="Last Name" required>
                            <i class='bx bx-id-card'></i>
                        </div>
                        <div class="input-group">
                            <input type="date" name="birthdate" id="registerBirthdate" required>
                            <i class='bx bx-calendar'></i>
                        </div>
                        <div class="input-group">
                            <select id="registerGender" name="gender" required>
                                <option value="" disabled selected>Gender</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <select id="registerMaritalStatus" name="marital_status" required>
                                <option value="" disabled selected>Marital Status</option>
                            </select>
                        </div>
                        <div class="buttons">
                            <button type="button" class="btn btn-next">Next</button>
                        </div>
                    </div>
                    <!-- Step 2: Account -->
                    <div class="wizard-step" data-step="1">
                        <div class="input-group">
                            <input type="text" name="username" id="registerUsername" placeholder="Username" required>
                            <i class='bx bx-user'></i>
                        </div>
                        <div class="input-group">
                            <input type="email" name="email" id="registerEmail" placeholder="Email" required>
                            <i class='bx bx-envelope'></i>
                        </div>
                        <div class="input-group">
                            <input type="tel" name="mobile_number" id="registerMobile" placeholder="Mobile Number" pattern="[0-9]{10,15}" required>
                            <i class='bx bx-phone'></i>
                        </div>
                        <div class="buttons">
                            <button type="button" class="btn btn-prev">Previous</button>
                            <button type="button" class="btn btn-next">Next</button>
                        </div>
                    </div>
                    <!-- Step 3: Security -->
                    <div class="wizard-step" data-step="2">
                        <div class="input-group">
                            <input type="password" id="registerPassword" name="password" placeholder="Password" required>
                            <i class='bx bx-lock-open'></i>
                        </div>
                        <div class="input-group">
                            <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" required>
                            <i class='bx bx-lock-alt'></i>
                        </div>
                        <div class="buttons">
                            <button type="button" class="btn btn-prev">Previous</button>
                            <button type="submit" class="btn btn-submit">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Forgot Password Form -->
        <div class="form-box forgot-password">
            <form id="forgotForm">
                <h1>Forgot Password</h1>
                <p>Enter your mobile number to proceed with account recovery via SMS.</p>

                <!-- Modern Toggle Switch -->
                <div class="toggle-switch">
                    <span>Reset via SMS</span>
                </div>

                <!-- Mobile Number Input -->
                <div class="input-box contact-input" data-method="mobile">
                    <input
                        type="tel"
                        id="forgotMobile"
                        name="forgotMobile"
                        placeholder="(e.g. +639XXXXXXXXX)"
                        pattern="\+?[0-9]{7,15}" />
                    <i class="bx bx-mobile"></i>
                </div>

                <button type="submit" class="btn">Reset Password</button>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/user-login.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('../controllers/UserController.php?action=getRegistrationPrequiset')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate Gender Dropdown
                        const genderSelect = document.getElementById("registerGender");
                        genderSelect.innerHTML = `<option value="" disabled selected>Gender</option>`;
                        data.genders.forEach(g => {
                            const option = document.createElement("option");
                            option.value = g.id;
                            option.textContent = g.gender_name;
                            genderSelect.appendChild(option);
                        });

                        // Populate Marital Status Dropdown
                        const maritalSelect = document.getElementById("registerMaritalStatus");
                        maritalSelect.innerHTML = `<option value="" disabled selected>Marital Status</option>`;
                        data.marital_statuses.forEach(m => {
                            const option = document.createElement("option");
                            option.value = m.id;
                            option.textContent = m.status_name;
                            maritalSelect.appendChild(option);
                        });
                    } else {
                        console.error("Failed to load dropdown options.");
                    }
                })
                .catch(error => {
                    console.error("AJAX error:", error);
                });
        });

        const form = document.getElementById('registerForm');
        const steps = document.querySelectorAll('.wizard-step');
        const progressItems = document.querySelectorAll('.wizard-progress li');
        let currentStep = 0;

        function showStep(index) {
            steps.forEach((step, i) => {
                step.classList.toggle('active', i === index);
                progressItems[i].classList.toggle('active', i === index);
                progressItems[i].classList.toggle('completed', i < index);
            });
        }

        form.addEventListener('click', (e) => {
            if (e.target.matches('.btn-next')) {
                const inputs = steps[currentStep].querySelectorAll('input, select');
                let valid = true;

                // Validate required inputs
                inputs.forEach(inp => {
                    if (!inp.checkValidity()) valid = false;
                });

                if (!valid) {
                    steps[currentStep].querySelector('input').reportValidity();
                    return;
                }

                // Only run AJAX validation if it's Step 2 (index 1)
                if (currentStep === 1) {
                    const formData = new FormData(form);

                    fetch('../controllers/UserController.php?action=validatePreRegistration', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    html: '<ul style="text-align:left;">' + data.messages.map(msg => `<li>${msg}</li>`).join('') + '</ul>',
                                });
                            } else {
                                // Go to next step if validated
                                currentStep = Math.min(currentStep + 1, steps.length - 1);
                                showStep(currentStep);
                            }
                        })
                        .catch(error => {
                            console.error('Validation failed:', error);
                        });

                    return; // wait for AJAX before proceeding
                }

                // Normal step transition for other steps
                currentStep = Math.min(currentStep + 1, steps.length - 1);
                showStep(currentStep);
            }

            if (e.target.matches('.btn-prev')) {
                currentStep = Math.max(currentStep - 1, 0);
                showStep(currentStep);
            }
        });
    </script>
</body>

</html>