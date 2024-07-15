<?php
require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new Hotel($mysqli);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $name = $_POST['name'];
    $location = $_POST['location'];
    $rooms = $_POST['rooms'];
    
    $response = $hotelModul->addHotel($name, $location, $rooms);

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'wrong method']);
}