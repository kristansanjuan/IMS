<?php
session_set_cookie_params(1800);
session_start();

// Check if the authentication script is not included yet
if (!defined('AUTH_INCLUDED')) {
    define('AUTH_INCLUDED', true); // Define a constant to indicate inclusion
    // Check if the user is not logged in, then redirect to the admin login page
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        header("Location: admin.php");
        exit(); // Stop further execution
    }
}
?>