<?php
require_once '../../config/config.php';
require_once '../../models/Airport.php';
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization');
$data = json_decode(file_get_contents("php://input"));

if (isset($data->Airport_id) && isset($data->AirportName)) {
    $airport_id = $data->Airport_id;
    $airport_name = $data->AirportName;

    $airport = new Airport($mysqli);
    $response = $airport->updateAirport($airport_id, $airport_name);
} else {
    $response = ["message" => "Airport ID and name are required."];
}

header('Content-Type: application/json');
echo json_encode($response);