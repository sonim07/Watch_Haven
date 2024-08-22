<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header('Location: admin_login.php');
    exit;
}

// Path to the log file
$logFile = '../log.txt'; // Ensure this path is correct

// Clear the log file if it exists
if (file_exists($logFile)) {
    file_put_contents($logFile, ''); // Clear the contents
    $_SESSION['message'] = 'Logs have been cleared successfully.';
} else {
    $_SESSION['message'] = 'Log file does not exist.';
}

// Redirect back to the admin dashboard
header('Location: admin_dashboard.php');
exit;
