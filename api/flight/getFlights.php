<?php
require_once '../../config/config.php';
require_once '../../models/Flight.php';

if (
    isset($_GET['departure_airport_id']) &&
    isset($_GET['destination_airport_id']) &&
    isset($_GET['departure_datetime']) &&
    isset($_GET['arrival_datetime'])
) {
    $departure_airport_id = $_GET['departure_airport_id'];
    $destination_airport_id = $_GET['destination_airport_id'];
    $departure_datetime = $_GET['departure_datetime'];
    $arrival_datetime = $_GET['arrival_datetime'];

    $flight = new Flight($mysqli);
    $response = $flight->getFlights($departure_airport_id, $destination_airport_id, $departure_datetime, $arrival_datetime);
} else {
    $response = ["message" => "All fields are required."];
}

echo json_encode($response);