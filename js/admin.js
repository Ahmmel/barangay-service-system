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
})(jQuery); // End of use strict
