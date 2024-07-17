<?php
//The Taxi class is responsible for interacting with the 'taxis' table in the database.
class Taxi {
    private $conn; // This will hold the database connection object.
//This is the constructor for the Taxi class
//It take a MySQLi connection object as a parametr and sets it for use in the class
    
    public function __construct($conn) {
        $this->conn = $conn; // Store the connection object for later use in the class.
    }

    //Retrieve a taxi record from the database using its ID.
    // This method queries the database for a taxi with the specified ID and returns the details.
    // This will return the taxi details if found, or a message indicating no record was found.
    public function getById($taxi_id) {
        // Prepare a SQL statement to select a taxi from the database based on the id
        $stmt = $this->conn->prepare('SELECT * FROM taxis WHERE taxi_id = ?');
        if (!$stmt) {
            return ["error" => "Prepare failed: " . $this->conn->error]; // Return error message if failed
        }
        $stmt->bind_param('i', $taxi_id);// to provide sql injection


        if (!$stmt->execute()) {
            return ["error" => "Execute failed: " . $this->conn->error]; 
        }

        $result = $stmt->get_result();
        // Fetch the taxi details(associative array.)
        $taxi = $result->fetch_assoc();
        if ($taxi) {
            return $taxi; 
        } else {
            return ["message" => "No record found"]; 
        }
    }



    // New method to update taxi details
    public function updateTaxi($taxi_id, $driver_name, $status, $from_location, $to_location) {
        $stmt = $this->conn->prepare('
            UPDATE taxis 
            SET driver_name = ?, 
                status = ?, 
                from_location = ?, 
                to_location = ? 
            WHERE taxi_id = ?
        ');

        $stmt->bind_param('ssssi', $driver_name, $status, $from_location, $to_location, $taxi_id);

        try {

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return ["message" => "Taxi updated successfully"];
            } else {
                return ["message" => "No changes made"];
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }


    public function searchTaxis($searchTerm) {
        $searchTerm = '%' . $searchTerm . '%'; // Wildcards for LIKE
        $stmt = $this->conn->prepare("SELECT * FROM taxis WHERE driver_name LIKE ? OR status LIKE ? OR from_location LIKE ? OR to_location LIKE ?");
        $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $taxis = [];
        while ($row = $result->fetch_assoc()) {
            $taxis[] = $row;
        }

        return $taxis;
    }
    public function createTaxi($driver_name, $status, $from_location, $to_location) {
        $stmt = $this->conn->prepare('INSERT INTO taxis (driver_name, status, from_location, to_location) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $driver_name, $status, $from_location, $to_location);
    
        try {
            $stmt->execute();
            return ["message" => "New taxi created successfully", "status" => "success"];
        } catch (Exception $e) {
            return ["error" => $stmt->error];
        }
    }


    public function getAllTaxis() {
        $stmt = $this->conn->prepare('SELECT * FROM taxis');
        $stmt->execute();
        $result = $stmt->get_result();
        
        $taxis = [];
        while ($row = $result->fetch_assoc()) {
            $taxis[] = $row;
        }
        
        return $taxis;
    }


    public function deleteTaxi($taxi_id) {
        $stmt = $this->conn->prepare('DELETE FROM taxis WHERE taxi_id=?');
        $stmt->bind_param('i', $taxi_id);
    
        try {
            $stmt->execute();
            return ["message" => "Taxi record deleted successfully", "status" => "success"];
        } catch (Exception $e) {
            return ["error" => $stmt->error];
        }
    }

    public function searchTaxisByDriver($driver_name) {
        $stmt = $this->conn->prepare('SELECT * FROM taxis WHERE driver_name LIKE ?');
        $searchTerm = "%{$driver_name}%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $taxis = [];
        while ($row = $result->fetch_assoc()) {
            $taxis[] = $row;
        }
    
        return $taxis;
    }
    
    
    
    
}

