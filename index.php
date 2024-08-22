<?php
session_start();
include 'header.php';
include 'db.php';
include 'logger.php';

// Display error message if set
if (isset($_SESSION["cart_error"])) {
    echo '<script>alert("' . $_SESSION["cart_error"] . '");</script>';
    unset($_SESSION["cart_error"]);
}
?>

<div class="hero-section">
    <h1>Welcome to Watch Haven</h1>
    <p>Explore our exclusive collection of premium watches</p>
    <a href="#collection" class="btn">Shop Now</a>
</div>

<div id="collection" class="collection">
    <h2>Our Collection</h2>
    <?php
    // Fetch watches from the database
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="watch-card">';
            echo '<a href="product_details.php?id=' . htmlspecialchars($row["id"]) . '">';
            echo '<img src="' . htmlspecialchars($row["product_image"]) . '" alt="' . htmlspecialchars($row["product_name"]) . '">';
            echo '<h3>' . htmlspecialchars($row["product_name"]) . '</h3>';
            echo '</a>';
            echo '<p>$' . htmlspecialchars($row["product_price"]) . '</p>';
            echo '<form method="post" action="add_to_cart.php">';
            echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row["id"]) . '">';
            echo '<button type="submit" name="add_to_cart" class="btn">Add to Cart</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo "0 results";
    }

    // Close database connection
    $conn->close();
    ?>
</div>

<?php include 'footer.php'; ?>
