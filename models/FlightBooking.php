<?php
class Booking {
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Function to fetch all flight bookings with necessary details
    public function getAllFlightBookings() {
        $query = "
        SELECT u.username, f.flight_number, dep_airport.AirportName AS departure_airport_name,
        dest_airport.AirportName AS destination_airport_name FROM bookings b 
        JOIN flights f ON b.flight_id = f.flight_id JOIN users u ON b.user_id = u.user_id
        JOIN airports dep_airport ON f.departure_airport_id = dep_airport.Airport_id
        JOIN airports dest_airport ON f.destination_airport_id = dest_airport.Airport_id;
        ";

        $stmt = $this->mysqli->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        return $bookings;
    }

    public function createBooking($user_id, $flight_id, $booking_date, $status = 'accepted')
    {
        // Prepare the SQL statement
        $stmt = $this->mysqli->prepare('INSERT INTO bookings (user_id, flight_id, booking_date, status) VALUES (?, ?, ?, ?)');
    
        // Check if the statement was prepared correctly
        if (!$stmt) {
            return ["message" => "Database error: " . $this->mysqli->error];
        }
    
        // Bind the parameters to the SQL query
        $stmt->bind_param("iiss", $user_id, $flight_id, $booking_date, $status);
    
        // Execute the statement
        if ($stmt->execute()) {
            return ["message" => "Booking created successfully", "booking_id" => $stmt->insert_id];
        } else {
            return ["message" => "Error creating booking: " . $stmt->error];
        }
    }
}

