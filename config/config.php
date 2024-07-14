
<?php
function getDBConnection() {
    $host = 'localhost';
    $username = 'root';
    $password = 'root'; // Replace with your actual password
    $dbname = 'chris_airlines_db'; // Replace with your actual database name

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    return $conn;
}
?>
