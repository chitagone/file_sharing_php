
<?php
// Include the session start and middleware
session_start();
require_once __DIR__ . '/../middleware/Authenticate.php';

// If you want to check if the user is authenticated
Authenticate::checkAuth(); // This will redirect to login if not authenticated

// Login logic below...
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    test
</body>
</html>