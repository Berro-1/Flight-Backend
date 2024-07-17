<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Replace with your actual API keys


// Include the provided PHP files for hotel, flight, and taxi functionalities
include_once '/mnt/data/gettaxi.php';
include_once '/mnt/data/getHotels.php';
include_once '/mnt/data/getAllFlightBookings.php';

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if (!isset($data['message'])) {
    echo json_encode(['error' => 'Message is required']);
    exit();
}

$message = $data['message'];

// Extract destination from the user's message
preg_match('/to (\w+)/', $message, $matches);
$destination = isset($matches[1]) ? $matches[1] : 'your destination';

// Fetch data using the provided functions
$flight_schedules = getAllFlightBookings($destination); // Assume function name based on file
$hotel_availability = getHotels($destination); // Assume function name based on file
$taxi_services = getTaxi($destination); // Assume function name based on file

$prompt = "
User is planning a trip to $destination.
Here are the details you have:
- $flight_schedules
- $hotel_availability
- $taxi_services

Provide a comprehensive trip plan including recommendations for hotels, car rentals, and tourist attractions at $destination.
";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ['role' => 'user', 'content' => $prompt]
    ]
]));

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Request Error:' . curl_error($ch)]);
    exit();
}

curl_close($ch);

echo $response;
?>
