<?php
class Requirement
{
    private $conn;
    private $table_name = "requirements";

    // Constructor to initialize the database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new requirement
    public function createRequirement($serviceId, $description)
    {
        $query = "INSERT INTO " . $this->table_name . " (service_id, description)
                  VALUES (:service_id, :description)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters to the query
        $stmt->bindParam(":service_id", $serviceId);
        $stmt->bindParam(":description", $description);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Retrieve all requirements
    public function getAllRequirements()
    {
        $query = "SELECT r.id, s.service_name, r.description FROM " . $this->table_name . " r JOIN services s ON r.service_id = s.id ORDER BY r.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch all requirements
        $requirements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $requirements;
    }

    // Retrieve a requirement by its ID
    public function getRequirementById($id)
    {
        $query = "SELECT id, service_id, description
                  FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        // Fetch the requirement details
        $requirement = $stmt->fetch(PDO::FETCH_ASSOC);

        return $requirement;
    }

    // Update an existing requirement
    public function updateRequirement($id, $serviceId, $description)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET service_id = :service_id, description = :description, modified_at = current_timestamp()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(":service_id", $serviceId);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":id", $id);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a requirement
    public function deleteRequirement($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Retrieve requirements by service ID
    public function getRequirementsByServiceId($serviceId)
    {
        $query = "SELECT id, description FROM " . $this->table_name . " WHERE service_id = :service_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":service_id", $serviceId);

        $stmt->execute();

        // Fetch the requirements for the service
        $requirements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $requirements;
    }

    // Retrieve all services
    public function getServices()
    {
        $query = "SELECT id, service_name FROM services";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch all services
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $services;
    }
}
