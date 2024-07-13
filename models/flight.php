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
        return ["message" => "Flight created successfully"];
    }
}