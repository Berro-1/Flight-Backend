<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
require_once '../../config/config.php';
require_once '../../models/flight.php';

$flightModul = new Flight($mysqli);

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    $departure = $_GET['departure'];
    $destination = $_GET['destination'];

    $response = $flightModul->getFlightByDepartureDestinatin($departure, $destination);

    echo json_encode($response);
}else{
    $response = ["message" => "All fields are required."];
}