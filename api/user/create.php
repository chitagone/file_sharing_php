<?php
session_start(); // Start the session

header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/User.php');

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {
    $user->name = $data->name;
    $user->email = $data->email;
    $user->password = $data->password;

    // Attempt to create the user
    $result = $user->create();

    if ($result) {
        // ✅ Set session variables upon successful user creation
        $_SESSION['auth_token'] = $result["token"]; // Store auth token
        $_SESSION['user'] = $result["user"]; // Store user details (name, email, etc.)
        $_SESSION['user_id'] = $result["user"]['id']; // Store user ID for future requests

        echo json_encode([
            "message" => "User created successfully.",
            "token" => $result["token"], // Send back the token
            "user" => $result["user"] // Send back the user details
        ]);
    } else {
        echo json_encode(["message" => "Unable to create user."]);
    }
} else {
    echo json_encode(["message" => "Incomplete data."]);
}
?>
