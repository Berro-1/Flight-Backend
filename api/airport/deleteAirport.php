<?php
require_once '../../config/config.php';
require_once '../../models/Airport.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->Airport_id)) {
    $airport_id = $data->Airport_id;

    $airport = new Airport($mysqli);
    $response = $airport->deleteAirport($airport_id);
} else {
    $response = ["message" => "Airport ID is required."];
}

header('Content-Type: application/json');
echo json_encode($response);
