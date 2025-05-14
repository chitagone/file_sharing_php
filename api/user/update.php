<?php
header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/User.php');

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->name) && !empty($data->email)) {
    $user->id = $data->id;
    $user->name = $data->name;
    $user->email = $data->email;

    if ($user->update()) {
        echo json_encode(["message" => "User updated successfully."]);
    } else {
        echo json_encode(["message" => "Unable to update user."]);
    }
} else {
    echo json_encode(["message" => "Incomplete data."]);
}
?>
