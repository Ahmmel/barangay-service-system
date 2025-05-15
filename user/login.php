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

    <style>
        /* Scoped under .forgot-password to avoid conflicts */
        .forgot-password .toggle-switch {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            user-select: none;
        }

        .forgot-password .toggle-switch input[type="checkbox"] {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .forgot-password .switch-label {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .forgot-password .switch-label .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #ddd;
            border-radius: 30px;
            transition: background-color 0.3s;
        }

        .forgot-password .switch-label .slider::before {
            content: "";
            position: absolute;
            width: 26px;
            height: 26px;
            left: 2px;
            top: 2px;
            background-color: #fff;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .forgot-password .toggle-switch input:checked+.switch-label .slider {
            background-color: #007bff;
        }

        .forgot-password .toggle-switch input:checked+.switch-label .slider::before {
            transform: translateX(30px);
        }

        .forgot-password .contact-input {
            display: block;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .forgot-password .contact-input.active {
            display: flex;
        }

        .forgot-password .contact-input input {
            flex: 1;
        }

        /* Wizard Container */
        .wizard .container {
            margin: 0 auto;
            padding: 2.5rem .5rem .5rem .5rem;
            width: 100%;
            max-width: 500px;
            background: #fff;
            box-shadow: none;
            display: flex;
            flex-direction: column;
            border-radius: 8px;
        }

        /* Progress Bar */
        .wizard-progress {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            list-style: none;
            padding: 0;
            margin-bottom: 2rem;
            counter-reset: step;
        }

        .wizard-progress li {
            position: relative;
            flex: 1 1 25%;
            min-width: 60px;
            text-align: center;
            color: #aaa;
            padding: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .wizard-progress li::before {
            counter-increment: step;
            content: counter(step);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            margin-bottom: 0.5rem;
            border: 2px solid #aaa;
            border-radius: 50%;
            font-size: 1rem;
            background: #fff;
            color: #aaa;
            transition: all 0.3s ease;
        }

        .wizard-progress li::after {
            content: '';
            position: absolute;
            top: 1rem;
            left: calc(50% + 1rem);
            right: calc(-50% + 1rem);
            height: 2px;
            background: #aaa;
            z-index: -1;
            transition: background 0.3s ease;
        }

        .wizard-progress li:first-child::after {
            left: 50%;
        }

        .wizard-progress li:last-child::after {
            right: 50%;
        }

        .wizard-progress li.active,
        .wizard-progress li.completed {
            color: #ff6f3c;
        }

        .wizard-progress li.active::before,
        .wizard-progress li.completed::before {
            border-color: #ff6f3c;
            background: #ff6f3c;
            color: #fff;
        }

        .wizard-progress li.completed::after {
            background: #ff6f3c;
        }

        /* Steps */
        .wizard-step {
            display: none;
            width: 100%;
        }

        .wizard-step.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Inputs */
        .wizard-step .input-group {
            position: relative;
            margin-bottom: .90rem;
        }

        .wizard-step .input-group input,
        .wizard-step .input-group select {
            width: 100%;
            padding: 0.75rem 2.5rem 0.75rem 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .wizard-step .input-group input:focus,
        .wizard-step .input-group select:focus {
            border-color: #ff6f3c;
            outline: none;
        }

        .wizard-step .input-group i {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 1.1rem;
            color: #888;
        }

        /* Buttons */
        .wizard-step .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: space-between;
            margin-top: 1.2rem;
        }

        .wizard-step .buttons .btn {
            flex: 1 1 48%;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-align: center;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .wizard-step .btn-prev {
            background: #ccc;
            color: #333;
        }

        .wizard-step .btn-prev:hover {
            background: #bbb;
        }

        .wizard-step .btn-next,
        .wizard-step .btn-submit {
            background: #ff6f3c;
            color: #fff;
        }

        .wizard-step .btn-next:hover,
        f .wizard-step .btn-submit:hover {
            background: #e65a2e;
        }

        /* Responsive Adjustments */
        @media (max-width: 480px) {
            .wizard-progress {
                margin-bottom: 1.5rem;
            }

            .wizard-progress li {
                flex: 1 1 50%;
                margin-bottom: 1rem;
            }

            .wizard-progress li::after {
                display: none;
            }

            .wizard-step .buttons .btn {
                flex: 1 1 100%;
            }
        }
    </style>
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
            <div class="container">
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