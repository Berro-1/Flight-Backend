<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization');


require '../../config/config.php'; 
require '../../models/Booking.php';
$bookingModel = new Booking($mysqli);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

if ($requestMethod == 'POST') {
  
    if (isset($data['booking_id'], $data['taxi_id'], $data['pickup_location'], $data['destination'])) {
        $response = $bookingModel->bookTaxi(
            $data['booking_id'],
            $data['taxi_id'],
            $data['pickup_location'],
            $data['destination']
        );
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Missing required fields for taxi booking.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
