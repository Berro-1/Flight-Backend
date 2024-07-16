<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
require_once '../../config/config.php';
require_once '../../models/Flight.php';

if (
    isset($_GET['departure_airport_id']) &&
    isset($_GET['destination_airport_id']) 
) {
    $departure_airport_id = $_GET['departure_airport_id'];
    $destination_airport_id = $_GET['destination_airport_id'];
 

    $flight = new Flight($mysqli);
    $response = $flight->getFlights($departure_airport_id, $destination_airport_id);
} else {
    $response = ["message" => "All fields are required."];
}

echo json_encode($response);