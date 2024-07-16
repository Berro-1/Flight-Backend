<?php

Class Airport
{
    private $mysqli;

    public function __construct($mysqli){
        $this->mysqli = $mysqli;
    }
    
    public function getAirportIdByName($name){
        $query = 'select Airport_id from airports where AirportName=?';
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error);
        }
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
        
    }
}
