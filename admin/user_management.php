<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'header.php'; // Include header navigation

// Retrieve user accounts from database and display in a table
?>

<main class="admin-content">
    <h2>Manage Users</h2>
    <table>
        <!-- Table headers and rows for displaying users -->
    </table>
</main>

<?php include 'footer.php'; ?>
