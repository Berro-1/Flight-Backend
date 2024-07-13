<?php

class hotel
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
    public function getAllHotels(){

        $query = 'select * from hotels';
        $result = $this->mysqli->prepare($query);

        if($result->num_rows > 0){
            $hotels = [];
            while ($row = $result->fetch_assoc()){
                $hotels[] = $row;
            }
            return $hotels;
        } else {
            return [];
        }
        
    }

    public function getHotelById($id){

        $query = 'select * from hotels where id=?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function addHotel($name, $location, $rooms){

        $query = 'insert into hotels (hotel_name, location, available_rooms) values (?, ?, ?)';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('ssi', $name, $location, $rooms);
        return $stmt->execute();
    }
}