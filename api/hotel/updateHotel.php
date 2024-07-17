<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization');
require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new Hotel($mysqli);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $data = json_decode(file_get_contents("php://input"), true);

    
    $id = isset($data['hotel_id']) ? $data['hotel_id'] : null;
    $name = isset($data['hotel_name']) ? $data['hotel_name'] : null;
    $location = isset($data['location']) ? $data['location'] : null;
    $rooms = isset($data['available_rooms']) ? $data['available_rooms'] : null;

    if ($id === null) {
        echo json_encode(['error' => 'Missing hotel ID']);
        exit;
    }

    $response = $hotelModul->updateHotel($id, $name, $location, $rooms);
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'wrong method']);
}
