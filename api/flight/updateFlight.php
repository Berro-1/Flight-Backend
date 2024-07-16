<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 

require_once '../../config/config.php';
require_once '../../models/Flight.php';

$data = json_decode(file_get_contents("php://input"), true);

try {
    $flight_id = $data['flight_id'];
    $flight_number = $data['flight_number'];
    $departure_airport_id = $data['departure_airport_id'];
    $arrival_airport_id = $data['arrival_airport_id']; 
    $departure_datetime = $data['departure_datetime'];
    $arrival_datetime = $data['arrival_datetime']; 
    $available_seats = $data['available_seats'];

    $flight = new Flight($mysqli);
    $response = $flight->update($flight_id, $flight_number, $departure_airport_id, $arrival_airport_id, $departure_datetime, $arrival_datetime, $available_seats);

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

