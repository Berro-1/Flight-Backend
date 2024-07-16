<?php
require_once '../../config/config.php';
require_once '../../models/Airport.php';
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 

$airport = new Airport($mysqli);
$response = $airport->getAllAirports();

header('Content-Type: application/json');
echo json_encode($response);


