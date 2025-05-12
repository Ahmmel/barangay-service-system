$(function () {
  const $year = $("#year");
  const $container = $(".container");
  const $loginForm = $("#loginForm");
  const $registerForm = $("#registerForm");
  const $forgotForm = $("#forgotForm");
  const $registerBtn = $(".register-btn");
  const $loginBtn = $(".login-btn");
  const $forgotBtn = $(".forgot-btn");
  const methodToggle = document.getElementById("methodToggle");

  // Set current year
  $year.text(new Date().getFullYear());

  // Toggle Forms
  $registerBtn.on("click", () => {
    $registerForm[0].reset();
    $container.removeClass("forgot-mode").addClass("active");
  });

  $loginBtn.on("click", () => {
    $loginForm[0].reset();
    $container.removeClass("active").removeClass("forgot-mode");
  });

  // Toggle Forgot Password
  $forgotBtn.on("click", () => {
    $container.addClass("forgot-mode");
    $forgotForm[0].reset();
  });

  // LOGIN
  $loginForm.on("submit", function (e) {
    e.preventDefault();
    const identifier = $("#identifier").val();
    const password = $("#loginPassword").val();
    const rememberMe = $("#rememberMe").is(":checked");

    $.post("../controllers/AuthController.php", {
      identifier,
      password,
      rememberMe,
    })
      .done((response) => {
        if (response.success) {
          Swal.fire({
            icon: "success",
            title: "Login Successful",
            text: "Redirecting...",
            showConfirmButton: false,
            timer: 1200,
          }).then(() => {
            window.location.href = response.redirect || "index.php";
          });
        } else {
          showError(response.message || "Login failed. Try again.");
        }
      })
      .fail(() => showError("An error occurred, please try again later."));
  });

  // REGISTER
  $registerForm.on("submit", function (e) {
    e.preventDefault();

    const formArray = $registerForm.serializeArray();
    const formData = {};

    // Convert serialized array to object
    formArray.forEach(({ name, value }) => {
      formData[name] = value.trim();
    });

    // Required field validation
    const requiredFields = [
      "first_name",
      "last_name",
      "gender",
      "birthdate",
      "mobile_number",
      "username",
      "email",
      "password",
      "confirm_password",
    ];
    console.log(requiredFields);
    for (const field of requiredFields) {
      if (!formData[field]) {
        return Swal.fire({
          icon: "warning",
          title: "Incomplete Form",
          text: "Please complete all required fields before submitting.",
          confirmButtonColor: "#ff6f3c",
        });
      }
    }

    // Password match check
    if (formData.password !== formData.confirm_password) {
      return Swal.fire({
        icon: "warning",
        title: "Password Mismatch",
        text: "Passwords do not match.",
        confirmButtonColor: "#ff6f3c",
      });
    }

    // Prepare payload for backend (excluding confirm_password)
    const payload = {
      firstName: formData.first_name,
      lastName: formData.last_name,
      gender: formData.gender,
      birthdate: formData.birthdate,
      mobileNumber: formData.mobile_number,
      username: formData.username,
      email: formData.email,
      password: formData.password,
    };

    // Submit data
    $.ajax({
      url: "../controllers/UserController.php?action=register",
      type: "POST",
      data: payload,
      dataType: "json", // This tells jQuery to expect and parse JSON automatically
      success: function (data) {
        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Registration Successful",
            text: "Redirecting to login...",
            timer: 1500,
            showConfirmButton: false,
          }).then(() => $loginBtn.click());
        } else {
          showError(data.message || "Registration failed. Please try again.");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Registration error:", textStatus, errorThrown);
        showError(
          "An error occurred while submitting the form. Please try again."
        );
      },
    });
  });

  // FORGOT PASSWORD AJAX
  $forgotForm.on("submit", function (e) {
    e.preventDefault();

    const isMobile = methodToggle.checked; // true → SMS, false → Email
    const emailVal = $("#forgotEmail").val().trim();
    const mobileVal = $("#forgotMobile").val().trim();
    const contact = isMobile ? mobileVal : emailVal;

    // Validate
    if (!contact) {
      Swal.fire({
        icon: "warning",
        title: isMobile ? "Mobile Number Required" : "Email Required",
        text: isMobile
          ? "Please enter your registered mobile number."
          : "Please enter your registered email address or username.",
        confirmButtonColor: "#ff6f3c",
      });
      return;
    }

    // Payload with explicit flag
    const payload = {
      isMobile: isMobile,
      contact: contact,
    };

    $.ajax({
      url: "../controllers/UserController.php?action=resetPassword",
      method: "POST",
      data: payload,
      dataType: "json",
    })
      .done((data) => {
        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Password Reset Requested",
            text: isMobile
              ? "A reset code has been sent via SMS. Please check your mobile."
              : "An email has been sent with instructions to reset your password.",
            confirmButtonColor: "#ff6f3c",
          }).then(() => $(".login-btn").click());
        } else {
          Swal.fire({
            icon: "error",
            title: "Reset Failed",
            text: data.message || "No account found with those credentials.",
            confirmButtonColor: "#ff6f3c",
          });
        }
      })
      .fail(() => {
        Swal.fire({
          icon: "error",
          title: "Server Error",
          text: "An error occurred, please try again later.",
          confirmButtonColor: "#ff6f3c",
        });
      });
  });

  // Helper for forgot error
  const showForgotError = (msg) => {
    Swal.fire({
      icon: "error",
      title: "Reset Failed",
      text: msg,
      confirmButtonColor: "#ff6f3c",
    });
  };

  // SweetAlert Error Handler
  const showError = (msg) => {
    Swal.fire({
      icon: "error",
      title: "Oops!",
      text: msg,
      confirmButtonColor: "#ff6f3c",
    });
  };
});

const container = document.querySelector(".forgot-password");
const toggle = container.querySelector("#methodToggle");
const inputs = container.querySelectorAll(".contact-input");

toggle.addEventListener("change", () => {
  const useSms = toggle.checked;
  inputs.forEach((el) => {
    const isMobile = el.getAttribute("data-method") === "mobile";
    el.classList.toggle("active", isMobile === useSms);
    el.querySelector("input").required = isMobile === useSms;
  });
});

// Toggle Dark Mode
function toggleDarkMode() {
  const html = document.documentElement;
  const icon = document.querySelector(".dark-toggle i");
  const isDark = html.getAttribute("data-theme") === "dark";
  html.setAttribute("data-theme", isDark ? "light" : "dark");
  icon.classList.toggle("bx-sun", !isDark);
  icon.classList.toggle("bx-moon", isDark);
}

// Particles.js Config
const isDark = document.documentElement.getAttribute("data-theme") === "dark";
particlesJS("particles-js", {
  particles: {
    number: {
      value: 80,
      density: {
        enable: true,
        value_area: 800,
      },
    },
    color: {
      value: isDark
        ? ["#ffaa85", "#ff6f3c", "#ffd280", "#ffffff"]
        : ["#ff6f3c", "#ffaa85", "#ffc98b", "#cccccc"],
    },
    shape: {
      type: "circle",
    },
    opacity: {
      value: 0.6,
      random: true,
      anim: {
        enable: true,
        speed: 0.6,
        opacity_min: 0.1,
        sync: false,
      },
    },
    size: {
      value: 3,
      random: true,
      anim: {
        enable: true,
        speed: 2,
        size_min: 0.5,
        sync: false,
      },
    },
    line_linked: {
      enable: true,
      distance: 150,
      color: "#ffffff",
      opacity: 0.3,
      width: 1,
    },
    move: {
      enable: true,
      speed: 2,
      direction: "none",
      random: true,
      straight: false,
      out_mode: "out",
      bounce: false,
    },
  },
  interactivity: {
    detect_on: "canvas",
    events: {
      onhover: {
        enable: true,
        mode: "repulse",
      },
      onclick: {
        enable: true,
        mode: "push",
      },
      resize: true,
    },
    modes: {
      repulse: {
        distance: 100,
        duration: 0.4,
      },
      push: {
        particles_nb: 4,
      },
    },
  },
  retina_detect: true,
});
