<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require '../../config/config.php'; // Database connection
require '../../models/Taxi.php';   // Taxi model

// Initialize the database connection
$mysqli = getDBConnection();
$taxiModel = new Taxi($mysqli);

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

} elseif ($method == 'POST') {
    // Read JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    $action = $data['action'] ?? null; // Determine the action
    $taxi_id = $data['taxi_id'] ?? null;
    $driver_name = $data['driver_name'] ?? null;
    $status = $data['status'] ?? null;
    $from_location = $data['from_location'] ?? null;
    $to_location = $data['to_location'] ?? null;

    if ($action == 'create') {
        // Create a new taxi
        if ($driver_name && $status && $from_location && $to_location) {
            $result = $taxiModel->createTaxi($driver_name, $status, $from_location, $to_location);
        } else {
            $result = ["error" => "Missing parameters for creating a taxi"];
        }

    } elseif ($action == 'update') {
       
        if ($taxi_id && $driver_name && $status && $from_location && $to_location) {
            $result = $taxiModel->updateTaxi($taxi_id, $driver_name, $status, $from_location, $to_location);
        } else {
            $result = ["error" => "Missing parameters for updating a taxi"];
        }
    } elseif ($action == 'delete') {
  
        if ($taxi_id) {
            $result = $taxiModel->deleteTaxi($taxi_id);
        } else {
            $result = ["error" => "taxi_id is required for deletion"];
        }
    } else {
        $result = ["error" => "Invalid action"];
    }

    echo json_encode($result);
} else {
    echo json_encode(["error" => "Unsupported request method"]);
}
?>
