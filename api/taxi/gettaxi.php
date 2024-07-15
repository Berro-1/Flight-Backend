<?php
require '../../config/config.php'; // Path to  database connection
require '../../models/Taxi.php';   // Path to taxi model


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

} elseif ($method == 'POST') {
    // Get the taxi details from POST data
    $action = $_POST['action'] ?? null;// to know if update or create 
    $taxi_id = $_POST['taxi_id'] ?? null;
    $driver_name = $_POST['driver_name'] ?? null;
    $status = $_POST['status'] ?? null;
    $from_location = $_POST['from_location'] ?? null;
    $to_location = $_POST['to_location'] ?? null;


    if ($action == 'create') {
        // Create a new taxi
        if ($driver_name && $status && $from_location && $to_location) {
            $result = $taxiModel->createTaxi($driver_name, $status, $from_location, $to_location);
        } else {
            $result = ["error" => "Missing parameters for creating a taxi"];
        }
    
    } 
    elseif ($action == 'update') {
        // Update an existing taxi
        if ($taxi_id && $driver_name && $status && $from_location && $to_location) {
            $result = $taxiModel->updateTaxi($taxi_id, $driver_name, $status, $from_location, $to_location);
        } else {
            $result = ["error" => "Missing parameters for updating a taxi"];
        }
    }
    elseif ($action == 'delete') {
        // Delete a taxi
        if ($taxi_id) {
            $result = $taxiModel->deleteTaxi($taxi_id);
        } else {
            $result = ["error" => "taxi_id is required for deletion"];
        }
    }
    else {
        $result = ["error" => "Invalid action"];
    }

    echo json_encode($result);

} else {
    echo json_encode(["error" => "Unsupported request method"]);
};