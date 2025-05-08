$(function () {
  const $year = $("#year");
  const $container = $(".container");
  const $loginForm = $("#loginForm");
  const $registerForm = $("#registerForm");
  const $forgotForm = $("#forgotForm");
  const $loginError = $("#loginError");
  const $responseMessage = $("#responseMessage");
  const $registerBtn = $(".register-btn");
  const $loginBtn = $(".login-btn");
  const $forgotBtn = $(".forgot-btn");
  const $forgotMessage = $("#forgotMessage");

  // Set current year
  $year.text(new Date().getFullYear());

  // Toggle Forms
  $registerBtn.on("click", () => {
    $registerForm[0].reset();
    clearForgotPasswordError();
    clearLoginError();
    $container.removeClass("forgot-mode").addClass("active");
  });

  $loginBtn.on("click", () => {
    $loginForm[0].reset();
    clearForgotPasswordError();
    clearResponseMessage();
    $container.removeClass("active").removeClass("forgot-mode");
  });

  // Toggle Forgot Password
  $forgotBtn.on("click", () => {
    $container.addClass("forgot-mode");
    $forgotForm[0].reset();
    clearLoginError();
    clearResponseMessage();
  });

  // Login submission
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
          return showLoginError("Invalid server response. Please try again.");
        }
        data.success
          ? (window.location.href = data.redirect || "index.php")
          : showLoginError(data.message || "Login failed. Try again.");
      })
      .fail(() => showLoginError("An error occurred, please try again later."));
  });

  // Register submission
  $registerForm.on("submit", function (e) {
    e.preventDefault();

    const username = $("#registerUsername").val();
    const email = $("#registerEmail").val();
    const password = $("#registerPassword").val();
    const confirmPassword = $("#confirmPassword").val();

    if (password !== confirmPassword) {
      return setResponseMessage("Passwords do not match");
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
          return setResponseMessage(
            "Invalid server response. Please try again."
          );
        }

        if (data.success) {
          $responseMessage
            .css("color", "green")
            .hide()
            .text("Registration successful! Redirecting to login...")
            .fadeIn(300);
          setTimeout(() => $loginBtn.click(), 1500);
        } else {
          setResponseMessage(data.message || "Registration failed. Try again.");
        }
      })
      .fail(() =>
        setResponseMessage("An error occurred, please try again later.")
      );
  });

  // Utility Functions
  const showLoginError = (msg) => {
    $loginError.stop(true, true).fadeOut(150, function () {
      $(this).text(msg).addClass("form-error").fadeIn(200);
    });
  };

  const clearLoginError = () => {
    $loginError.stop(true, true).fadeOut(150, function () {
      $(this).text("").hide();
    });
  };

  const clearForgotPasswordError = () => {
    $forgotMessage.stop(true, true).fadeOut(150, function () {
      $(this).text("").hide();
    });
  };

  const setResponseMessage = (msg, color = "red") => {
    $responseMessage.show().css("color", color).text(msg);
  };

  const clearResponseMessage = () => {
    $responseMessage.stop(true, true).fadeOut(150, function () {
      $(this).text("").hide();
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
