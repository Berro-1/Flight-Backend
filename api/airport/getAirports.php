<?php
require_once '../../config/config.php';
require_once '../../models/Airport.php';

$airport = new Airport($mysqli);
$response = $airport->getAllAirports();

header('Content-Type: application/json');
echo json_encode($response);
