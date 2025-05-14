<?php
header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/User.php');

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Instantiate user
$user = new User($db);

// Check if ID is set
$user->id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["message" => "User ID is required."]));

// Read user by ID
$stmt = $user->read();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user was found
if ($row) {
    $user_data = array(
        "id" => $row['id'],
        "name" => $row['name'],
        "email" => $row['email']
    );
    echo json_encode($user_data);
} else {
    echo json_encode(["message" => "User not found."]);
}
