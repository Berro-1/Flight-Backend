<?php
require_once '../../config/config.php';
require_once '../../models/Flight.php';

$flight_id = $_GET['flight_id'];

$flight = new Flight($mysqli);
$response = $flight->getOneFlight($flight_id);

echo json_encode($response);
?>
