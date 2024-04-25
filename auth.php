<?php
session_set_cookie_params(1800);
session_start();

if (!defined('AUTH_INCLUDED')) {
    define('AUTH_INCLUDED', true);
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        header("Location: index.php");
        exit();
    }
}
?>