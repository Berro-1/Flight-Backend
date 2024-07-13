<?php
require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new hotel($mysqli);

$response = $hotelModul->deleteHotelById('id');

echo json_encode($response);