<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/config.php';
require_once '../../vendor/autoload.php';
require_once '../../models/user.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents("php://input"));

$userModel = new User($mysqli);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($data->email) || !isset($data->password)) {
        http_response_code(400);
        echo json_encode(["message" => "Email and password are required"]);
        exit();
    }
    $response = $userModel->login($data->email, $data->password);
    echo json_encode($response);
    exit();
}

// Function to authenticate JWT
function authenticate()
{
    $headers = getallheaders();
    $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

    if (!$jwt) {
        http_response_code(401);
        echo json_encode(["message" => "Access denied. No token provided."]);
        exit();
    }

    try {
        $decoded = JWT::decode($jwt, new Key('your_secret_key', 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Access denied. " . $e->getMessage()]);
        exit();
    }
}

$decoded=authenticate();
echo json_encode($response);