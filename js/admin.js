(function ($) {
  "use strict"; // Start of use strict

  // Function to toggle sidebar and update margin
  function updateSidebarMargin() {
    // Check if the sidebar is toggled (hidden or visible)
    const isSidebarToggled = $(".sidebar").hasClass("toggled");

    // Set the margin based on the sidebar visibility
    const marginValue = isSidebarToggled ? "0 0 28rem" : "0 0 31.5rem";

    // Apply the margin with !important using setProperty
    $(".toggle-sidebar-divider")[0].style.setProperty(
      "margin",
      marginValue,
      "important"
    );
  }

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on("click", function () {
    // Toggle the 'sidebar-toggled' class on the body and 'toggled' on the sidebar
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");

    // Update the margin of the divider
    updateSidebarMargin();
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function () {
    if ($(window).width() < 768) {
      $(".sidebar .collapse").collapse("hide");
    }

    // Toggle the side navigation when window is resized below 480px
    if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
      $("body").addClass("sidebar-toggled");
      $(".sidebar").addClass("toggled");
      $(".sidebar .collapse").collapse("hide");
    }
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $("body.fixed-nav .sidebar").on(
    "mousewheel DOMMouseScroll wheel",
    function (e) {
      if ($(window).width() > 768) {
        var e0 = e.originalEvent,
          delta = e0.wheelDelta || -e0.detail;
        this.scrollTop += (delta < 0 ? 1 : -1) * 30;
        e.preventDefault();
      }
    }
  );

  // Scroll to top button appear
  $(document).on("scroll", function () {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $(".scroll-to-top").fadeIn();
    } else {
      $(".scroll-to-top").fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on("click", "a.scroll-to-top", function (e) {
    var $anchor = $(this);
    $("html, body")
      .stop()
      .animate(
        {
          scrollTop: $($anchor.attr("href")).offset().top,
        },
        1000,
        "easeInOutExpo"
      );
    e.preventDefault();
  });

  // Store the original margin
  var originalMargin = "0 1rem 28.5rem";

  // Listen for when the collapse is shown (open)
  $("#queueManagement").on("shown.bs.collapse", function () {
    var element = $(".toggle-sidebar-divider")[0]; // Get the actual DOM element
    // When the collapse is open, apply the modified margin
    element.style.setProperty("margin", "0 1rem 26.5rem", "important");
  });

  // Listen for when the collapse is hidden (closed)
  $("#queueManagement").on("hidden.bs.collapse", function () {
    var element = $(".toggle-sidebar-divider")[0]; // Get the actual DOM element
    // When the collapse is closed, restore the original margin
    element.style.setProperty("margin", originalMargin, "important");
  });

  // Cache the elements for reuse
  const $walkinConfirmedBtn = $("#walkinConfirmed");
  const $walkinNoShowBtn = $("#walkinNoShow");
  const $walkinDoneNextBtn = $("#walkinDoneNext");

  // Ensure the buttons exist before adding event listeners
  if (
    $walkinConfirmedBtn.length &&
    $walkinNoShowBtn.length &&
    $walkinDoneNextBtn.length
  ) {
    // Event listener for "Confirmed" button
    $walkinConfirmedBtn.on("click", function () {
      // Disable the button to prevent multiple clicks
      $(this).prop("disabled", true);

      // Change "Confirmed" to "Cancelled" and show "Done & Next" button
      $walkinConfirmedBtn.text("Set To Pending");
      $walkinNoShowBtn.hide(); // Hide "No Show" button
      $walkinDoneNextBtn.show(); // Show "Done & Next" button

      // Re-enable the button after the change (optionally after a delay)
      setTimeout(() => {
        $walkinConfirmedBtn.prop("disabled", false);
      }, 500); // Adjust delay time as necessary
    });

    // Event listener for "No Show" button
    $walkinNoShowBtn.on("click", function () {
      // Disable the button to prevent multiple clicks
      $(this).prop("disabled", true);

      // Hide both "Confirmed" and "No Show" buttons when "No Show" is clicked
      $walkinConfirmedBtn.hide();
      $walkinNoShowBtn.hide();
      $walkinDoneNextBtn.show(); // Show "Done & Next" button

      // Re-enable the button after the change (optionally after a delay)
      setTimeout(() => {
        $walkinNoShowBtn.prop("disabled", false);
      }, 500); // Adjust delay time as necessary
    });
  }
})(jQuery); // End of use strict
