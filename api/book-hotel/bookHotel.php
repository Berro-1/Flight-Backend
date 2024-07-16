<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../../config/config.php';
require_once '../../models/book_hotel.php';

$bookHotelModul = new BookHotel($mysqli);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $booking_id = $_POST['booking_id'];
    $hotel_id = $_POST['hotel_id'];
    $date = $_POST['date'];

    $response = $bookHotelModul->bookHotel($booking_id, $hotel_id, $date);

    echo json_encode($response); 
}else{
    echo json_encode(['error' => 'wrong method']);
}
