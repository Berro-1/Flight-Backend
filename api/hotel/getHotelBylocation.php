<?php
require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new Hotel($mysqli);

if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    $location = $_GET['location'];

    $response = $hotelModul->getHotelByLocation($location);

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'wrong method']);
}

