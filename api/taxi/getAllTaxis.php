<?php

require_once '../../config/config.php';
require_once '../../models/taxi.php';

$taxiModul = new Taxi($mysqli);

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    $response = $taxiModul->getAllTaxis();
    echo json_encode($response);

}else{
    echo json_encode(['error' => 'wrong request method']);
}