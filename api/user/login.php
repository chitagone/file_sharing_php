<?php
// Start session
session_start();

// Set content type for JSON response
header("Content-Type: application/json");

// Include necessary files
include_once('../../config/database.php');
include_once('../../model/User.php');

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Instantiate User object
$user = new User($db);

// Get data from the request body
$data = json_decode(file_get_contents("php://input"));

// Check if email and password are provided
if (!empty($data->email) && !empty($data->password)) {
    // Assign email and password to user object
    $user->email = $data->email;
    $user->password = $data->password;

    // Attempt to log in the user
    $login_response = $user->login();

    if ($login_response['message'] == "Login successful.") {
        // Set session variables upon successful login
        $_SESSION['auth_token'] = $login_response['token']; // Store auth token
        $_SESSION['user'] = $login_response['user']; // Store user details
        $_SESSION['user_id'] = $login_response['user']['id']; // Store user ID for future requests

        // Send success response with token and user data
        echo json_encode([
            "message" => $login_response['message'],
            "token" => $login_response['token'],
            "user" => $login_response['user']
        ]);
    } else {
        // Send failure response with error message
        echo json_encode([
            "message" => $login_response['message']
        ]);
    }
} else {
    // Send failure response if email or password is missing
    echo json_encode([
        "message" => "Incomplete data. Please provide email and password."
    ]);
}
