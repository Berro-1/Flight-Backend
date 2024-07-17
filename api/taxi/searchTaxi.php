<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require '../../config/config.php'; 
require '../../models/taxi.php';  

$mysqli = getDBConnection();
$taxiModel = new Taxi($mysqli);

$requestBody = json_decode(file_get_contents('php://input'), true);
$searchTerm = $requestBody['searchTerm'] ?? '';

if (!empty($searchTerm)) {
    $result = $taxiModel->searchTaxis($searchTerm);
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Search term is required"]);
}
