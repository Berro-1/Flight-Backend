<?php

require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new Hotel($mysqli);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $location = isset($_POST['location']) ? $_POST['location'] : null;
    $rooms = isset($_POST['rooms']) ? $_POST['rooms'] : null;

    $response = $hotelModul->updateHotel($id, $name, $location, $rooms);

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'wrong method']);
}

