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
    $input = json_decode(file_get_contents('php://input'), true);
    $hotel_name = $input['hotel_name'] ?? null;
    $location = $input['location'] ?? null;
    $hotel_rooms = $input['hotel_rooms'] ?? null;

    $response = $hotelModul->addHotel($hotel_name, $location, $hotel_rooms);
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'wrong method']);
}
