<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="logoutModalLabel"><i class="fas fa-sign-out-alt mr-2"></i> <strong>Logout Confirmation</strong></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
        </div>
        <p class="text-center">Are you sure you want to log out of the Barangay Services system? Please ensure that any ongoing service updates are saved to avoid data loss.</p>
        <form id="logoutForm" method="POST" class="mt-3">
          <div class="text-center">
            <a href="logout.php" class="btn btn-primary btn-lg px-5"><i class="fas fa-sign-out-alt"></i> Confirm Logout</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Start of Users Modal -->
<!-- Modal for Adding User -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document"> <!-- Use modal-xl for extra width -->
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addUserModalLabel"><i class="fas fa-user-plus"></i> Add New User</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add User Form -->
        <form id="addUserForm" action="UsersController.php?action=add" method="POST" enctype="multipart/form-data">
          <div class="form-row">
            <!-- First Name, Middle Name, Last Name -->
            <div class="form-group col-md-4">
              <label for="firstName"><i class="fas fa-user"></i> First Name</label>
              <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required autocomplete="off" />
            </div>
            <div class="form-group col-md-4">
              <label for="middleName"><i class="fas fa-user-edit"></i> Middle Name</label>
              <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter middle name" />
            </div>
            <div class="form-group col-md-4">
              <label for="lastName"><i class="fas fa-user-tie"></i> Last Name</label>
              <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required autocomplete="off" />
            </div>
          </div>

          <div class="form-row">
            <!-- Username, Password, Confirm Password -->
            <div class="form-group col-md-4">
              <label for="username"><i class="fas fa-user-circle"></i> Username</label>
              <input type="text" class="form-control" id="username" name="userName" placeholder="Enter username" required autocomplete="off" />
            </div>

            <div class="form-group col-md-4">
              <label for="password"><i class="fas fa-lock"></i> Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required autocomplete="off" />
            </div>

            <div class="form-group col-md-4">
              <label for="confirmPassword"><i class="fas fa-lock"></i> Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required autocomplete="off" />
            </div>
          </div>

          <!--Email, Mobile Number, Suffix -->
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="email"><i class="fas fa-envelope"></i> Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required autocomplete="off" />
            </div>

            <div class="form-group col-md-4">
              <label for="mobile_number"><i class="fas fa-mobile-alt"></i> Mobile Number</label>
              <input type="text" class="form-control" id="mobile_number" name="mobileNumber" placeholder="Enter mobile number" />
            </div>

            <div class="form-group col-md-4">
              <label for="suffix"><i class="fas fa-asterisk"></i> Suffix</label>
              <input type="text" class="form-control" id="suffix" name="suffix" placeholder="Enter suffix (e.g., Jr., Sr.)" />
            </div>
          </div>

          <!--Birthdate, Gender -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="birthdate"><i class="fas fa-calendar-alt"></i> Birthdate</label>
              <input type="date" class="form-control" id="birthdate" name="birthdate" required autocomplete="off" />
            </div>

            <div class="form-group col-md-6">
              <label for="gender"><i class="fas fa-venus-mars"></i> Gender</label>
              <select class="form-control" id="gender" name="gender" required autocomplete="off">
              </select>
            </div>
          </div>

          <!-- Role, Marital Status, Is Verified -->
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="role"><i class="fas fa-briefcase"></i> Role</label>
              <select class="form-control" id="role" name="role" required autocomplete="off">
              </select>
            </div>

            <div class="form-group col-md-4">
              <label for="maritalStatus"><i class="fas fa-heart"></i> Marital Status</label>
              <select class="form-control" id="maritalStatus" name="maritalStatus" required autocomplete="off">
              </select>
            </div>

            <div class="form-group col-md-4">
              <label for="isVerified"><i class="fas fa-check-circle"></i> Is Verified</label>
              <select class="form-control" id="isVerified" name="isVerified" required autocomplete="off">
                <option value="0">Not Verified</option>
                <option value="1">Verified</option>
              </select>
            </div>
          </div>

          <!-- Profile Image Upload, Birthdate -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="profileImage"><i class="fas fa-camera"></i> Profile Image</label>
              <input type="file" class="form-control" id="profileImage" name="profileImage" accept="image/*" onchange="previewImage(event)" />
            </div>
            <div class="form-group col-md-6">
              <div><i class="fas fa-image"></i> Image Preview</div>
              <div id="imagePreview" style="text-align: center;">
                <img id="preview" src="" alt="Image Preview"
                  style="
                    margin:auto;
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    max-width: 100px;
                    max-height: 100px;
                    display: none;
                    " />
              </div>
            </div>
          </div>

          <!-- Address -->
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
              <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter address" required autocomplete="off"></textarea>
            </div>

          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary mt-3 btn-block">
            <i class="fas fa-user-plus"></i> Add User
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Editing User -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editUserModalLabel"><i class="fas fa-user-edit"></i> Edit User</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Edit User Form -->
        <form id="editUserForm" action="UsersController.php?action=edit" method="POST" enctype="multipart/form-data">
          <input type="hidden" id="editUserId" name="userId">

          <!-- First Name, Middle Name, Last Name -->
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="editFirstName"><i class="fas fa-user"></i> First Name</label>
              <input type="text" class="form-control" id="editFirstName" name="firstName" placeholder="Enter first name" required autocomplete="off" />
            </div>

            <div class="form-group col-md-4">
              <label for="editMiddleName"><i class="fas fa-user-edit"></i> Middle Name</label>
              <input type="text" class="form-control" id="editMiddleName" name="middleName" placeholder="Enter middle name" />
            </div>

            <div class="form-group col-md-4">
              <label for="editLastName"><i class="fas fa-user-tie"></i> Last Name</label>
              <input type="text" class="form-control" id="editLastName" name="lastName" placeholder="Enter last name" required autocomplete="off" />
            </div>
          </div>

          <!-- Username, Password, Confirm Password -->
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="editUsername"><i class="fas fa-user-circle"></i> Username</label>
              <input type="text" class="form-control" id="editUsername" name="userName" placeholder="Enter username" required autocomplete="off" />
            </div>

            <div class="form-group col-md-4">
              <label for="password"><i class="fas fa-lock"></i> Password</label>
              <input type="password" class="form-control password" name="password" placeholder="Enter password" autocomplete="off" />
            </div>

            <div class="form-group col-md-4">
              <label for="confirmPassword"><i class="fas fa-lock"></i> Confirm Password</label>
              <input type="password" class="form-control confirmPassword" name="confirmPassword" placeholder="Confirm password" autocomplete="off" />
            </div>
          </div>

          <!-- Email, Mobile Number, Suffix -->
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="editEmail"><i class="fas fa-envelope"></i> Email</label>
              <input type="email" class="form-control" id="editEmail" name="email" placeholder="Enter email" required autocomplete="off" />
            </div>

            <div class="form-group col-md-4">
              <label for="editMobileNumber"><i class="fas fa-mobile-alt"></i> Mobile Number</label>
              <input type="text" class="form-control" id="editMobileNumber" name="mobileNumber" placeholder="Enter mobile number" />
            </div>

            <div class="form-group col-md-4">
              <label for="editSuffix"><i class="fas fa-asterisk"></i> Suffix</label>
              <input type="text" class="form-control" id="editSuffix" name="suffix" placeholder="Enter suffix (e.g., Jr., Sr.)" />
            </div>
          </div>

          <!-- Role, Marital Status, Is Verified -->
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="editRole"><i class="fas fa-briefcase"></i> Role</label>
              <select class="form-control" id="editRole" name="role" required autocomplete="off">
              </select>
            </div>

            <div class="form-group col-md-4">
              <label for="editMaritalStatus"><i class="fas fa-heart"></i> Marital Status</label>
              <select class="form-control" id="editMaritalStatus" name="maritalStatus" required autocomplete="off">
              </select>
            </div>

            <div class="form-group col-md-4">
              <label for="editIsVerified"><i class="fas fa-check-circle"></i> Is Verified</label>
              <select class="form-control" id="editIsVerified" name="isVerified" required autocomplete="off">
                <option value="1" selected="selected">Verified</option>
                <option value="0">Not Verified</option>
              </select>
            </div>
          </div>

          <!-- Birthdate, Gender -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editBirthdate"><i class="fas fa-calendar-alt"></i> Birthdate</label>
              <input type="date" class="form-control" id="editBirthdate" name="birthdate" required autocomplete="off" />
            </div>

            <div class="form-group col-md-6">
              <label for="editGender"><i class="fas fa-venus-mars"></i> Gender</label>
              <select class="form-control" id="editGender" name="gender" required autocomplete="off">
              </select>
            </div>
          </div>

          <!-- Profile Image Upload, Preview Image -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editProfileImage"><i class="fas fa-camera"></i> Profile Image</label>
              <input type="file" class="form-control" id="editProfileImage" name="profileImage" accept="image/*" onchange="editPreviewImage(event)" />
            </div>
            <div class="form-group col-md-6">
              <div><i class="fas fa-image"></i> Image Preview</div>
              <div id="editImagePreview" style="text-align: center;">
                <img id="editPreview" src="" alt="Image Preview"
                  style="margin:auto;
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    max-width: 100px;
                    max-height: 100px;
                    display: none;" />
              </div>
            </div>
          </div>

          <!-- Address -->
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="editAddress"><i class="fas fa-map-marker-alt"></i> Address</label>
              <textarea class="form-control" id="editAddress" name="address" rows="3" placeholder="Enter address" required autocomplete="off"></textarea>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary mt-3 btn-block">
            <i class="fas fa-user-edit"></i> Save Changes
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteServiceModalLabel"><i class="fas fa-trash-alt mr-2"></i> <strong>Delete Service Confirmation</strong></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        </div>
        <p class="text-center">Are you sure you want to permanently delete this service? This action cannot be undone, and all data related to this service will be lost. Please confirm your decision.</p>
        <input type="hidden" id="deleteServiceId">
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <!-- Left-aligned button (Cancel) -->
        <button type="button" class="btn btn-secondary btn-lg px-5" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
        <!-- Right-aligned button (Confirm) -->
        <button type="button" id="confirmDeleteServiceBtn" class="btn btn-danger btn-lg px-5"><i class="fas fa-trash-alt"></i> Confirm Deletion</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Users Modal -->

<!-- Start of Services Modals -->
<!-- Modal for Adding Service -->
<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"> <!-- Modal with larger size -->
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addServiceModalLabel"><i class="fas fa-suitcase"></i> Add New Service</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add Service Form -->
        <form id="addServiceForm" action="ServicesController.php?action=add" method="POST">
          <div class="form-row">
            <!-- Service Name -->
            <div class="form-group col-md-12">
              <label for="serviceName"><i class="fas fa-suitcase"></i> Service Name</label>
              <input type="text" class="form-control" id="serviceName" name="serviceName" placeholder="Enter service name" required autocomplete="off" />
            </div>
          </div>

          <div class="form-row">
            <!-- Description -->
            <div class="form-group col-md-12">
              <label for="description"><i class="fas fa-file-alt"></i> Description</label>
              <textarea class="form-control" id="description" name="serviceDescription" rows="4" placeholder="Enter service description" required autocomplete="off"></textarea>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary mt-3 btn-block">
            <i class="fas fa-suitcase"></i> Add Service
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Editing Service -->
<div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"> <!-- Modal with larger size -->
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="editServiceModalLabel"><i class="fas fa-suitcase"></i> Edit Service</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Edit Service Form -->
        <form id="editServiceForm" action="ServicesController.php?action=edit" method="POST">
          <input type="hidden" id="editServiceId" name="serviceId" /> <!-- Hidden field for service ID -->

          <div class="form-row">
            <!-- Service Name -->
            <div class="form-group col-md-12">
              <label for="editServiceName"><i class="fas fa-suitcase"></i> Service Name</label>
              <input type="text" class="form-control" id="editServiceName" name="serviceName" placeholder="Enter service name" required autocomplete="off" />
            </div>
          </div>

          <div class="form-row">
            <!-- Description -->
            <div class="form-group col-md-12">
              <label for="editDescription"><i class="fas fa-file-alt"></i> Description</label>
              <textarea class="form-control" id="editDescription" name="serviceDescription" rows="4" placeholder="Enter service description" required autocomplete="off"></textarea>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-warning mt-3 btn-block">
            <i class="fas fa-suitcase"></i> Update Service
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Deleting Service -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document"> <!-- Modal with small size -->
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteServiceModalLabel"><i class="fas fa-trash-alt"></i> Delete Service</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this service?</p>
          <input type="hidden" id="deleteServiceId" name="serviceId" /> <!-- Hidden field for service ID -->
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <!-- Left-aligned button (Cancel) -->
        <button type="button" class="btn btn-secondary btn-lg px-5" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
        <!-- Right-aligned button (Confirm) -->
        <button type="button" id="confirmDeleteUserBtn" class="btn btn-danger btn-lg px-5"><i class="fas fa-trash-alt"></i> Confirm Deletion</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Services Modals -->

<!-- Start of Requirement Modals -->
<!-- Modal for Adding Requirement -->
<div class="modal fade" id="addRequirementModal" tabindex="-1" aria-labelledby="addRequirementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRequirementModalLabel">Add New Requirement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addRequirementForm">
          <!-- Service Select Dropdown -->
          <div class="mb-3">
            <label for="addServiceId" class="form-label">Service</label>
            <select class="form-control" id="addServiceId" required>
              <option value="">Select a Service</option>
              <!-- Dynamically populated service options will go here -->
            </select>
          </div>
          <div class="mb-3">
            <label for="addDescription" class="form-label">Description</label>
            <input type="text" class="form-control" id="addDescription" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Requirement</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Requirement Modal -->
<div class="modal fade" id="editRequirementModal" tabindex="-1" aria-labelledby="editRequirementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRequirementModalLabel">Edit Requirement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editRequirementForm">
          <input type="hidden" id="editRequirementId">
          <!-- Service Select Dropdown -->
          <div class="mb-3">
            <label for="editServiceId" class="form-label">Service</label>
            <select class="form-control" id="editServiceId" required>
              <option value="">Select a Service</option>
              <!-- Dynamically populated service options will go here -->
            </select>
          </div>
          <div class="mb-3">
            <label for="editDescription" class="form-label">Description</label>
            <input type="text" class="form-control" id="editDescription" required>
          </div>
          <button type="submit" class="btn btn-primary">Update Requirement</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Requirement Modal -->
<div class="modal fade" id="deleteRequirementModal" tabindex="-1" aria-labelledby="deleteRequirementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteRequirementModalLabel">Delete Requirement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this requirement?</p>
        <input type="hidden" id="deleteRequirementId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteRequirement">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- End of Requirement Modals -->