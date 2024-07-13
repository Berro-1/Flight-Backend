<?php
// Include configuration and model files
require_once '../../config/config.php';  
require_once '../../models/user.php';

$data = json_decode(file_get_contents("php://input"));


$userModel = new User($mysqli);

$response = $userModel->read();

echo json_encode($response);
