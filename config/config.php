<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'chris_airlines_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDBConnection()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }
    
    //echo "MySQLi connection successfully established.<br>";
    
    return $conn;
}

$mysqli = getDBConnection();

