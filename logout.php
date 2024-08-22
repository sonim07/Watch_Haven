<?php
// Initialize the session
session_start();
include 'logger.php';
// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("location: login.php");
exit;
?>
