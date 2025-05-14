<?php
class Authenticate {
    public static function checkAuth() {
        session_start(); // Make sure session is started

        // Allow access to login and register pages
        $allowedPages = ['login.php', 'register.php'];
        $currentPage = basename($_SERVER['PHP_SELF']);

        // If the current page is not login or register, and the user is not logged in, redirect to login page
        if (!isset($_SESSION['auth_token']) && !in_array($currentPage, $allowedPages)) {
            header('Location: /document_api/public/login.php');
            exit();
        }
    }

    public static function logout() {
        session_start(); // Start the session if not already started

        // Destroy all session variables
        session_unset();

        // Destroy the session itself
        session_destroy();

        // Redirect to the login page
        header('Location: /document_api/public/login.php');
        exit();
    }
}
?>
