<?php

Class BookHotel
{
    private $mysqli;

    public function __construct($mysqli)
        
    {
        $this->mysqli = $mysqli;
    }

    public function bookHotel($booking_id, $hotel_id, $date){

        $query = 'insert into hotelbookings (booking_id, hotel_id, checkin_date) values (?, ?, ?)';
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error);
        } 
        $stmt->bind_param('iis',$booking_id, $hotel_id, $date);
        $stmt->execute();
        return ['message' => 'Booked successfully' ];

    }
}   
