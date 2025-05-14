<?php
header("Content-Type: application/json");
include_once('../../config/database.php');
include_once('../../model/Document.php');

$database = new Database();
$db = $database->getConnection();

$document = new Document($db);
$document->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $document->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $document_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $document_item = array(
            "id" => $id,
            "user_id" => $user_id,
            "title" => $title,
            "file_name" => $file_name,
            "file_path" => $file_path,
            "file_type" => $file_type,
            "file_size" => $file_size,
            "category_id" => $category_id,
            "is_public" => $is_public
        );
        array_push($document_arr, $document_item);
    }
    echo json_encode($document_arr);
} else {
    echo json_encode(["message" => "Document not found."]);
}
?>
