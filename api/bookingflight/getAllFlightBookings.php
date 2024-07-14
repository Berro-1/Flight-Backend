<?php
require '../../config/config.php'; // Path to  database connection
require '../../models/FlightBooking.php';   // Path to taxi model




$flightBooking = new $Booking($msqli);

$response = $flightBooking->getAllFlightBookings();

header('Content-Type: application/json');
echo json_encode($response);
?>
