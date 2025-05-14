// Get references to the password and confirmPassword inputs
const passwordField = document.querySelector(".password");
const confirmPasswordField = document.querySelector(".confirmPassword");

// Function to check the password field and toggle the 'required' attribute on confirmPassword
function checkPassword() {
  if (passwordField.value.trim() !== "") {
    // If password is not empty, make confirmPassword required
    confirmPasswordField.setAttribute("required", "required");
  } else {
    // If password is empty, remove the required attribute
    confirmPasswordField.removeAttribute("required");
  }
}

// Add event listener to check password field on every input change
passwordField.addEventListener("input", checkPassword);

// Run the function once on page load in case password field is pre-filled
checkPassword();

document.addEventListener("DOMContentLoaded", function () {
  const bodyContent = document.querySelector("body"); // Select the entire page

  // Listen for any Bootstrap modal opening
  document.querySelectorAll(".modal").forEach((modal) => {
    modal.addEventListener("shown.bs.modal", function () {
      bodyContent.setAttribute("inert", "true"); // Disable background interaction
    });

    modal.addEventListener("hidden.bs.modal", function () {
      bodyContent.removeAttribute("inert"); // Restore interaction
    });
  });
});

// Handeles ajax Response
function handleAjaxResponse(response, successMessage, errorMessage) {
  var result = JSON.parse(response);

  if (result.success) {
    // SweetAlert2 Success Popup
    Swal.fire({
      title: "Success!",
      text: successMessage,
      icon: "success",
      confirmButtonText: "OK",
      confirmButtonColor: "#28a745", // Green color for success
    });
    return true;
  } else {
    // SweetAlert2 Error Popup
    Swal.fire({
      title: "Error!",
      text: result.message ?? errorMessage,
      icon: "error",
      confirmButtonText: "OK",
      confirmButtonColor: "#dc3545", // Red color for error
    });
    return false;
  }
}

function showErrorException(errorMessage) {
  Swal.fire({
    title: "Error!",
    text: errorMessage,
    icon: "error",
    confirmButtonText: "OK",
    confirmButtonColor: "#dc3545", // Red color for error
  });
}

// User Modals
// Open the User Modal and Populate the Fields
function openAddUserModal() {
  const preview = document.getElementById("preview");
  preview.src = ""; // Clear the image source
  preview.style.display = "none"; // Hide the preview if there was an image before

  $.ajax({
    url: "../controllers/UserController.php?action=getUserPrequisite", // Endpoint to fetch user data
    type: "GET",
    success: function (response) {
      var result = JSON.parse(response); // Parse the JSON response
      if (result.success) {
        // Populate the Role dropdown dynamically
        let roleOptions = "";
        if (!isAdmin) {
          // Only allow role ID 2 and make it selected/locked
          roleOptions = `<option value="2" selected>User</option>`;

          // Set role to disabled in UI
          document.getElementById("role").setAttribute("disabled", "disabled");
          // Insert hidden input with same name to preserve data
          const hiddenInput = document.createElement("input");
          hiddenInput.type = "hidden";
          hiddenInput.name = "role"; // Must match select name
          hiddenInput.value = "2";
          document.getElementById("addUserForm").appendChild(hiddenInput);
        } else {
          result.roles.forEach(function (role) {
            roleOptions += `<option value="${role.id}">${role.role_name}</option>`;
          });
        }

        $("#role").html(roleOptions); // Inject the options into the Role dropdown

        // Populate the Gender dropdown dynamically
        let genderOptions =
          '<option value="" disabled selected>Select gender</option>';
        result.genders.forEach(function (gender) {
          genderOptions += `<option value="${gender.id}">${gender.gender_name}</option>`;
        });

        $("#gender").html(genderOptions); // Inject the options into the Gender dropdown

        // Populate the Gender dropdown dynamically
        let maritalStatusOptions =
          '<option value="" disabled selected>Select marital status</option>';
        result.marital_statuses.forEach(function (maritalStatus) {
          maritalStatusOptions += `<option value="${maritalStatus.id}">${maritalStatus.status_name}</option>`;
        });

        $("#maritalStatus").html(maritalStatusOptions); // Inject the options into the Gender dropdown
        // Open the modal
        $("#addUserModal").modal("show");
      } else {
        showErrorException("Error fetching user prequesite.");
      }
    },
    error: function (xhr, status, error) {
      showErrorException(error);
    },
  });
}

// Open the Edit User Modal
function openEditUserModal(userId) {
  $.ajax({
    url: "../controllers/UserController.php?action=getUserById", // Endpoint to fetch user data
    type: "GET",
    data: {
      user_id: userId, // Send the user ID to fetch data
    },
    success: function (response) {
      var result = JSON.parse(response); // Parse the JSON response
      if (result.success) {
        // Populate the modal with the user data
        $("#editUserId").val(result.user.id);
        $("#editUsername").val(result.user.username);
        $("#editFirstName").val(result.user.first_name);
        $("#editMiddleName").val(result.user.middle_name);
        $("#editLastName").val(result.user.last_name);
        $("#editEmail").val(result.user.email);
        $("#editUsername").val(result.user.username);
        $("#editPhone").val(result.user.phone);
        $("#editAddress").val(result.user.address);
        $("#editMobileNumber").val(result.user.mobile_number);
        $("#editSuffix").val(result.user.suffix);
        $("#editIsVerified").val(result.user.is_verified);

        let birthdate = new Date(result.user.birthdate);
        // Format the date to YYYY-MM-DD
        let formattedDate = birthdate.toISOString().split("T")[0];

        // Populate the Role dropdown dynamically
        let roleOptions = "";
        if (!isAdmin) {
          // Only allow role ID 2 and make it selected/locked
          roleOptions = `<option value="2" selected>User</option>`;
          document.getElementById("role").setAttribute("disabled", "disabled");
        } else {
          // Admins can see all role options
          result.roles.forEach(function (role) {
            roleOptions += `<option value="${role.id}"
            ${role.id == result.user.role_id ? "selected" : ""}>${
              role.role_name
            }</option>`;
          });
        }
        $("#editRole").html(roleOptions); // Inject the options into the Role dropdown

        // Populate the Gender dropdown dynamically
        let genderOptions =
          '<option value="" disabled selected>Select gender</option>';
        result.genders.forEach(function (gender) {
          genderOptions += `<option value="${gender.id}" ${
            gender.id == result.user.gender_id ? "selected" : ""
          }>${gender.gender_name}</option>`;
        });
        $("#editGender").html(genderOptions); // Inject the options into the Gender dropdown

        // Populate the Marital Statuses dropdown dynamically
        let maritalStatusOptions =
          '<option value="" disabled selected>Select marital status</option>';
        result.marital_statuses.forEach(function (maritalStatus) {
          maritalStatusOptions += `<option value="${maritalStatus.id}" ${
            maritalStatus.id == result.user.marital_status_id ? "selected" : ""
          }>${maritalStatus.status_name}</option>`;
        });
        $("#editMaritalStatus").html(maritalStatusOptions); // Inject the options into the Gender dropdown

        // Apply the formatted date to the input field
        $("#editBirthdate").val(formattedDate);

        if (result.user.is_verified > 0) {
          $("#editIsVerified")
            .attr("disabled", true)
            .closest("div.form-group")
            .find("label i")
            .css("color", "green");
        }

        // Check if profile picture is null and set a default based on gender_id
        var profilePicture = result.user.profile_picture;
        if (!profilePicture) {
          profilePicture =
            result.user.gender_id == 2
              ? "../images/default_male.png"
              : "../images/default_female.png";
        }
        // Set the image preview source and ensure it is visible
        $("#editPreview").attr("src", profilePicture).show();

        // Open the modal
        $("#editUserModal").modal("show");
      } else {
        showErrorException("Error fetching user details.");
      }
    },
    error: function (xhr, status, error) {
      showErrorException(error);
    },
  });
}

// Open the modal and set the user ID to delete
function openDeleteUserModal(userId) {
  // Set the user ID in the hidden input field of the modal
  $("#deleteUserId").val(userId);

  // Open the modal
  $("#deleteUserModal").modal("show");
}
//End of User Modals

// Start of Service
// Function to open the Add Service Modal
function openAddServiceModal() {
  $("#addServiceModal").modal("show");
}

// Function to open the Edit Service Modal and populate the fields
function openEditServiceModal(serviceId) {
  $.ajax({
    url: "../controllers/ServiceController.php?action=getServiceById",
    type: "GET",
    data: {
      service_id: serviceId, // Send the service ID to fetch data
    },
    success: function (response) {
      var result = JSON.parse(response); // Parse the JSON response
      if (result.success) {
        // Populate the modal with the service data
        $("#editServiceId").val(result.service.id);
        $("#editServiceName").val(result.service.service_name);
        $("#editDescription").val(result.service.description);

        // Open the modal
        $("#editServiceModal").modal("show");
      } else {
        showErrorException("Error fetching service details.");
      }
    },
    error: function (xhr, status, error) {
      showErrorException(error);
    },
  });
}

// Open the modal and set the user ID to delete
function openDeleteServiceModal(serviceId) {
  // Set the user ID in the hidden input field of the modal
  $("#deleteServiceId").val(serviceId);

  // Open the modal
  $("#deleteServiceModal").modal("show");
}
// End of services

// Start of Service requirements
// Function to open the Add Requirement Modal
function openAddRequirementModal() {
  // First, fetch the available services from the backend
  $.ajax({
    url: "../controllers/RequirementController.php?action=getServices", // Endpoint to fetch all services
    type: "GET",
    success: function (response) {
      var result = JSON.parse(response); // Parse the JSON response
      if (result.length > 0) {
        // Populate the Service dropdown (select option)
        var serviceDropdown = $("#addRequirementServiceId");
        serviceDropdown.empty(); // Clear previous options
        serviceDropdown.append(
          '<option value="" disabled selected>Select Service</option>'
        );

        result.forEach(function (service) {
          serviceDropdown.append(
            '<option value="' +
              service.id +
              '">' +
              service.service_name +
              "</option>"
          );
        });

        // Once services are loaded, open the modal
        $("#addRequirementModal").modal("show");
      } else {
        showErrorException("No services found.");
      }
    },
    error: function (xhr, status, error) {
      showErrorException(error);
    },
  });
}

// Function to open the Edit Requirement Modal and populate the fields
function openEditRequirementModal(requirementId) {
  $.ajax({
    url: "../controllers/RequirementController.php?action=getRequirementById",
    type: "GET",
    data: {
      requirement_id: requirementId, // Send the requirement ID to fetch data
    },
    success: function (response) {
      var result = JSON.parse(response); // Parse the JSON response
      if (result.success) {
        // Populate the modal with the requirement data
        $("#editRequirementId").val(result.requirement.id);
        $("#editRequirementName").val(result.requirement.requirement_name);
        $("#editRequirementDescription").val(result.requirement.description);
        $("#editRequirementServiceId").val(result.requirement.service_id);
        $("#editRequirementServiceId").attr("disabled", true); // Disable the service dropdown

        console.log(result);
        // Populate the services
        var serviceDropdown = $("#editRequirementServiceId");
        serviceDropdown.empty(); // Clear previous options

        // Append the selected service to the dropdown
        result.services.forEach(function (service) {
          serviceDropdown.append(
            '<option value="' +
              service.id +
              '" ' +
              (service.id == result.requirement.service_id ? "selected" : "") +
              ">" +
              service.service_name +
              "</option>"
          );
        });

        // Open the modal
        $("#editRequirementModal").modal("show");
      } else {
        showErrorException("Error fetching requirement details.");
      }
    },
    error: function (xhr, status, error) {
      showErrorException(error);
    },
  });
}

// open the delete requirement modal
function openDeleteRequirementModal(requirementId) {
  // Set the requirement ID in the hidden input field of the modal
  $("#deleteRequirementId").val(requirementId);

  // Open the modal
  $("#deleteRequirementModal").modal("show");
}
// end of service requirements
// Handle the Image Preview Action
function previewImage(event) {
  const preview = document.getElementById("preview");
  const file = event.target.files[0];
  const reader = new FileReader();

  reader.onload = function () {
    preview.src = reader.result;
    preview.style.display = "block"; // Show the image preview
  };

  if (file) {
    reader.readAsDataURL(file);
  }
}

// Handle the Edit Image Preview Action
function editPreviewImage(event) {
  const editPreview = document.getElementById("editPreview");
  const file = event.target.files[0];
  const reader = new FileReader();

  reader.onload = function () {
    editPreview.src = reader.result;
    editPreview.style.display = "block"; // Show the image preview
  };

  if (file) {
    reader.readAsDataURL(file);
  }
}

//Function to open the add Transaction Modal and populate the fields
function openAddTransactionModal() {
  // First, fetch the available services from the backend
  $.ajax({
    url: "../controllers/TransactionController.php?action=getServices", // Endpoint to fetch all services
    type: "GET",
    success: function (response) {
      var result = JSON.parse(response); // Parse the JSON response
      if (result.length > 0) {
        // Populate the Service dropdown (select option)
        var serviceDropdown = $("#addServices");
        serviceDropdown.empty(); // Clear previous options
        result.forEach((service) => {
          serviceDropdown.append(
            '<option value="' +
              service.id +
              '">' +
              service.service_name +
              "</option>"
          );
        });

        // Once services are loaded, show the modal and initialize Select2
        $("#addTransactionModal").modal("show");
      } else {
        showErrorException("No services found.");
      }
    },
    error: function (xhr, status, error) {
      showErrorException(error);
    },
  });
}

// Function to validate the selected services
function validateServices() {
  const services = document.getElementById("addServices");
  const selectedServices = services.selectedOptions.length;

  if (selectedServices < 1 || selectedServices > 3) {
    alert("Please select between 1 and 3 services.");
    return false; // Prevent form submission
  }

  return true; // Allow form submission
}

//function to open search transaction modal
function openSearchTransactionModal() {
  $("#searchTransactionModal").modal("show");
}

$("#searchTransactionForm").submit(function (e) {
  e.preventDefault(); // Prevent default form submission

  var transactionCode = $("#transactionCode").val();

  // You can add validation for the field
  if (transactionCode === "") {
    showErrorException("Please enter a transaction code.");
    return;
  }
});

function shouldShowActions(status) {
  const allowedStatuses = ["Open", "Pending"];
  return allowedStatuses.includes(status);
}

// Function to open the edit transaction modal
function openUpdateTransactionModal(transactionId) {
  $.ajax({
    url: "../controllers/TransactionController.php?action=getTransactionById",
    type: "GET",
    data: { transaction_id: transactionId },
    success: function (response) {
      var result = JSON.parse(response);

      if (result.success) {
        // Create the full name with proper handling of missing fields
        const firstName = result.transaction.first_name || "";
        const middleName = result.transaction.middle_name
          ? result.transaction.middle_name + " "
          : ""; // Add space if middle name exists
        const lastName = result.transaction.last_name || "";
        const suffix = result.transaction.suffix
          ? ", " + result.transaction.suffix
          : ""; // Add a comma if suffix exists
        const transactionCode = result.transaction.transaction_code;

        const fullName =
          `${firstName} ${middleName}${lastName}${suffix}`.trim();

        // Assign the full name to the input field
        $("#applicantName").text(fullName);
        $("#updateTransactionCode").text(transactionCode);

        // Format the created and updated date
        const formattedCreatedAt = formatDate(result.transaction.created_at);
        const formattedUpdatedAt = formatDate(result.transaction.updated_at);

        // Set the formatted date values
        $("#dateRequested").text(formattedCreatedAt);
        $("#dateLastUpdated").text(formattedUpdatedAt);

        const services = JSON.parse("[" + result.transaction.services + "]"); // Convert services string to array

        // Build the service list HTML with the action buttons
        const serviceListHtml = services
          .map(
            (service) => `
          <tr id="service_${service.id}">
            <td>${service.name}</td>
            ${getServiceActions(service)}
          </tr>
        `
          )
          .join(""); // Use map and join to build the table rows

        // Insert the service rows into the table
        $("#serviceList").html(serviceListHtml);

        // Show the modal
        $("#updateTransaction").modal("show");
      } else {
        showErrorException("Error fetching transaction details.");
      }
    },
    error: function (xhr, status, error) {
      showErrorException(error);
    },
  });
}

// Function to generate action buttons for a service
const getServiceActions = (service) => {
  if (shouldShowActions(service.status)) {
    return `
      <td>${getStatusHtml(service.status)}</td>
      <td>
        <button class="btn btn-info btn-sm btn-action" onclick="updateStatus(${
          service.id
        })">
          <i class="fas fa-sync-alt"></i> Change Status
        </button>
      </td>
      <tr id="reasonRow_${service.id}" style="display:none;">
        <td colspan="3">
          <div class="reason-container">
            <label for="reason_${service.id}" class="form-label">Reason</label>
            <textarea id="reason_${
              service.id
            }" class="form-control" rows="3" placeholder="Enter reason here..."></textarea>
            
            <div class="mt-3 d-flex justify-content-start">
              <button class="btn btn-danger btn-sm btn-rounded ml-2" onclick="processStatusChanged(${
                service.id
              }, 'Cancelled')">
                <i class="fas fa-times-circle"></i> Set to Cancelled
              </button>
              <button class="btn btn-warning btn-sm btn-rounded ml-2" onclick="processStatusChanged(${
                service.id
              }, 'ToBeFollowed')">
                <i class="fas fa-check-circle"></i> Set To Be Followed
              </button>
              <button class="btn btn-success btn-sm btn-rounded ml-2" onclick="processStatusChanged(${
                service.id
              }, 'Closed')">
                <i class="fas fa-check-circle"></i> Set to Closed
              </button>
              <button class="btn btn-secondary btn-sm btn-rounded ml-2" onclick="cancelReason(${
                service.id
              })">
                <i class="fas fa-ban"></i> Cancel Action
              </button>
            </div>
          </div>
        </td>
      </tr>
    `;
  }

  return `<td>${getStatusHtml(service.status)}</td>`;
};

// Function to show the reason textbox and hide other action buttons
function updateStatus(serviceId) {
  // Hide the action buttons for the service
  $(`#reasonRow_${serviceId}`).toggle();
  $(`.btn-action`).toggle();
  $("#serviceList tr").not('[id^="reasonRow_"]').hide();
  $(`#serviceList tr:eq(${serviceId})`).show();
  $(`#service_${serviceId}`).show();
}

// Function to save the reason and update the service status to closed
function processStatusChanged(serviceId, status) {
  const reason = $(`#reason_${serviceId}`).val();
  const roleId = $("#transactionUpdateSessionRoleId").val(); // Get the role ID from the hidden field
  if (!reason) {
    showErrorException("Please provide a reason before saving.");
    return;
  }

  $.ajax({
    url: "../controllers/TransactionController.php?action=updateServiceStatus",
    type: "POST",
    data: {
      transaction_service_id: serviceId,
      status: status,
      staff_id: currentSessionId,
      reason: reason,
      session_role_id: roleId,
    },
    success: function (response) {
      const result = JSON.parse(response);

      if (result.success) {
        // ✅ Update only the status badge
        const $statusCell = $(`#service_${serviceId} td`).eq(1);
        let badgeHtml = "";

        if (status === "Closed") {
          badgeHtml = `<span class="badge badge-success"><i class="fas fa-check-circle"></i> Closed</span>`;
        } else if (status === "Cancelled") {
          badgeHtml = `<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Cancelled</span>`;
        } else if (status === "ToBeFollowed") {
          badgeHtml = `<span class="badge badge-warning"><i class="fas fa-clock"></i> To Be Followed</span>`;
        }

        $statusCell.html(badgeHtml);

        // ✅ Hide the action button and cleanup
        cancelReason(serviceId);
        $(`#service_${serviceId} td`).eq(2).hide(); // Hide the action button

        // ✅ Toast success
        Swal.fire({
          toast: true,
          position: "top-end",
          icon: "success",
          title: `Service marked as ${status}`,
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
        });

        // ✅ Check if all services are completed
        const allDone = $('#serviceList tr[id^="service_"]')
          .toArray()
          .every((row) => {
            const statusText = $(row).find("td").eq(1).text().trim();
            return (
              statusText.includes("Closed") ||
              statusText.includes("Cancelled") ||
              statusText.includes("To Be Followed")
            );
          });

        if (allDone) {
          if (typeof isTransactionPage !== "undefined" && isTransactionPage) {
            Swal.fire({
              title: "All services completed",
              text: "This transaction has been successfully completed.",
              icon: "success",
              confirmButtonText: "OK",
              confirmButtonColor: "#28a745",
              allowOutsideClick: false,
              allowEscapeKey: false,
            }).then(() => {
              $("body").append(`
                <div id="topBar" style="
                  position: fixed;
                  top: 0; left: 0; width: 0%;
                  height: 4px;
                  background-color: #28a745;
                  z-index: 9999;
                  transition: width 0.8s ease-in-out;
                "></div>
              `);

              setTimeout(() => {
                $("#topBar").css("width", "100%");
                setTimeout(() => location.reload(), 700);
              }, 100);
            });
          } else {
            Swal.fire({
              title: "All services completed",
              text: "Proceeding to the next transaction.",
              icon: "success",
              confirmButtonText: "OK",
              confirmButtonColor: "#28a745",
              allowOutsideClick: false,
              allowEscapeKey: false,
            }).then(() => {
              $("#updateTransaction").modal("hide");

              if (!isWalkinPage) {
                moveNextScheduledToNowServing();
              } else {
                moveNextWalkinToNowServing();
              }
            });
          }
        }
      } else {
        showErrorException(result.message || "Failed to update status.");
      }
    },
    error: function (xhr, status, error) {
      showErrorException(error);
    },
  });
}

// Function to cancel the reason entry and show the action buttons again
function cancelReason(serviceId) {
  // Clear the reason textarea
  $(`#reason_${serviceId}`).val("");

  // Hide the reason textbox and remove its row
  $(`#reasonRow_${serviceId}`).hide();

  // Show the action buttons again
  $(`#serviceList tr:eq(${serviceId}) td button`).show();
  $("#serviceList tr").not('[id^="reasonRow_"]').show();
  $(`.btn-action`).show();
}

function formatDate(dateString) {
  const options = { year: "numeric", month: "long", day: "numeric" };
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", options);
}

function getStatusHtml(status) {
  // Return different HTML based on the status
  switch (status) {
    case "Open":
      return '<span class="badge badge-secondary"><i class="fas fa-spinner"></i> Open</span>';
    case "Pending":
      return '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>';
    case "Cancelled":
      return '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Cancelled</span>';
    case "Closed":
      return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Closed</span>';
    default:
      return '<span class="badge badge-secondary"><i class="fas fa-question-circle"></i> Unknown</span>';
  }
}

function handleNoShow(transactionCode) {
  Swal.fire({
    title: "Mark as No Show?",
    text: "This action cannot be undone once set.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, mark as No Show",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../controllers/QueueController.php?action=markNoShow",
        method: "POST",
        data: {
          transaction_code: transactionCode,
          staff_id: currentSessionId,
        },
        success: function (response) {
          const res = JSON.parse(response);
          if (res.success) {
            Swal.fire(
              "Marked as No Show",
              "The transaction was cancelled.",
              "success"
            ).then(() => {
              if (!isWalkinPage) {
                moveNextScheduledToNowServing();
              } else {
                moveNextWalkinToNowServing();
              }
            });
          } else {
            Swal.fire(
              "Error",
              res.message || "Failed to update status.",
              "error"
            );
          }
        },
        error: function () {
          Swal.fire("Error", "Could not connect to the server.", "error");
        },
      });
    }
  });
}

function moveNextToNowServing(config) {
  const {
    queueSelector,
    currentNumberSelector,
    startButtonSelector,
    noShowButtonSelector,
    label,
  } = config;

  const $nextItem = $(`${queueSelector} .queue-item`).first();

  if (!$nextItem.length) {
    $(currentNumberSelector).text("—");
    $(startButtonSelector).prop("disabled", true);
    $(noShowButtonSelector).prop("disabled", true);
    Swal.fire("Done", `No more ${label} transactions.`, "info");
    return;
  }

  const transactionCode = $nextItem.find(".transaction-code").text().trim();
  const transactionId = $nextItem.data("transaction-id");

  $nextItem.fadeOut(300, function () {
    $(this).remove();

    // Update UI
    $(currentNumberSelector).text(transactionCode);

    $(startButtonSelector)
      .prop("disabled", false)
      .attr("onclick", `openUpdateTransactionModal(${transactionId})`);

    $(noShowButtonSelector)
      .prop("disabled", false)
      .data("transaction-code", transactionCode);
  });
}

function moveNextScheduledToNowServing() {
  moveNextToNowServing({
    queueSelector: "#scheduledQueueList",
    currentNumberSelector: "#scheduledCurrentNumber",
    startButtonSelector: "#scheduledStartTransaction",
    noShowButtonSelector: "#scheduledNoShow",
    label: "scheduled",
  });
}

function moveNextWalkinToNowServing() {
  moveNextToNowServing({
    queueSelector: "#walkinQueueList",
    currentNumberSelector: "#walkinCurrentNumber",
    startButtonSelector: "#walkinStartTransaction",
    noShowButtonSelector: "#walkinNoShow",
    label: "walk-in",
  });
}

// Function to update the clock
function updateClock() {
  const clockElement = document.getElementById("clock");
  const now = new Date();
  if (!clockElement) return;

  let hours = now.getHours();
  let minutes = now.getMinutes();
  let seconds = now.getSeconds();
  let day = now.getDate();
  let month = now.getMonth() + 1; // Months are zero-based in JavaScript, so add 1
  let year = now.getFullYear();

  // Determine AM or PM
  let amPm = hours >= 12 ? "PM" : "AM";

  // Convert to 12-hour format
  hours = hours % 12;
  hours = hours ? hours : 12; // Handle midnight case

  // Add leading zero to minutes and seconds if necessary
  function pad(number) {
    return number < 10 ? "0" + number : number;
  }

  clockElement.textContent =
    pad(hours) +
    ":" +
    pad(minutes) +
    ":" +
    pad(seconds) +
    " " +
    amPm +
    " | " +
    pad(month) +
    "/" +
    pad(day) +
    "/" +
    year;
}

// Padding function for single digit numbers
function pad(num) {
  return num < 10 ? "0" + num : num;
}

// Update the clock every second
setInterval(updateClock, 1000);

function initTablesAndSelects() {
  const dataTableOptions = {
    pageLength: 10,
    lengthChange: false,
    searching: true,
    ordering: true,
    info: true,
    paging: true,
  };

  // Initialize DataTables if the element exists
  [
    "#activityLogTable",
    "#userTable",
    "#transactionTable",
    "#requirementTable",
    "#serviceTable",
  ].forEach(function (id) {
    if ($(id).length) {
      $(id).DataTable(dataTableOptions);
    }
  });

  $("#addServices").select2({
    placeholder: "Select services",
    allowClear: true,
    width: "100%",
    maximumSelectionLength: "3",
  });
}

$(document).ready(function () {
  initTablesAndSelects();
  // Start Of Users
  $("#addUserForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    // Validate if the passwords match
    let password = $("#password").val();
    let confirmPassword = $("#confirmPassword").val();
    if (password !== confirmPassword) {
      showErrorException("Passwords do not match!");
      return;
    }

    var formData = new FormData(this);

    $.ajax({
      url: "../controllers/UserController.php?action=add", // PHP script to handle the request
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (
          handleAjaxResponse(
            response,
            "User added successfully",
            "Error adding user. Please try again."
          )
        ) {
          $("#addUserModal").modal("hide"); // Close the modal
          $("#addUserForm")[0].reset(); // Reset the form

          // Reload the page after successful addition
          Swal.fire({
            title: "Success!",
            text: "User added successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#28a745", // Green color for success
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
    });
  });

  // Handle the Edit Form Submission with AJAX
  $("#editUserForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    var formData = new FormData(this);

    $.ajax({
      url: "../controllers/UserController.php?action=edit",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (
          handleAjaxResponse(
            response,
            "User updated successfully.",
            "Error updating user."
          )
        ) {
          $("#editUserModal").modal("hide");

          // Reload the page after successful update
          Swal.fire({
            title: "Success!",
            text: "User updated successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#28a745", // Green color for success
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
    });
  });

  // Handle the Delete Action
  $("#confirmDeleteUserBtn").on("click", function () {
    var userId = $("#deleteUserId").val(); // Get the user ID from the hidden field
    const sessionUserId = $("#delUserSessionUserId").val(); // Get the user ID from the hidden field
    const sessionUsername = $("#delUserSessionUsername").val(); // Get the user ID from the hidden field
    const sessionRoleId = $("#delUserSessionRoleId").val(); // Get the user ID from the hidden field

    if (userId) {
      $.ajax({
        url: "../controllers/UserController.php?action=delete",
        type: "POST",
        data: {
          user_id: userId,
          session_user_id: sessionUserId,
          session_username: sessionUsername,
          session_role_id: sessionRoleId,
        },
        success: function (response) {
          if (
            handleAjaxResponse(
              response,
              "User deleted successfully.",
              "Error deleting user."
            )
          ) {
            $("#deleteUserModal").modal("hide");
            // Animate the row's slide-up effect before removal
            $("#userData_" + userId).slideUp(500, function () {
              $(this).remove(); // Remove the element after the slide-up animation
            });
          }
        },
        error: function (xhr, status, error) {
          showErrorException(error);
        },
      });

      // Close the modal after the action is taken
      $("#deleteUserModal").modal("hide");
    }
  });
  // End Of User

  // Start Of Services
  $("#addServiceForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    var serviceName = $("#serviceName").val();
    var description = $("#description").val();

    // You can add validation for your fields, for example:
    if (serviceName === "" || description === "") {
      showErrorException("Please fill out all fields!");
      return;
    }

    // Gather the form data
    var formData = new FormData(this);

    $.ajax({
      url: "../controllers/ServiceController.php?action=add",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (
          handleAjaxResponse(
            response,
            "Service added successfully",
            "Error adding service. Please try again."
          )
        ) {
          $("#addServiceModal").modal("hide"); // Close the modal
          $("#addServiceForm")[0].reset(); // Reset the form

          // Reload the page after successful addition
          Swal.fire({
            title: "Success!",
            text: "Service added successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#28a745", // Green color for success
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
    });
  });

  $("#editServiceForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    var formData = new FormData(this);

    $.ajax({
      url: "../controllers/ServiceController.php?action=edit",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (
          handleAjaxResponse(
            response,
            "User updated successfully.",
            "Error updating user."
          )
        ) {
          $("#editServiceModal").modal("hide");

          // Reload the page after successful update
          Swal.fire({
            title: "Success!",
            text: "Service updated successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#28a745", // Green color for success
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
    });
  });

  $("#confirmDeleteServiceBtn").on("click", function () {
    var serviceId = $("#deleteServiceId").val(); // Get the user ID from the hidden field
    const sessionUserId = $("#delServiceSessionUserId").val(); // Get the user ID from the hidden field
    const sessionUsername = $("#delServiceSessionUsername").val(); // Get the user ID from the hidden field
    const sessionRoleId = $("#delServiceSessionRoleId").val(); // Get the user ID from the hidden field

    if (serviceId) {
      $.ajax({
        url: "../controllers/ServiceController.php?action=delete",
        type: "POST",
        data: {
          service_id: serviceId,
          session_user_id: sessionUserId,
          session_username: sessionUsername,
          session_role_id: sessionRoleId,
        },
        success: function (response) {
          if (
            handleAjaxResponse(
              response,
              "Service deleted successfully.",
              "Error deleting service."
            )
          ) {
            $("#deleteServiceModal").modal("hide");
            // Animate the row's slide-up effect before removal
            $("#serviceData_" + serviceId).slideUp(500, function () {
              $(this).remove(); // Remove the element after the slide-up animation
            });
          }
        },
        error: function (xhr, status, error) {
          showErrorException(error);
        },
      });

      // Close the modal after the action is taken
      $("#deleteServiceModal").modal("hide");
    }
  });
  // End Of Services

  //Start of Service Requirements
  $("#addRequirementForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    var requirementServiceId = $("#addRequirementServiceId").val();
    var requirementDescription = $("#addRequirementDescription").val();

    // You can add validation for your fields, for example:
    if (requirementServiceId === "" || requirementDescription === "") {
      showErrorException("Please fill out all fields!");
      return;
    }

    // Create FormData from the form
    var formData = new FormData(this);

    $.ajax({
      url: "../controllers/RequirementController.php?action=add",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (
          handleAjaxResponse(
            response,
            "Requirement added successfully",
            "Error adding requirement. Please try again."
          )
        ) {
          $("#addRequirementModal").modal("hide"); // Close the modal
          $("#addRequirementForm")[0].reset(); // Reset the form

          // Show success message and reload the page after successful addition
          Swal.fire({
            title: "Success!",
            text: "Requirement added successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#28a745", // Green color for success
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload(); // Reload the page
            }
          });
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error); // Show error if AJAX request fails
      },
    });
  });

  $("#editRequirementForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    var formData = new FormData(this);

    $.ajax({
      url: "../controllers/RequirementController.php?action=edit",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (
          handleAjaxResponse(
            response,
            "Requirement updated successfully.",
            "Error updating requirement."
          )
        ) {
          $("#editRequirementModal").modal("hide");
          // Reload the page after successful update
          Swal.fire({
            title: "Success!",
            text: "Requirement updated successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#28a745", // Green color for success
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
    });
  });

  $("#confirmDeleteRequirementBtn").on("click", function () {
    var requirementId = $("#deleteRequirementId").val(); // Get the user ID from the hidden field
    const sessionUserId = $("#delRequirementSessionUserId").val(); // Get the user ID from the hidden field
    const sessionUsername = $("#delRequirementSessionUsername").val(); // Get the user ID from the hidden field
    const sessionRoleId = $("#delRequirementSessionRoleId").val(); // Get the user ID from the hidden field

    if (requirementId) {
      $.ajax({
        url: "../controllers/RequirementController.php?action=delete",
        type: "POST",
        data: {
          requirement_id: requirementId,
          session_user_id: sessionUserId,
          session_username: sessionUsername,
          session_role_id: sessionRoleId,
        },
        success: function (response) {
          if (
            handleAjaxResponse(
              response,
              "Requirement deleted successfully.",
              "Error deleting requirement."
            )
          ) {
            // Animate the row's slide-up effect before removal
            $("#requirementData_" + requirementId).slideUp(500, function () {
              $(this).remove(); // Remove the element after the slide-up animation
            });
          }
          $("#deleteRequirementModal").modal("hide");
        },
        error: function (xhr, status, error) {
          showErrorException(error);
        },
      });

      // Close the modal after the action is taken
      $("#deleteRequirementModal").modal("hide");
    }
  });
  // End of Service Requirements

  // Start of Transactions
  $("#addTransactionForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    // Validate the selected services
    if (!validateServices()) {
      return;
    }

    var formData = new FormData(this);

    $.ajax({
      url: "../controllers/TransactionController.php?action=add",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (
          handleAjaxResponse(
            response,
            "Transaction added successfully.",
            "Error adding transaction."
          )
        ) {
          // Reload the page after successful addition
          Swal.fire({
            title: "Success!",
            text: "Transaction added successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#28a745", // Green color for success
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
        }

        $("#addTransactionModal").modal("hide"); // Close the modal
        $("#addTransactionForm")[0].reset(); // Reset the form
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
    });
  });

  $("#verifyChangeBtn").on("click", function () {
    var userId = $("#addUserId").val();
    var button = $("#verifyChangeBtn");

    // Disable the Add Transaction button initially
    var addTransactionButton = $("#addTransactionButton"); // Assuming the Add Transaction button has this ID

    if (userId) {
      // If the button says "Verify", send AJAX request to check user existence
      if (button.text() === "Verify") {
        // AJAX call to check if the user exists
        $.ajax({
          url: "../controllers/UserController.php?action=getUserDetails",
          method: "POST",
          data: { userId: userId },
          success: function (response) {
            var data = JSON.parse(response); // Assuming the response is JSON

            if (data.success) {
              $("#transactionUserId").val(data.user.id);
              // User exists, update UI
              button.text("Change"); // Change button text to "Change"
              $("#addUserId").prop("disabled", true); // Disable the user ID field
              $("#userIdError").hide(); // Hide error message

              // Enable the Add Transaction button
              addTransactionButton.prop("disabled", false); // Enable Add Transaction button

              // Check if profile picture is null and set a default based on gender_id
              var profilePicture = data.user.profile_picture;
              if (!profilePicture) {
                // If profile_picture is null, set the default based on gender_id
                profilePicture =
                  data.user.gender_id == 2
                    ? "../images/default_male.png"
                    : "../images/default_female.png";
              }

              // Populate the modal with user information
              $("#userInfo").show(); // Show the user information section
              // Update the modal with the returned user data or the default image
              $("#userInfo img").attr("src", profilePicture); // Update profile picture
              $("#userInfo .full-name").text(data.user.full_name); // Update full name
              $("#userInfo .birthdate").text(data.user.birthdate); // Update birthdate
            } else {
              // User doesn't exist, show error
              $("#userIdError").show();
              $("#userInfo").hide(); // Hide user info section if user doesn't exist

              // Disable the Add Transaction button if user does not exist
              addTransactionButton.prop("disabled", true); // Disable Add Transaction button
            }
          },
          error: function () {
            // Handle AJAX errors
            console.log("An error occurred while checking the user.");
          },
        });
      } else {
        // If the button says "Change", enable the input and clear it
        $("#addUserId").prop("disabled", false); // Enable the input field
        $("#addUserId").val(""); // Clear the input field
        $("#userIdError").hide(); // Hide any error message
        $("#userInfo").hide(); // Hide the user information section
        button.text("Verify"); // Change button text back to "Verify"

        // Disable the Add Transaction button if user changes ID
        addTransactionButton.prop("disabled", true); // Disable Add Transaction button
      }
    } else {
      $("#userIdError").show(); // Show error if user ID is empty

      // Disable the Add Transaction button if user ID is empty
      addTransactionButton.prop("disabled", true); // Disable Add Transaction button
    }
  });

  $("#searchTransactionForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    var transactionCode = $("#transactionCode").val(); // Get the transaction code from the input field

    // You can add validation for the field
    if (transactionCode === "") {
      showErrorException("Please enter a transaction code.");
      return;
    }

    // Perform the AJAX request to check if the transaction exists
    $.ajax({
      url: "../controllers/TransactionController.php?action=getTransactionByCode",
      type: "POST",
      data: {
        transaction_code: transactionCode, // Send the transaction code
      },
      success: function (response) {
        var data = JSON.parse(response); // Assuming the response is JSON

        if (data.success) {
          // Create a new row with the transaction data
          var transactionRow = `
            <tr id="transactionData_${
              data.transaction.transaction_id
            }" style="display: none;">
              <td>${data.transaction.transaction_code}</td>
              <td>${data.transaction.first_name} ${
            data.transaction.middle_name ? data.transaction.middle_name : ""
          } ${data.transaction.suffix ? data.transaction.suffix : ""}</td>
              <td>${data.transaction.services}</td>
              <td>${data.transaction.created_at}</td>
              <td>${data.transaction.updated_at}</td>
              <td>${data.transaction.date_closed || ""}</td>
              <td>${data.transaction.status}</td>
              <td>
                <button 
                  ${data.transaction.date_closed ? "hidden" : ""} 
                  class="btn btn-info btn-sm" 
                  onclick="openUpdateTransactionModal(${
                    data.transaction.transaction_id
                  })">
                  Update Status
                </button>
              </td>
            </tr>
          `;

          $("#searchTransactionModal").modal("hide"); // Close the modal

          // Add the new row to the table
          $("#transactionTable tbody").empty(); // Clear the existing rows
          $("#transactionTable tbody").append(transactionRow);

          // Animate the new row with a fade-in effect
          $("#transactionData_" + data.transaction.transaction_id).fadeIn(500);
        } else {
          // Show error if transaction not found
          showErrorException(
            "Transaction not found. Please check the code and try again."
          );
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
      complete: function () {
        $("#transactionCode").val("");
      },
    });
  });

  $(".no-show-btn").on("click", function () {
    const transactionCode = $(this).data("transaction-code");
    const type = $(this).data("type");
    const isValid = /^Q-[A-Z0-9]{6,10}$/.test(transactionCode);

    if (!isValid) {
      Swal.fire({
        title: "No Active Transaction",
        text:
          type === "scheduled"
            ? "No scheduled transaction available."
            : "No walk-in transaction available.",
        icon: "error",
        confirmButtonText: "OK",
      });
      return;
    }
    handleNoShow(transactionCode);
  });

  $("#settingsForm").on("submit", function (e) {
    e.preventDefault();
    NProgress.start();

    const formData = $(this).serialize();

    $.post("../controllers/SystemSettingController.php?action=save", formData)
      .done((response) => {
        const data = JSON.parse(response);
        Swal.fire({
          icon: data.success ? "success" : "error",
          title: data.success ? "Settings Updated" : "Update Failed",
          text:
            data.message ||
            (data.success
              ? "Your changes were saved successfully."
              : "An error occurred."),
          confirmButtonColor: "#007bff",
        });
      })
      .fail(() => {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to connect to the server.",
          confirmButtonColor: "#dc3545",
        });
      })
      .always(() => NProgress.done());
  });
});
