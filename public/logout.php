<!-- public/logout-action.php -->
<?php
session_start();
require_once __DIR__ . '/../middleware/Authenticate.php';

Authenticate::logout(); // Logs the user out and redirects to login
?>