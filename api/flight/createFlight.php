<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 

require_once '../../config/config.php';
require_once '../../models/Flight.php';

// Initialize the Flight class
$flightModel = new Flight($mysqli);

// Count the flights
$response = $flightModel->countFlights(); // Ensure you have this method in the Flight model

header('Content-Type: application/json');
echo json_encode($response);
