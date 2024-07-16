<?php
// models/Flight.php
class Flight
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function create($data)
    {
        // Validate that all required fields are set
        if (
            !isset($data['flight_number']) || !isset($data['departure_airport_id']) ||
            !isset($data['destination_airport_id']) || !isset($data['departure_datetime']) ||
            !isset($data['arrival_datetime']) || !isset($data['available_seats'])
        ) {
            return ["message" => "All fields are required."];
        }

        // Extract variables
        $flight_number = $data['flight_number'];
        $departure_airport_id = $data['departure_airport_id'];
        $destination_airport_id = $data['destination_airport_id'];
        $departure_datetime = $data['departure_datetime'];
        $arrival_datetime = $data['arrival_datetime'];
        $available_seats = $data['available_seats'];

        // Validate flight_number
        if (!is_numeric($flight_number) || $flight_number <= 0) {
            return ["message" => "Invalid flight number"];
        }

        // Validate departure_airport_id
        if (!is_numeric($departure_airport_id) || $departure_airport_id <= 0) {
            return ["message" => "Invalid departure airport ID"];
        }

        // Validate destination_airport_id
        if (!is_numeric($destination_airport_id) || $destination_airport_id <= 0) {
            return ["message" => "Invalid destination airport ID"];
        }

        // Validate departure_datetime and arrival_datetime
        if (!strtotime($departure_datetime) || !strtotime($arrival_datetime)) {
            return ["message" => "Invalid date format"];
        }

        // Validate that arrival_datetime is after departure_datetime
        if (strtotime($departure_datetime) >= strtotime($arrival_datetime)) {
            return ["message" => "Arrival datetime must be after departure datetime"];
        }

        // Validate available_seats
        if (!is_numeric($available_seats) || $available_seats < 0) {
            return ["message" => "Invalid number of available seats"];
        }

        // Insert flight
        $stmt = $this->mysqli->prepare('INSERT INTO flights (flight_number, departure_airport_id, destination_airport_id, departure_datetime, arrival_datetime, available_seats) VALUES (?, ?, ?, ?, ?, ?)');
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
        $stmt->bind_param("iiissi", $flight_number, $departure_airport_id, $destination_airport_id, $departure_datetime, $arrival_datetime, $available_seats);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return ["message" => "Flight created successfully"];
        } else {
            return ["message" => "No flight was created"];
        }
    }

    public function getFlights($departure_airport_id, $destination_airport_id, $departure_datetime, $arrival_datetime)
    {
        $query = 'SELECT * FROM flights WHERE departure_airport_id = ? AND destination_airport_id = ? AND departure_datetime >= ? AND arrival_datetime <= ? ';
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
    
        $stmt->bind_param("iiss", $departure_airport_id, $destination_airport_id, $departure_datetime, $arrival_datetime);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getallFlights()
    {
        $query = 'SELECT f.flight_id,f.flight_number,
        d.Airport_id as departure_airportid ,
        a.Airport_id as arrival_airportid,
        d.AirportName AS departure_airport,
        a.AirportName AS arrival_airport,
        f.departure_datetime,
        f.arrival_datetime,
        f.available_seats
            FROM 
                flights f
            JOIN 
                airports d ON f.departure_airport_id = d.Airport_id
            JOIN 
                airports a ON f.destination_airport_id = a.Airport_id';    
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
    
        //$stmt->bind_param("iiss", $departure_airport_id, $destination_airport_id, $departure_datetime, $arrival_datetime);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    public function getOneFlight($flight_id)
    {
        if (!isset($flight_id) || !is_numeric($flight_id) || $flight_id <= 0) {
            return ["message" => "Invalid flight ID"];
        }

        $stmt = $this->mysqli->prepare('SELECT * FROM flights WHERE flight_id = ?');
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
        $stmt->bind_param("i", $flight_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $flight = $result->fetch_assoc();
        if (!$flight) {
            return ["message" => "Flight not found"];
        }
        return $flight;
    }
    public function update($flight_id, $flight_number, $departure_airport_id, $destination_airport_id, $departure_datetime, $arrival_datetime, $available_seats)
    {
        $stmt = $this->mysqli->prepare('UPDATE flights SET flight_number = ?, departure_airport_id = ?, destination_airport_id = ?, departure_datetime = ?, arrival_datetime = ?, available_seats = ? WHERE flight_id = ?');
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
        $stmt->bind_param("iiissii", $flight_number, $departure_airport_id, $destination_airport_id, $departure_datetime, $arrival_datetime, $available_seats, $flight_id);
        $stmt->execute();
        return ["message" => "Flight updated successfully"];
    }


    public function delete($flight_id)
    {
        $stmt = $this->mysqli->prepare('DELETE FROM flights WHERE flight_id = ?');
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
        $stmt->bind_param("i", $flight_id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return ["message" => "Flight deleted successfully"];
        } else {
            return ["message" => "Flight not found"];
        }
    }
}