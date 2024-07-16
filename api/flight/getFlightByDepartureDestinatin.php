<?php

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