<?php

class hotel
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getAllHotels($id=null, $name=null){

        $query = 'select * from hotels';
        $types = '';
        $params = [];

        if($id){
            $query .= ' where hotel_id=?';
            $types .= 'i';
            $params[] = $id;
        }
        if($name){
            $query .= ($id) ? ' and hotel_name=?': ' where hotel_name=?';
            $types .= 's';
            $params[] = $name;
        }
        $stmt = $this->mysqli->prepare($query);
        if(!empty($params)){
            $stmt->bind_param($types, ...$params);
        }
        
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

    public function addHotel($name, $location, $rooms){

        $query = 'insert into hotels (hotel_name, location, available_rooms) values (?, ?, ?)';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('ssi', $name, $location, $rooms);
        return $stmt->execute();
    }

    public function deleteHotelById($id)
    {
        $query = 'DELETE FROM hotels WHERE hotel_id = ?';
        $stmt = $this->mysqli->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error);
        }
    
        $stmt->bind_param('i', $id);
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            return ['message' => 'Hotel deleted successfully', 'rowCount' => $stmt->affected_rows];
        } else {
            return ['message' => 'Hotel not found'];
        }
    }
    
}