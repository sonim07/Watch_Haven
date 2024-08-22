<?php
session_start();

include 'db.php'; // Include database connection
include '../logger.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    // Prepare an insert statement
    $sql = "INSERT INTO products (product_name, product_description, product_price, product_image) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssds", $product_name, $product_description, $product_price, $product_image);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to admin dashboard after successful product addition
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <?php include 'header.php'; ?>
<style>
    /* Reset styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Global styles */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    background-color: #f0f0f0;
}

.container {
    width: 80%;
    margin: 0 auto;
}

.admin-main {
    padding: 20px;
}

.admin-section {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.admin-section h2 {
    font-size: 24px;
    margin-bottom: 20px;
}

.admin-form {
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
input[type="url"],
textarea {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

textarea {
    resize: vertical;
}

.btn {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.btn:hover {
    background-color: #45a049;
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
    <main class="admin-main">
        <section class="admin-section">
            <h2>Add Product</h2>
            <div class="admin-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" name="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="product_description">Product Description:</label>
                        <textarea id="product_description" name="product_description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="product_price">Product Price:</label>
                        <input type="number" id="product_price" name="product_price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="product_image">Product Image URL:</label>
                        <input type="url" id="product_image" name="product_image" required>
                    </div>
                    <button type="submit" class="btn">Add Product</button>
                </form>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
