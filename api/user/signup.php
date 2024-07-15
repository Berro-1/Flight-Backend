<?php

require_once '../../models/user.php';
require_once '../../config/config.php';

$userModul = new User($mysqli);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $response = $userModul->create($username, $email, $pass);

    echo json_encode($response);
}  else {
    echo json_encode(['error' => 'wrong method']);
}