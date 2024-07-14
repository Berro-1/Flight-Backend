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


}
?>
