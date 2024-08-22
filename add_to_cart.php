<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    // Ensure that the user is logged in and the username is available in the session
    if (!isset($_SESSION['username'])) {
        $_SESSION["cart_error"] = "You must be logged in to add items to the cart.";
        header('Location: login.php'); // Redirect to login page
        exit;
    }

    $username = $_SESSION['username'];

    // Fetch user_id from the username
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
    } else {
        $_SESSION["cart_error"] = "User not found.";
        header('Location: login.php');
        exit;
    }

    $productId = $_POST['product_id'];

    // Check if the cart has reached the maximum limit of 20 distinct products
    $stmt = $conn->prepare("SELECT COUNT(*) as product_count FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['product_count'] >= 20) {
        $_SESSION["cart_error"] = "You cannot add more than 20 different products to the cart.";
        header('Location: index.php');
        exit;
    }

    // Use prepared statement to fetch product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $productPrice = $product["product_price"];

        // Check if the product is already in the cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $cartItem = $stmt->get_result()->fetch_assoc();

        if ($cartItem) {
            if ($cartItem['quantity'] >= 20) {
                $_SESSION["cart_error"] = "You cannot add more than 20 units of this product to the cart.";
            } else {
                // Update the quantity of the existing item
                $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("ii", $userId, $productId);
                $stmt->execute();
            }
        } else {
            // Insert the new item into the cart
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $quantity = 1;
            $stmt->bind_param("iiid", $userId, $productId, $quantity, $productPrice);
            $stmt->execute();
        }
    } else {
        $_SESSION["cart_error"] = "Product not found.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to index.php after adding to cart
    header('Location: index.php');
    exit;
}
?>
