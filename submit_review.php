<?php
session_start();
include 'db.php';
include 'logger.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $customer_name = $_POST['customer_name'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $sql = "INSERT INTO reviews (product_id, customer_name, review, rating) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $product_id, $customer_name, $review, $rating);

    if ($stmt->execute()) {
        echo "Review submitted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the product details page
    header("Location: product_details.php?id=" . $product_id);
    exit();
}
?>
