<?php
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

    $result = $user->create();

    if ($result) {
        echo json_encode([
            "message" => "User created successfully.",
            "token" => $result["token"] // Send back the token
        ]);
    } else {
        echo json_encode(["message" => "Unable to create user."]);
    }
} else {
    echo json_encode(["message" => "Incomplete data."]);
}
?>
