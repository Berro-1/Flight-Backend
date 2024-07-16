<?php
header('Access-Control-Allow-Origin: *'); // Allows requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allows specific methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); //
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
    
<<<<<<< HEAD
    //echo "MySQLi connection successfully established.<br>";
=======
    // echo "MySQLi connection successfully established.<br>";
>>>>>>> 51196cecc0deb78ddd3707a6c0825f1f16ba375b
    
    return $conn;
}

$mysqli = getDBConnection();

