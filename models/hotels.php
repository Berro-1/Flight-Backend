<?php

class Hotel
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
        if (!$stmt) {
            throw new Exception("Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error);
        } 

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

    public function addHotel($name, $location, $rooms) {
        $query = 'INSERT INTO hotels (hotel_name, location, available_rooms) VALUES (?, ?, ?)';
        $stmt = $this->mysqli->prepare($query);

    
        if ($stmt === false) {
            return ['error' => 'Failed to prepare statement'];
        }
    
        $stmt->bind_param('ssi', $name, $location, $rooms);
        
        if ($stmt->execute()) {
            return ['message' => 'Hotel added successfully'];
        } else {
            return ['error' => 'Failed to add hotel: ' . $stmt->error];
        }
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

    public function updateHotel($id, $name, $location, $rooms)
{
    $query = 'UPDATE hotels SET hotel_name = ?, location = ?, available_rooms = ? WHERE hotel_id = ?';


    $stmt = $this->mysqli->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $this->mysqli->error);
    }
    
    $stmt->bind_param('ssii', $name, $location, $rooms, $id);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed:". $stmt->error);
    }
    
    $stmt->close();
    
    return true; 
}
public function searchHotels($searchTerm) {
    $query = 'SELECT * FROM hotels WHERE hotel_name LIKE ? OR location LIKE ?';

    $stmt = $this->mysqli->prepare($query);
 
    $searchTerm = '%' . $searchTerm . '%';
  
    $stmt->bind_param('ss', $searchTerm, $searchTerm);

    $stmt->execute();

    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}


}