<!-- <?php include 'header.php'; ?>
<?php include 'db.php'; ?>
<?php include 'logger.php'; ?>
<div class="product-section">
    <?php
    $id = $_GET['id'];
    $sql = "SELECT * FROM watches WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<img src="' . $row["image"] . '" alt="' . $row["name"] . '">';
            echo '<h1>' . $row["name"] . '</h1>';
            echo '<p>$' . $row["price"] . '</p>';
            echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam non urna nec nulla laoreet ultricies.</p>';
            echo '<a href="#" class="btn">Buy Now</a>';
        }
    } else {
        echo "Product not found.";
    }
    ?>
</div>

<?php include 'footer.php'; ?> -->
