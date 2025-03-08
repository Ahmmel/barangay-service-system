<?php
class Admin
{
    private $conn;
    private $table_name = "admins";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Login Admin
    public function login($identifier, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :identifier OR username = :identifier";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":identifier", $identifier);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }
}
