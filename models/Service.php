<?php
class Service
{
    private $conn;
    private $table_name = "services";

    // Constructor to initialize the database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new service
    public function createService($serviceName, $serviceDescription)
    {
        $query = "INSERT INTO " . $this->table_name . " (service_name, description)
                  VALUES (:service_name, :description)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters to the query
        $stmt->bindParam(":service_name", $serviceName);
        $stmt->bindParam(":description", $serviceDescription);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Retrieve all services
    public function getServices()
    {
        $query = "SELECT id, service_name, description FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch all services
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $services;
    }

    // Retrieve a service by its ID
    public function getServiceById($id)
    {
        $query = "SELECT id, service_name, description
                  FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        // Fetch the service details
        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        return $service;
    }

    // Update an existing service
    public function updateService($id, $serviceName, $serviceDescription)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET service_name = :service_name, description = :description, modified_at = current_timestamp()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(":service_name", $serviceName);
        $stmt->bindParam(":description", $serviceDescription);
        $stmt->bindParam(":id", $id);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a service
    public function deleteService($id)
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
}
?>
