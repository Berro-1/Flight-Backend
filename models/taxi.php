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
}
?>
