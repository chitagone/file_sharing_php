<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../middleware/Authenticate.php';

Authenticate::checkAuth();

echo json_encode(["message" => "You are authenticated."]);
