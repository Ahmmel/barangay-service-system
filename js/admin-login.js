window.addEventListener("DOMContentLoaded", () => {
  const html = document.documentElement;
  const icon = document.querySelector(".dark-toggle i");
  const isDark = html.getAttribute("data-theme") === "dark";
  icon.classList.add(isDark ? "fa-sun" : "fa-moon");
});

function toggleDarkMode() {
  const html = document.documentElement;
  const icon = document.querySelector(".dark-toggle i");
  const isDark = html.getAttribute("data-theme") === "dark";

  if (isDark) {
    html.setAttribute("data-theme", "light");
    icon.classList.remove("fa-sun");
    icon.classList.add("fa-moon");
  } else {
    html.setAttribute("data-theme", "dark");
    icon.classList.remove("fa-moon");
    icon.classList.add("fa-sun");
  }
}

particlesJS("particles-js", {
  particles: {
    number: {
      value: 60,
      density: { enable: true, value_area: 800 },
    },
    color: { value: "#bd5932" },
    shape: { type: "circle" },
    opacity: { value: 0.4, random: true },
    size: { value: 4, random: true },
    line_linked: {
      enable: true,
      distance: 150,
      color: "#bd5932",
      opacity: 0.2,
      width: 1,
    },
    move: {
      enable: true,
      speed: 2,
      bounce: true,
    },
  },
  interactivity: {
    events: {
      onhover: { enable: true, mode: "repulse" },
      onclick: { enable: true, mode: "push" },
      resize: true,
    },
    modes: {
      repulse: { distance: 100 },
      push: { particles_nb: 4 },
    },
  },
  retina_detect: true,
});

const form = document.getElementById("login-form");
const errorBox = document.getElementById("login-error");
const errorText = document.getElementById("error-text");

form.addEventListener("submit", async function (e) {
  e.preventDefault();
  errorBox.style.display = "none";
  errorBox.classList.remove("slide-in");

  const formData = new FormData(form);
  try {
    const response = await fetch("../controllers/AuthController.php", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      Swal.fire({
        icon: "success",
        title: "Login Successful!",
        text: "Redirecting to dashboard...",
        showConfirmButton: false,
        timer: 1500,
      }).then(() => {
        if (result.redirect) {
          window.location.href = result.redirect;
        }
      });
    } else {
      errorText.textContent = result.message;
      errorBox.style.display = "flex";
      void errorBox.offsetWidth;
      errorBox.classList.add("slide-in");
    }
  } catch (err) {
    errorText.textContent = "Something went wrong. Please try again.";
    errorBox.style.display = "flex";
    void errorBox.offsetWidth;
    errorBox.classList.add("slide-in");
  }
});
