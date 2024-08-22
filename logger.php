<?php
// logger.php

// Function to log user activities
function logActivity($message) {
    // Path to the log file
    $logFile = 'log.txt';

    // Prepare log entry with timestamp and user info
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'userAgent' => $_SERVER['HTTP_USER_AGENT'],
        'username' => isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest',
        'activity' => $message,
        'uri' => $_SERVER['REQUEST_URI']
    ];

    // Read existing log entries
    $logEntries = [];
    if (file_exists($logFile)) {
        $jsonContent = file_get_contents($logFile);
        $logEntries = json_decode($jsonContent, true) ?: [];
    }

    // Add new log entry
    $logEntries[] = $logEntry;

    // Write updated log entries to file
    file_put_contents($logFile, json_encode($logEntries, JSON_PRETTY_PRINT));
}

// Example of logging page access
logActivity('Visited page');
?>