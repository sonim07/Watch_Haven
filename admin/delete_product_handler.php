<?php
session_start();
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        // Set a success message in the session
        $_SESSION['message'] = "Product deleted successfully.";
    } else {
        // Set an error message in the session
        $_SESSION['message'] = "Failed to delete product.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to delete_products.php
    header('Location: delete_product.php');
    exit();
}
?>
