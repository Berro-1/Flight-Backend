<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModel = new hotel($mysqli);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $searchTerm = isset($data['searchTerm']) ? $data['searchTerm'] : '';

    $hotels = $hotelModel->searchHotels($searchTerm);

    echo json_encode($hotels);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
