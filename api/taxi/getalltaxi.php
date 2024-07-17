<?php
// Enable CORS
header('Access-Control-Allow-Origin: *'); // Allow all origins (for development)
header('Access-Control-Allow-Methods: GET, POST'); // Allowed request methods
header('Access-Control-Allow-Headers: Content-Type'); // Allowed headers

require '../../config/config.php'; 
require '../../models/Taxi.php';  

$mysqli = getDBConnection();

// Create an instance of the Taxi model
$taxiModel = new Taxi($mysqli);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  
    $taxis = $taxiModel->getAllTaxis();

    header('Content-Type: application/json');
    echo json_encode($taxis);
} else {

    echo json_encode(["error" => "Unsupported request method"]);
}
?>
