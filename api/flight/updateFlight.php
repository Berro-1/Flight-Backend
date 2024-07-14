<?php
require_once '../../config/config.php';
require_once '../../models/Flight.php';

$data = json_decode(file_get_contents("php://input"), true);

$flight_id = $data['flight_id'];
$flight_number = $data['flight_number'];
$departure_airport_id = $data['departure_airport_id'];
$destination_airport_id = $data['destination_airport_id'];
$departure_datetime = $data['departure_datetime'];
$arrival_datetime = $data['arrival_datetime'];
$available_seats = $data['available_seats'];

$flight = new Flight($mysqli);
$response = $flight->update($flight_id, $flight_number, $departure_airport_id, $destination_airport_id, $departure_datetime, $arrival_datetime, $available_seats);

echo json_encode($response);
?>
