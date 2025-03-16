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
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteUserModalLabel"><i class="fas fa-trash-alt mr-2"></i> <strong>Delete User Confirmation</strong></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        </div>
        <p class="text-center">Are you sure you want to permanently delete this user? This action cannot be undone, and all data related to this user will be lost. Please confirm your decision.</p>
        <input type="hidden" id="deleteUserId">
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
        <form id="addServiceForm">
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
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteServiceModalLabel">
          <i class="fas fa-trash-alt mr-2"></i> <strong>Delete Service Confirmation</strong>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        </div>
        <p class="text-center">
          Are you sure you want to permanently delete this service? This action cannot be undone, and all related data will be lost. Please confirm your decision.
        </p>
        <input type="hidden" id="deleteServiceId">
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <!-- Left-aligned button (Cancel) -->
        <button type="button" class="btn btn-secondary btn-lg px-5" data-dismiss="modal">
          <i class="fas fa-times"></i> Cancel
        </button>
        <!-- Right-aligned button (Confirm) -->
        <button type="button" id="confirmDeleteServiceBtn" class="btn btn-danger btn-lg px-5">
          <i class="fas fa-trash-alt"></i> Confirm Deletion
        </button>
      </div>
    </div>
  </div>
</div>
<!-- End of Services Modals -->

<!-- Start of Requirement Modals -->
<!-- Modal for Adding Requirement -->
<div class="modal fade" id="addRequirementModal" tabindex="-1" role="dialog" aria-labelledby="addRequirementModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"> <!-- Modal with larger size -->
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addRequirementModalLabel"><i class="fas fa-file-alt"></i> Add New Requirement</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Add Requirement Form -->
        <form id="addRequirementForm">
          <div class="form-row">
            <!-- Service Selection -->
            <div class="form-group col-md-12">
              <label for="addRequirementServiceId" class="form-label fw-bold"><i class="fas fa-cogs"></i> Select Service</label>
              <select class="form-select" id="addRequirementServiceId" name="serviceId" required>
                <option value="" disabled selected>-- Choose Service --</option>
                <!-- Dynamically populated service options -->
              </select>
            </div>
          </div>

          <div class="form-row">
            <!-- Requirement Description -->
            <div class="form-group col-md-12">
              <label for="addRequirementDescription" class="form-label fw-bold"><i class="fas fa-file-alt"></i> Requirement Description</label>
              <textarea class="form-control" id="addRequirementDescription" name="description" rows="4" placeholder="Enter requirement details" required
                style="width: 100%; resize: none; max-height: 150px;"></textarea>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary mt-3 btn-block">
            <i class="fas fa-suitcase"></i> Add Requirement
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Edit Requirement Modal -->
<div class="modal fade" id="editRequirementModal" tabindex="-1" aria-labelledby="editRequirementModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="editRequirementModalLabel"><i class="fas fa-edit"></i> Edit Requirement</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editRequirementForm">
          <!-- Hidden Requirement ID -->
          <input type="hidden" id="editRequirementId" name="requirementId">

          <!-- Service Selection -->
          <div class="mb-3">
            <label for="editRequirementServiceId" class="form-label fw-bold">Select Service</label>
            <select class="form-select" id="editRequirementServiceId" name="serviceId" required>
              <option value="" disabled selected>-- Choose Service --</option>
              <!-- Dynamically populated service options -->
            </select>
          </div>

          <!-- Requirement Description -->
          <div class="mb-3">
            <label for="editRequirementDescription" class="form-label fw-bold">Requirement Description</label>
            <textarea class="form-control" id="editRequirementDescription" rows="4" name="description" placeholder="Enter requirement details" required
              style="width: 100%; resize: none; max-height: 150px;"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning" form="editRequirementForm"><i class="fas fa-save"></i> Update Requirement</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Requirement Modal -->
<div class="modal fade" id="deleteRequirementModal" tabindex="-1" role="dialog" aria-labelledby="deleteRequirementModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteRequirementModalLabel">
          <i class="fas fa-trash-alt mr-2"></i> <strong>Delete Requirement Confirmation</strong>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        </div>
        <p class="text-center">
          Are you sure you want to permanently delete this requirement? This action cannot be undone, and all data related to this requirement will be lost. Please confirm your decision.
        </p>
        <input type="hidden" id="deleteRequirementId">
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <!-- Left-aligned button (Cancel) -->
        <button type="button" class="btn btn-secondary btn-lg px-5" data-dismiss="modal">
          <i class="fas fa-times"></i> Cancel
        </button>
        <!-- Right-aligned button (Confirm) -->
        <button type="button" id="confirmDeleteRequirementBtn" class="btn btn-danger btn-lg px-5">
          <i class="fas fa-trash-alt"></i> Confirm Deletion
        </button>
      </div>
    </div>
  </div>
</div>
<!-- End of Requirement Modals -->
<!-- Start of Transaction Modals -->
<!-- Modal for Adding Transaction -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" role="dialog" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"> <!-- Modal with larger size -->
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="addTransactionModalLabel"><i class="fas fa-exchange-alt"></i> Add Transaction</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add Transaction Form -->
        <form id="addTransactionForm" action="TransactionController.php?action=add" method="POST" onsubmit="return validateServices()">

          <!-- User ID Input -->
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="addUserId"><i class="fas fa-user"></i> User ID</label>
              <input type="text" class="form-control" id="addUserId" name="userId" placeholder="Enter user ID" required autocomplete="off" />
            </div>
          </div>

          <!-- List of Services (Multiple Select with Search) -->
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="addServices"><i class="fas fa-cogs"></i> Select Services</label>
              <br>
              <select class="form-control" id="addServices" name="services[]" multiple required>
                <!-- Dynamically populated service options -->
              </select>
              <small id="serviceHelp" class="form-text text-muted">Select 1 to 3 services.</small>
            </div>
          </div>

          <!-- Status (Pending, Disabled) -->
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="addStatus"><i class="fas fa-clock"></i> Status</label>
              <input type="text" class="form-control" id="addStatus" name="status" value="Pending" disabled />
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-success mt-3 btn-block">
            <i class="fas fa-exchange-alt"></i> Add Transaction
          </button>
        </form>
      </div>
    </div>
  </div>
</div>