<?php
require_once __DIR__ . '/../models/SystemSettings.php';
class User
{
    private $conn;
    private $table_name = "users";
    private $systemSettings;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->systemSettings = SystemSettings::getInstance($this->conn);
    }

    function isStaffAllowedToUpdate(): bool
    {
        // Get cutoff time from settings (default to 17:00 if not set)
        $cutoff = $this->systemSettings->get('staff_update_cutoff_time', '17:00');
        $staffStartTime = $this->systemSettings->get('staff_update_start_time', '07:00'); // Assuming the correct setting name is `staff_update_start_time`

        // Get current time in 'Asia/Manila' timezone
        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $currentTime = $now->format('H:i');

        // Staff can only update if current time is >= staff start time and < cutoff time
        return $currentTime >= $staffStartTime && $currentTime < $cutoff;
    }



    // Create a new user
    public function createUser($username, $email, $password, $gender, $birthdate, $address, $isVerified, $profilePicture, $roleId, $firstName, $middleName, $lastName, $suffix, $maritalStatus, $mobileNumber)
    {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, gender_id, birthdate, address, is_verified, profile_picture, role_id, first_name, middle_name, last_name, suffix, marital_status_id, mobile_number)
              VALUES (:username, :email, :password, :gender, :birthdate, :address, :is_verified, :profile_picture, :role_id, :first_name, :middle_name, :last_name, :suffix, :marital_status, :mobile_number)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':is_verified', $isVerified);
        $stmt->bindParam(':profile_picture', $profilePicture);
        $stmt->bindParam(':role_id', $roleId);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':middle_name', $middleName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':marital_status', $maritalStatus);
        $stmt->bindParam(':mobile_number', $mobileNumber);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fetch all users from the database
    public function getUsers()
    {
        $query = "SELECT u.id, u.first_name, u.middle_name, u.last_name, u.suffix, 
                 u.email, u.is_verified, u.username, u.mobile_number, u.profile_picture,
                 r.role_name, g.gender_name as gender, g.id as gender_id
                FROM " . $this->table_name . " u
                JOIN user_roles r ON u.role_id = r.id
                JOIN genders g ON u.gender_id = g.id ORDER BY u.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return an associative array of users
    }

    // Fetch a single user by ID
    public function getUserById($id)
    {
        // SQL query to fetch user details
        $query = "SELECT id, first_name, middle_name, last_name, suffix, 
                     email, is_verified, username, mobile_number,
                     role_id, gender_id, marital_status_id, birthdate,
                     address, profile_picture, username
              FROM " . $this->table_name . " WHERE id = :id";

        // Prepare and execute the query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query and return the result
        $stmt->execute();

        // Return the fetched user data or an empty array if no user found
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getUserRoles()
    {
        $query = "SELECT id, role_name From user_roles";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserGenders()
    {
        $query = "SELECT id, gender_name From genders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaritalStatus()
    {
        $query = "SELECT id, status_name From marital_statuses";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update user information
    public function updateUser(
        $id,
        $username,
        $email,
        $gender,
        $birthdate,
        $address,
        $is_verified,
        $role_id,
        $first_name,
        $middle_name,
        $last_name,
        $suffix,
        $marital_status,
        $mobile_number
    ) {
        $query = "UPDATE " . $this->table_name . " 
              SET username = :username, email = :email, gender_id = :gender, birthdate = :birthdate, address = :address, 
                  is_verified = :is_verified, role_id = :role_id, first_name = :first_name, 
                  middle_name = :middle_name, last_name = :last_name, suffix = :suffix, marital_status_id = :marital_status, 
                  mobile_number = :mobile_number 
              WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':is_verified', $is_verified);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':middle_name', $middle_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':marital_status', $marital_status);
        $stmt->bindParam(':mobile_number', $mobile_number);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updatePassword($id, $password)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET password = :password 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':password', $password);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateProfile($id, $profileImagePath)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET profile_picture = :profileImage 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':profileImage', $profileImagePath);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete user
    public function deleteUser($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            $error = $stmt->errorInfo();
            error_log("SQL error: " . $error[2]); // This will log the error message
            return false;
        }
        return true;
    }

    // Login using email OR username
    public function login($identifier, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :identifier OR username = :identifier";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":identifier", $identifier);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    //check user if already exists
    public function checkUserExist($email, $username)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email OR username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // checkEmailExists
    public function checkEmailExists($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email AND role_id IN (2, 3)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // checkUsernameExists
    public function checkUsernameExists($username)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username AND role_id IN (2, 3)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    //checkMobileExists
    public function checkMobileExists($mobile)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE mobile_number = :mobile AND role_id IN (2, 3)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Get user details by ID
    public function getUserDetailsById($userId)
    {
        $query = "SELECT * FROM user_details WHERE id = :userId LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registerUser($email, $username, $password, $firstName, $lastName, $gender, $birthdate, $mobileNumber)
    {
        $query = "INSERT INTO " . $this->table_name . " 
        (email, username, password, first_name, last_name, gender_id, birthdate, mobile_number) 
        VALUES (:email, :username, :password, :first_name, :last_name, :gender, :birthdate, :mobile_number)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':mobile_number', $mobileNumber);

        return $stmt->execute();
    }


    public function generateNewPassword($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function resetPassword($email, $newPassword)
    {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $newPassword);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getUserMobileByEmail($email)
    {
        $query = "SELECT id, mobile_number FROM {$this->table_name} WHERE email = :email AND role_id != 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function changePassword($userId, $newPassword)
    {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':password', $newPassword);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getPasswordHashById($userId)
    {
        $query = "SELECT password FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getByMobile($mobile)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE mobile_number = :mobile";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId()
    {
        return $this->conn->lastInsertId();
    }
}
