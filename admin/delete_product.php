<?php
session_start();
include 'header.php'; // Include header navigation
include 'db.php'; // Include database connection

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<style>
    /* Styles for delete_products.php */
.delete-products {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
}

.delete-products h1 {
    text-align: center;
}

.product-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.product-item {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
}

.product-item img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
}

.product-item h3 {
    margin-top: 10px;
    font-size: 18px;
}

.product-item p {
    margin: 10px 0;
    font-size: 14px;
}

.btn-delete {
    background-color: #ff6347;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-delete:hover {
    background-color: #ff4500;
}
@media (max-width: 768px) {
            th, td {
                padding: 10px;
                font-size: 14px;
            }
            a {
                width: 100%;
                padding: 15px 20px;
            }
        }
        @media (max-width: 480px) {
            th, td {
                padding: 8px;
                font-size: 12px;
            }
            a {
                width: 100%;
                padding: 12px 15px;
            }
        }


</style>
<main class="delete-products">
    <h1>Delete Products</h1>
    <?php
    // Display session messages if any
    if (isset($_SESSION['message'])) {
        echo '<div class="alert">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>
    <div class="product-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-item">';
                echo '<img src="' . htmlspecialchars($row["product_image"]) . '" alt="' . htmlspecialchars($row["product_name"]) . '">';
                echo '<h3>' . htmlspecialchars($row["product_name"]) . '</h3>';
                echo '<p>Description: ' . htmlspecialchars($row["product_description"]) . '</p>';
                echo '<p>Price: $' . htmlspecialchars($row["product_price"]) . '</p>';
                echo '<form method="post" action="delete_product_handler.php">';
                echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row["id"]) . '">';
                echo '<button type="submit" class="btn-delete">Delete</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo '<p>No products found.</p>';
        }
        ?>
    </div>
</main>

<?php include 'footer.php'; // Include footer ?>
