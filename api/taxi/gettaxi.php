<?php
require '../../config/config.php'; // Path to your database connection
require '../../models/Taxi.php';   // Path to Taxi model

// Initialize the database connection
$mysqli = getDBConnection();

// Create an instance of the Taxi model
$taxiModel = new Taxi($mysqli);

// Determine the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // Get the taxi_id from query parameters
    $taxi_id = $_GET['taxi_id'] ?? null;
    
    if ($taxi_id) {
        $result = $taxiModel->getById($taxi_id);
    } else {
        $result = ["error" => "taxi_id is required"];
    }

    echo json_encode($result);

} else {
    echo json_encode(["error" => "Unsupported request method"]);
}
?>
