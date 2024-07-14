
<?php
function getDBConnection() {
    $host = 'localhost';
    $username = 'root';
    $password = 'root'; // Replace with your actual password
    //just to ntice what happened...
    /// i just have added my pass here 
    // and i have wrote the in the same functionaly but in another way. 

    $dbname = 'chris_airlines_db'; // Replace with your actual database name

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    return $conn;
}
?>
