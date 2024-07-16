<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new hotel($mysqli);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    $response = $hotelModul->getAllHotels(); 

    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    
    $response = $hotelModul->getAllHotels($id, $name);

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'wrong method']);
}
