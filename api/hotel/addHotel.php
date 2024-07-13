<?php
require_once '../../config/config.php';
require_once '../../models/hotels.php';

$hotelModul = new hotel($mysqli);

$response = $hotelModul->addHotel('name', 'location', 'rooms');

echo json_encode($response);