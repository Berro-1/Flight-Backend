<?php

require_once '../../config/config.php';
require_once '../../models/user.php';

$usreModul = new User($mysqli);

if($_SERVER['REQUEST_METHOD' == 'POST']){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $response = $usreModul->login($email, $pass, $username);

    echo json_encode($response);
}else{
    echo json_encode('wrong method');
}