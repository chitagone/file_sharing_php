<?php
header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/Document.php');

$database = new Database();
$db = $database->getConnection();

$document = new Document($db);
$document->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($document->delete()) {
    echo json_encode(["message" => "Document deleted successfully."]);
} else {
    echo json_encode(["message" => "Unable to delete document."]);
}
?>
