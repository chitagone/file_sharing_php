<?php
header("Content-Type: application/json");

include_once "../../config/database.php";

$db = (new Database())->getConnection();
$stmt = $db->prepare("SELECT * FROM document_categories");
$stmt->execute();

$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($cats);
