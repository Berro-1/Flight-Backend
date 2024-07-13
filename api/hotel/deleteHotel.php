<?php
require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new hotel($mysqli);
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id = $_POST['id'];
    
    $response = $hotelModul->deleteHotelById($id);

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'wrong method']);
}



