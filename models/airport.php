<?php

class Airport
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getAllAirports()
    {
        $query = 'SELECT * FROM airports';
        $result = $this->mysqli->query($query);
        if (!$result) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAirportById($airport_id)
    {
        if (!is_numeric($airport_id) || $airport_id <= 0) {
            return ["message" => "Invalid airport ID"];
        }

        $query = 'SELECT * FROM airports WHERE Airport_id = ?';
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }

        $stmt->bind_param("i", $airport_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }

        return $result->fetch_assoc();
    }


    public function createAirport($airport_name)
    {
        if (isset($airport_name)) {
            return ["message" => "Airport name is required"];
        }

        $query = 'INSERT INTO airports (AirportName) VALUES (?)';
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }

        $stmt->bind_param("s", $airport_name);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return ["message" => "Airport created successfully", "Airport_id" => $this->mysqli->insert_id];
        } else {
            return ["message" => "Failed to create airport"];
        }
    }


}