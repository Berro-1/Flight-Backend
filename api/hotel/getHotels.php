<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new Hotel($mysqli);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;

    $response = $hotelModul->getAllHotels($id, $name);

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'wrong method']);
}

