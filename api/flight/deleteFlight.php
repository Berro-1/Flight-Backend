<?php
require_once '../../config/config.php';
require_once '../../models/Flight.php';

$data = json_decode(file_get_contents("php://input"), true);

$flight_id = $data['flight_id'];

$flight = new Flight($mysqli);
$response = $flight->delete($flight_id);

echo json_encode($response);
