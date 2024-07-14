<?php
class Booking{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
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
}
?>
