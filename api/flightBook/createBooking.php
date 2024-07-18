<?php
header('Content-Type: application/json');

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../../config/config.php';
require_once '../../models/FlightBooking.php';

$bookingModel = new Booking($mysqli);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $user_id = $input['user_id'] ?? null;
    $flight_id = $input['flight_id'] ?? null;
    $booking_date = $input['booking_date'] ?? null;

    // Validation
    $errors = [];

    if (!isset($user_id)) {
        $errors[] = 'User ID is required';
    }

    if (!isset($flight_id)) {
        $errors[] = 'Flight ID is required';
    }

    if (!isset($booking_date)) {
        $errors[] = 'Booking date is required';
    }

    if (count($errors) > 0) {
        echo json_encode(['error' => $errors]);
        exit;
    }

    // Create booking
    $response = $bookingModel->createBooking($user_id, $flight_id, $booking_date);
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Wrong method']);
}
