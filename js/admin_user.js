var isAdmin = "<?php echo $isAdmin; ?>";

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

// Handeles ajax Response
function handleAjaxResponse(response, successMessage, errorMessage) {
  var result = JSON.parse(response);

  if (result.success) {
    // SweetAlert2 Success Popup
    Swal.fire({
      title: 'Success!',
      text: successMessage,
      icon: 'success',
      confirmButtonText: 'OK',
      confirmButtonColor: '#28a745',  // Green color for success
    });
    return true;
  } else {
    // SweetAlert2 Error Popup
    Swal.fire({
      title: 'Error!',
      text: errorMessage,
      icon: 'error',
      confirmButtonText: 'OK',
      confirmButtonColor: '#dc3545',  // Red color for error
    });
    return false;
  }
}

function showErrorException(errorMessage) {
  Swal.fire({
    title: 'Error!',
    text: errorMessage,
    icon: 'error',
    confirmButtonText: 'OK',
    confirmButtonColor: '#dc3545',  // Red color for error
  });
}

// User Modals
// Open the User Modal and Populate the Fields
function openAddUserModal() {
  const preview = document.getElementById("preview");
  preview.src = ""; // Clear the image source
  preview.style.display = "none"; // Hide the preview if there was an image before

  $.ajax({
    url: "../controllers/UsersController.php?action=getUserPrequisite", // Endpoint to fetch user data
    type: "GET",
    success: function (response) {
      var result = JSON.parse(response); // Parse the JSON response
      if (result.success) {
        // Populate the Role dropdown dynamically
        let roleOptions =
          '<option value="" disabled selected>Select role</option>';
        result.roles.forEach(function (role) {
          roleOptions += `<option value="${role.id}">${role.role_name}</option>`;
        });

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
        alert("Error fetching user prequesite.");
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
    url: "../controllers/UsersController.php?action=getUserById", // Endpoint to fetch user data
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
        let roleOptions =
          '<option value="" disabled selected>Select role</option>';
        result.roles.forEach(function (role) {
          roleOptions += `<option d value="${role.id}" 
          ${role.id == result.user.role_id ? "selected" : ""}
          ${isAdmin == false ? "disabled" : ""}
          >${role.role_name}</option>`;
        });
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

        var baseUrl = window.location.origin + "/QPila";
        var imageUrl;

        if (result.user.profile_picture) {
          var imagePath = result.user.profile_picture.replace(/^(\.\.\/)/, "");

          imageUrl = baseUrl + "/" + imagePath;
        } else {
          // Set default image based on gender if no profile picture exists
          imageUrl =
            result.user.gender_id == 2
              ? "../images/default_male.png"
              : "../images/default_female.png";
        }

        // Set the image preview source and ensure it is visible
        $("#editPreview").attr("src", imageUrl).show();

        // Open the modal
        $("#editUserModal").modal("show");
      } else {
        alert("Error fetching user details.");
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
  $('#deleteUserId').val(userId);

  // Open the modal
  $('#deleteUserModal').modal('show');
}
//End of User Modals


// Start of Service
// Function to open the Add Service Modal
function openAddServiceModal() {
  $('#addServiceModal').modal('show');
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
        alert("Error fetching service details.");
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
  $('#deleteServiceId').val(serviceId);

  // Open the modal
  $('#deleteServiceModal').modal('show');
}
// End of services

// Start of Service requirements
// Function to open the Add Service Modal
function openAddServiceModal() {
  // First, fetch the available services from the backend
  $.ajax({
    url: "../controllers/ServiceController.php?action=getServices", // Endpoint to fetch all services
    type: "GET",
    success: function(response) {
      var result = JSON.parse(response); // Parse the JSON response
      if (result.length > 0) {
        // Populate the Service dropdown (select option)
        var serviceDropdown = $("#addServiceName");
        serviceDropdown.empty(); // Clear previous options
        serviceDropdown.append('<option value="" disabled selected>Select Service</option>');
        
        result.forEach(function(service) {
          serviceDropdown.append('<option value="' + service.id + '">' + service.service_name + '</option>');
        });

        // Once services are loaded, open the modal
        $('#addServiceModal').modal('show');
      } else {
        alert("No services found.");
      }
    },
    error: function(xhr, status, error) {
      showErrorException(error);
    }
  });
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

$(document).ready(function () {

  // Users
  // Handle form submission with AJAX
  $("#addUserForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    // Validate if the passwords match
    let password = $("#password").val();
    let confirmPassword = $("#confirmPassword").val();
    if (password !== confirmPassword) {
      alert("Passwords do not match!");
      return;
    }

    var formData = new FormData(this);

    $.ajax({
      url: "../controllers/UsersController.php?action=add", // PHP script to handle the request
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
          location.reload();
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
      url: "../controllers/UsersController.php?action=edit",
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
          location.reload();
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
    });
  });

  // Handle the Delete Action
  $('#confirmDeleteUserBtn').on('click', function() {
    var userId = $('#deleteUserId').val(); // Get the user ID from the hidden field

    if (userId) {
      $.ajax({
        url: "../controllers/UsersController.php?action=delete",
        type: "POST",
        data: {
          user_id: userId,
        },
        success: function (response) {
          if (
            handleAjaxResponse(
              response,
              "User deleted successfully.",
              "Error deleting user."
            )
          ) {

            $('#deleteUserModal').modal('hide');
            // Animate the row's slide-up effect before removal
            $('#userData_' + userId).slideUp(500, function() {
              $(this).remove(); // Remove the element after the slide-up animation
            });
          }
        },
        error: function (xhr, status, error) {
          showErrorException(error);
        },
      });
      
      // Close the modal after the action is taken
      $('#deleteUserModal').modal('hide');
    }
  });
 // End of user

// Services
  $("#addServiceForm").submit(function (e) {
    e.preventDefault(); // Prevent default form submission
  
    var serviceName = $("#serviceName").val();
    var description = $("#description").val();
  
    // You can add validation for your fields, for example:
    if (serviceName === "" || description === "") {
      alert("Please fill out all fields!");
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
          location.reload();
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
          location.reload();
        }
      },
      error: function (xhr, status, error) {
        showErrorException(error);
      },
    });
  });

  $('#confirmDeleteServiceBtn').on('click', function() {
    var serviceId = $('#deleteServiceId').val(); // Get the user ID from the hidden field

    if (serviceId) {
      $.ajax({
        url: "../controllers/ServiceController.php?action=delete",
        type: "POST",
        data: {
          service_id: serviceId,
        },
        success: function (response) {
          if (
            handleAjaxResponse(
              response,
              "Service deleted successfully.",
              "Error deleting service."
            )
          ) {

            $('#deleteServiceModal').modal('hide');
            // Animate the row's slide-up effect before removal
            $('#serviceData_' + serviceId).slideUp(500, function() {
              $(this).remove(); // Remove the element after the slide-up animation
            });
          }
        },
        error: function (xhr, status, error) {
          showErrorException(error);
        },
      });
      
      // Close the modal after the action is taken
      $('#deleteServiceModal').modal('hide');
    }
  });
// end of services


});
