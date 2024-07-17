<?php
require_once '../../config/config.php';
require_once '../../models/Flight.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

header('Content-Type: application/json');

if (isset($_GET['departure_airport_id']) && isset($_GET['destination_airport_id'])) {
    $departure_airport_id = $_GET['departure_airport_id'];
    $destination_airport_id = $_GET['destination_airport_id'];

    $flight = new Flight($mysqli);
    $response = $flight->getFlights($departure_airport_id, $destination_airport_id);
} else {
    $response = ["message" => "All fields are required."];
}

echo json_encode($response);
