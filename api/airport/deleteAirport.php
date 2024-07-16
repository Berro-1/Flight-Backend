<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
require_once '../../config/config.php';
require_once '../../models/Flight.php';

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
