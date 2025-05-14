<?php
header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/User.php');

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$user->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($user->delete()) {
    echo json_encode(["message" => "User deleted successfully."]);
} else {
    echo json_encode(["message" => "Unable to delete user."]);
}
?>
