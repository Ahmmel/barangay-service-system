$(function () {
  const $year = $("#year");
  const $container = $(".container");
  const $loginForm = $("#loginForm");
  const $registerForm = $("#registerForm");
  const $forgotForm = $("#forgotForm");
  const $registerBtn = $(".register-btn");
  const $loginBtn = $(".login-btn");
  const $forgotBtn = $(".forgot-btn");

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
    const email = $("#loginUsername").val();
    const password = $("#loginPassword").val();
    const rememberMe = $("#rememberMe").is(":checked");

    $.post("../controllers/AuthController.php", {
      email,
      password,
      rememberMe,
    })
      .done((response) => {
        let data;
        try {
          data = JSON.parse(response);
        } catch {
          return showError("Invalid server response. Please try again.");
        }

        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Login Successful",
            text: "Redirecting...",
            showConfirmButton: false,
            timer: 1200,
          }).then(() => {
            window.location.href = data.redirect || "index.php";
          });
        } else {
          showError(data.message || "Login failed. Try again.");
        }
      })
      .fail(() => showError("An error occurred, please try again later."));
  });

  // REGISTER
  $registerForm.on("submit", function (e) {
    e.preventDefault();
    const username = $("#registerUsername").val();
    const email = $("#registerEmail").val();
    const password = $("#registerPassword").val();
    const confirmPassword = $("#confirmPassword").val();

    if (password !== confirmPassword) {
      return Swal.fire({
        icon: "warning",
        title: "Password Mismatch",
        text: "Passwords do not match.",
        confirmButtonColor: "#ff6f3c",
      });
    }

    $.post("../controllers/UserController.php?action=register", {
      username,
      email,
      password,
    })
      .done((response) => {
        let data;
        try {
          data = JSON.parse(response);
        } catch {
          return showError("Invalid server response. Please try again.");
        }

        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Registration Successful",
            text: "Redirecting to login...",
            timer: 1500,
            showConfirmButton: false,
          }).then(() => $loginBtn.click());
        } else {
          showError(data.message || "Registration failed. Try again.");
        }
      })
      .fail(() => showError("An error occurred, please try again later."));
  });

  // FORGOT PASSWORD AJAX
  $forgotForm.on("submit", function (e) {
    e.preventDefault();
    const email = $("#forgotEmail").val();

    if (!email) {
      return Swal.fire({
        icon: "warning",
        title: "Email Required",
        text: "Please enter your registered email address.",
        confirmButtonColor: "#ff6f3c",
      });
    }

    $.post("../controllers/UserController.php?action=resetPassword", {
      action: "forgot-password",
      email: email,
    })
      .done((response) => {
        let data;
        try {
          data = JSON.parse(response);
        } catch {
          return showForgotError("Invalid server response. Please try again.");
        }

        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Password Reset Successful",
            text: "A new password has been sent to your registered mobile number. Please check your SMS and use the new password to log in.",
            confirmButtonColor: "#ff6f3c",
          }).then(() => {
            $(".login-btn").click(); // return to login
          });
        } else {
          showForgotError(data.message || "No account found with that email.");
        }
      })
      .fail(() =>
        showForgotError("An error occurred, please try again later.")
      );
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
