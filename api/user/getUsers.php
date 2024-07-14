<?php
require '../../config/config.php'; // Path to database connection
require '../../models/User.php';   // Path to user model

// Initialize the database connection
$mysqli = getDBConnection();

// Create an instance of the User model
$userModel = new User($mysqli);

// Determine the request method
$method = $_SERVER['REQUEST_METHOD'];

// Log the request method
error_log("Request method: " . $method);

if ($method == 'GET') {
    // Get the user_id from query parameters
    $user_id = $_GET['user_id'] ?? null;
    $action = $_GET['action'] ?? null;

    if ($user_id) {
        $result = $userModel->getUserById($user_id);
    } elseif ($action == 'search') {
        $term = $_GET['term'] ?? '';
        $result = $userModel->searchUsers($term);
    } elseif ($action == 'getAll') {
        $result = $userModel->getAllUsers();
    } else {
        $result = ["error" => "Invalid action or user_id is required"];
    }

    echo json_encode($result);

} elseif ($method == 'POST') {
    // Log all POST data
    error_log("POST data: " . print_r($_POST, true));
    
    // Get the action from POST data
    $action = $_POST['action'] ?? null;

    // Log the action value
    error_log("Action received: " . $action);

    if ($action == 'create') {
        $username = $_POST['username'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($username && $email && $password) {
            $result = $userModel->createUser($username, $email, $password);
        } else {
            $result = ["error" => "Missing parameters for creating a user"];
        }
    
    } elseif ($action == 'update') {
        $user_id = $_POST['user_id'] ?? null;
        $username = $_POST['username'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($user_id && $username && $email) {
            $result = $userModel->updateUser($user_id, $username, $email, $password);
        } else {
            $result = ["error" => "Missing parameters for updating a user"];
        }

    } elseif ($action == 'login') {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($email && $password) {
            $result = $userModel->loginUser($email, $password);
        } else {
            $result = ["error" => "Email and password are required"];
        }

    } elseif ($action == 'logout') {
        $result = $userModel->logoutUser();

    } else {
        $result = ["error" => "Invalid action"];
    }

    echo json_encode($result);

} else {
    echo json_encode(["error" => "Unsupported request method"]);
}
?>


<?php 
require '../.../config/config.php';
require '../../models/User.php';

//initlize the database connction 
$mysqli = getDBConnection();

$userModel = new User($mysqli);

$method = $_SERVER['REQUEST_METHOD'];


error-log("Request method : " .$method );

if  ($method == 'Get'){
    $user_id=$_GET['user_id']??null ; 
    $action = $_Get['action']?? null ; 

    if ($user_id){
        $result = $userModel->getUserById($user_id);

    }elseif($action == 'search'){
        $term = $_Get['term'] ?? '' ;
        $result = $userModel -> searchUsers($term);
    }elseif($action == 'getAll'){
        $result=$userModel->getAllUsers($term);
    }else { 
        $result = ["error"=> "Invalid action or use_id is required"];
    }
    exho json_encode($result);

if ( $method == 'Get'){
     $user_id = 
}elseif($action =='search'){
    $rsult =
}