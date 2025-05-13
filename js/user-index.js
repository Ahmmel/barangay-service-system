document.addEventListener("DOMContentLoaded", () => {
  const MAX_SERVICES = 3;
  const userId = window.userId;

  // Cached elements
  const body = document.body;
  const logoutLink = document.querySelector(".logout-link");
  const changeForm = document.getElementById("changePasswordForm");
  const $serviceSelect = $("#brgy-services");
  const $dateTimeInput = $("#datetime-picker");
  const $ratingStars = $("#rating .rate");
  const $modalReview = $("#reviewModal");
  const $txnTableBody = $("#transactions table tbody");

  // Utility to show Toasts and Dialogs
  const toast = (msg, icon = "success", timer = 2000) =>
    Swal.fire({
      toast: true,
      icon,
      title: msg,
      position: "top-end",
      showConfirmButton: false,
      timer,
      timerProgressBar: true,
    });
  const confirmDialog = (opts) =>
    Swal.fire({
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#BD5D38",
      cancelButtonText: "Cancel",
      reverseButtons: true,
      ...opts,
    });

  // Initialize Select2
  $serviceSelect.select2({
    placeholder: "Select up to 3 services",
    maximumSelectionLength: MAX_SERVICES,
    allowClear: true,
  });

  // Global click handler
  body.addEventListener("click", (e) => {
    const t = e.target;
    // Nav-link active toggle
    if (t.matches(".js-scroll-trigger")) {
      document
        .querySelectorAll(".nav-link.active")
        .forEach((el) => el.classList.remove("active"));
      t.classList.add("active");
    }
    // Password show/hide
    if (t.matches(".toggle-password")) {
      const input = t.closest(".input-with-icon").querySelector("input");
      input.type = input.type === "password" ? "text" : "password";
      t.classList.toggle("bi-eye");
      t.classList.toggle("bi-eye-slash");
    }
    // Limit service checkboxes
    if (t.matches("input[name='services']")) {
      if (
        document.querySelectorAll("input[name='services']:checked").length >
        MAX_SERVICES
      ) {
        t.checked = false;
        toast(
          `You can select a maximum of ${MAX_SERVICES} services.`,
          "warning"
        );
      }
    }
  });

  // Logout confirmation
  logoutLink?.addEventListener("click", (e) => {
    e.preventDefault();
    confirmDialog({
      title: "Are you sure you want to log out?",
      confirmButtonText: "Yes, log me out",
    }).then((res) => {
      if (res.isConfirmed) {
        confirmDialog({
          title: "Logging you out…",
          timer: 400,
          didOpen: Swal.showLoading,
        }).then(() => (window.location.href = logoutLink.href));
      }
    });
  });

  // Change Password form
  changeForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    const { oldPassword, newPassword, confirmPassword } = changeForm;
    if (newPassword.value !== confirmPassword.value) {
      return toast("New passwords do not match!", "danger");
    }
    $.ajax({
      url: "../controllers/UserController.php?action=changePassword",
      method: "POST",
      data: {
        userId,
        oldPassword: oldPassword.value,
        newPassword: newPassword.value,
      },
      dataType: "json",
    })
      .done((data) => {
        if (data.success) {
          toast("Password changed successfully!", "success");
          $("#changePasswordModal").modal("hide");
          changeForm.reset();
        } else {
          toast(data.message || "Failed to change password.", "danger");
        }
      })
      .fail(() => toast("Something went wrong. Please try again.", "danger"));
  });

  // Rating Modal
  let currentTxn = "";
  let selectedRating = 0;
  window.openReviewModal = (code) => {
    currentTxn = code;
    $("#transactionIdText").text(`Transaction: ${code}`);
    selectedRating = 0;
    $ratingStars.removeClass("selected");
    $modalReview.modal("show");
  };
  $ratingStars.on("click", function () {
    $ratingStars.removeClass("selected");
    $(this).addClass("selected");
    selectedRating = $(this).data("value");
  });
  $("#submitRatingBtn").on("click", () => {
    if (!selectedRating) {
      return toast("Please select a rating before submitting!", "warning");
    }
    $modalReview.modal("hide");
    $.ajax({
      url: "../controllers/TransactionController.php?action=rateTransaction",
      method: "POST",
      data: {
        transaction_code: currentTxn,
        rating: selectedRating,
      },
      dataType: "json",
    })
      .done((res) => {
        if (res.success) {
          const stars = [...Array(5)]
            .map((_, i) => (i < selectedRating ? "★" : "☆"))
            .join("");
          $txnTableBody
            .find(`td:contains(${currentTxn})`)
            .filter((i, td) => td.textContent === currentTxn)
            .siblings()
            .last()
            .html(`<span style="font-size:1.2rem">${stars}</span>`);
          toast("Thank you for your review!");
        } else {
          confirmDialog({
            title: "Failed!",
            text: res.message || "Unable to submit rating.",
            icon: "error",
          });
        }
      })
      .fail(() =>
        confirmDialog({
          title: "Server Error!",
          text: "Please try again later.",
          icon: "error",
        })
      );
  });

  // Multi-step Wizard
  const MAX_STEPS = 3;

  // showStep: highlights step n
  window.showStep = (n) => {
    $(".step, .progress-step").removeClass("active");
    $(`#step-${n}, #bar-step-${n}`).addClass("active");

    // If we're on the confirmation screen, populate the summary
    if (n === MAX_STEPS) {
      const ids = $serviceSelect.val() || [];
      const dtRaw = $dateTimeInput.val().replace(/-/g, "/");
      const dt = new Date(dtRaw).toLocaleString("en-PH", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
      });

      $("#confirmation-list").html(
        ids
          .map((id) => {
            const text = $serviceSelect.find(`option[value="${id}"]`).text();
            return `<li class="list-group-item">${text}</li>`;
          })
          .join("")
      );
      $("#confirm-datetime").text(dt);
    }
  };

  // nextStep: validates and advances, or bounces back to the same step
  window.nextStep = (currentStep) => {
    // step 1 → must pick at least one service
    if (currentStep === 1 && !$serviceSelect.val().length) {
      return confirmDialog({
        title: "Select Services",
        text: "Please select at least one service.",
      }).then(() => showStep(1));
    }

    // step 2 → must pick a date/time
    if (currentStep === 2 && !$dateTimeInput.val().trim()) {
      return confirmDialog({
        title: "Select Date and Time",
        text: "Please pick a date/time.",
      }).then(() => showStep(2));
    }

    // otherwise move forward
    showStep(currentStep + 1);
  };

  // Booking Confirmation
  window.confirmBooking = () => {
    const services = $serviceSelect.val() || [];
    const dtRaw = $dateTimeInput.val().trim();

    // Guard: both services and date/time are required
    if (!services.length || !dtRaw) {
      const missingStep = !services.length ? 1 : 2;
      return confirmDialog({
        title: "Incomplete!",
        text: "Select services and date/time.",
      }).then(() => showStep(missingStep));
    }

    // Disable button to prevent duplicates
    const $btn = $("#confirmBookingButton");
    $btn.prop("disabled", true);

    $.ajax({
      url: "../controllers/TransactionController.php?action=add",
      method: "POST",
      data: {
        serviceIds: services,
        scheduledTime: dtRaw,
        userId,
        transactionType: 2,
      },
      dataType: "json",
    })
      .done((res) => {
        if (res.success) {
          confirmDialog({
            title: "Booking Confirmed!",
            text: "Thank you for your request.",
            icon: "success",
          }).then(() => {
            // 1) Clear the wizard fields
            $serviceSelect.val([]).trigger("change"); // if using Select2; otherwise just .val([])
            $dateTimeInput.val("");

            // 2) Reset to step 1
            showStep(1);

            // 3) Reload their transactions
            loadTransactions(userId);
          });
        } else {
          // Jump back to the step most likely at fault
          const errorStep =
            res.error_type === "schedule"
              ? 2
              : res.error_type === "service"
              ? 1
              : MAX_STEPS;

          confirmDialog({
            title: "Booking Failed",
            html: res.message,
            icon: "error",
          }).then(() => showStep(errorStep));
        }
      })
      .fail(() =>
        // network/server error: let them retry at confirmation
        confirmDialog({
          title: "Server Error",
          text: "Please try again later.",
          icon: "error",
        }).then(() => showStep(MAX_STEPS))
      )
      .always(() => {
        // Re-enable the button
        $btn.prop("disabled", false);
      });
  };

  // ==== Transactions Loader & Nav-trigger ====
  function loadTransactions(uid) {
    $.ajax({
      url: "../controllers/TransactionController.php?action=getTransactionsByUserId",
      method: "POST",
      data: { userId: uid },
      dataType: "json",
    })
      .done(handleResponse)
      .fail(handleError);
  }

  function handleResponse(res) {
    if (!res.success) {
      return confirmDialog({
        title: "Failed to Load",
        text: res.message,
        icon: "error",
      });
    }
    const rows = (res.transactions || []).map(renderRow);
    $txnTableBody.html(
      rows.join("") ||
        '<tr><td colspan="5" class="text-center">No transactions found.</td></tr>'
    );
    setTimeout(() => {
      document
        .getElementById("transactions")
        ?.scrollIntoView({ behavior: "smooth" });
    }, 200);
  }

  function handleError() {
    confirmDialog({
      title: "Server Error",
      text: "Unable to load transactions.",
      icon: "error",
    });
  }

  function renderRow(tx) {
    let badges = "";
    try {
      JSON.parse(`[${tx.services}]`).forEach((s) => {
        badges += `<span class="badge bg-primary me-1 mb-1">${s.name}</span>`;
      });
    } catch {
      badges = `<span class="text-danger">Error loading services</span>`;
    }

    const btn =
      tx.status === "Closed"
        ? `<button class="btn btn-outline-primary btn-sm" onclick="openReviewModal('${tx.transaction_code}')">Leave a Review</button>`
        : `<button class="btn btn-outline-secondary btn-sm" disabled>Not Available</button>`;

    const date = new Date(tx.created_at).toLocaleDateString("en-PH", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });

    const statusClass =
      tx.status === "Closed"
        ? "bg-success"
        : tx.status === "Pending"
        ? "bg-warning text-dark"
        : "bg-info text-dark";

    return `
        <tr>
          <td>${tx.transaction_code}</td>
          <td>${badges}</td>
          <td><span class="badge ${statusClass}">${tx.status}</span></td>
          <td>${date}</td>
          <td>${btn}</td>
        </tr>
      `;
  }

  // Expose globally
  window.loadTransactions = loadTransactions;

  // Load only when Transactions tab is clicked
  document.querySelectorAll(".js-scroll-trigger").forEach((link) => {
    link.addEventListener("click", () => {
      if (link.getAttribute("data-target") === "#transactions") {
        loadTransactions(userId);
      }
    });
  });

  // — No auto-load on DOMContentLoaded —
});
