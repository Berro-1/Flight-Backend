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
        $stmt = $this->mysqli->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

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

    public function deleteHotel($id){

        $query = 'delete from hotels where hotel_id=?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if($stmt->effected_rows > 0){
            return ['message'=>'hotel deleted successfully', 'rowCount'=>$stmt->effected_rows]; 
        } else {
            return ['message'=>'hotel not found'];
        }
    }
}