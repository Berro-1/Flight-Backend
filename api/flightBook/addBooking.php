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
    
    if (isset($data['user_id'], $data['flight_id'], $data['booking_date'], $data['status'])) {
        $response = $bookingModel->bookFlight(
            $data['user_id'],
            $data['flight_id'],
            $data['booking_date'],
            $data['status']
        );
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Missing required fields.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}

