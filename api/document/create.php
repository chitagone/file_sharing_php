<?php
header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/Document.php');

$database = new Database();
$db = $database->getConnection();

$document = new Document($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->user_id) && !empty($data->title) && !empty($data->file_name) && !empty($data->file_path)) {
    $document->user_id = $data->user_id;
    $document->title = $data->title;
    $document->file_name = $data->file_name;
    $document->file_path = $data->file_path;
    $document->file_type = isset($data->file_type) ? $data->file_type : '';
    $document->file_size = isset($data->file_size) ? $data->file_size : 0;
    $document->category_id = isset($data->category_id) ? $data->category_id : null;
    $document->is_public = isset($data->is_public) ? $data->is_public : false;

    if ($document->create()) {
        echo json_encode(["message" => "Document created successfully."]);
    } else {
        echo json_encode(["message" => "Unable to create document."]);
    }
} else {
    echo json_encode(["message" => "Incomplete data."]);
}
?>
