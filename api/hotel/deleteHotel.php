<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../../config/config.php';
require_once '../../models/hotels.php';

<<<<<<< HEAD
$hotelModul = new hotel($mysqli);
=======
$hotelModul = new Hotel($mysqli);
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
>>>>>>> 51196cecc0deb78ddd3707a6c0825f1f16ba375b

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if ($id) {
        $response = $hotelModul->deleteHotelById($id);
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'No ID provided']);
    }
} else {
    echo json_encode(['error' => 'Method not allowed']);
}
