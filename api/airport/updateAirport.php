<?php
require_once '../../config/config.php';
require_once '../../models/Airport.php';

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