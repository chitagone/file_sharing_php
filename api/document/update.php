<?php
header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/Document.php');

$database = new Database();
$db = $database->getConnection();

$document = new Document($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $document->id = $data->id;
    $document->title = isset($data->title) ? $data->title : $document->title;
    $document->file_name = isset($data->file_name) ? $data->file_name : $document->file_name;
    $document->file_path = isset($data->file_path) ? $data->file_path : $document->file_path;
    $document->file_type = isset($data->file_type) ? $data->file_type : $document->file_type;
    $document->file_size = isset($data->file_size) ? $data->file_size : $document->file_size;
    $document->category_id = isset($data->category_id) ? $data->category_id : $document->category_id;
    $document->is_public = isset($data->is_public) ? $data->is_public : $document->is_public;

    if ($document->update()) {
        echo json_encode(["message" => "Document updated successfully."]);
    } else {
        echo json_encode(["message" => "Unable to update document."]);
    }
} else {
    echo json_encode(["message" => "Document ID is required."]);
}
?>
