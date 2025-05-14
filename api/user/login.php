<?php
session_start(); // 👈 Add this line at the top

header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/User.php');

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $user->email = $data->email;
    $user->password = $data->password;

    // Attempt to login the user
    $login_response = $user->login();

    if ($login_response['message'] == "Login successful.") {
        // ✅ Set session after successful login
        $_SESSION['auth_token'] = $login_response['token'];
        $_SESSION['user'] = $login_response['user'];

        echo json_encode([
            "message" => $login_response['message'],
            "token" => $login_response['token'],
            "user" => $login_response['user']
        ]);
    } else {
        echo json_encode([
            "message" => $login_response['message']
        ]);
    }
} else {
    echo json_encode([
        "message" => "Incomplete data. Please provide email and password."
    ]);
}
