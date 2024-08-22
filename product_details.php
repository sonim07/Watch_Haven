<?php
session_start();
include 'header.php';
include 'db.php';
include 'logger.php';
// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

// Fetch product details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Use prepared statement to fetch product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid product ID.";
    exit;
}

// Handle form submission for adding reviews
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $name = sanitize($_POST['name']);
    $rating = intval($_POST['rating']);
    $comment = sanitize($_POST['comment']);

    // Insert review into database
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, customer_name, rating, review) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $id, $name, $rating, $comment);
    if ($stmt->execute()) {
        // Redirect after successful submission
        header("Location: product_details.php?id=$id");
        exit(); // Ensure that script stops executing after redirect
    } else {
        echo '<script>alert("Failed to submit review.");</script>'; // JavaScript popup
    }
    $stmt->close();
}

// Fetch reviews for this product
$sql_reviews = "SELECT * FROM reviews WHERE product_id = $id ORDER BY created_at DESC";
$result_reviews = $conn->query($sql_reviews);
?>
<style>
    /* Additional styles specific to product_details.php */
    .product-details-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 20px;
        background-color: #f0f0f0; /* Light grey background */
    }

    .product-details {
        max-width: 600px;
        padding: 20px;
        background-color: #fff; /* White background */
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
        margin-right: 20px;
    }

    .product-details img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .product-details h1 {
        color: #333; /* Dark text color */
        margin-bottom: 10px;
    }

    .product-details p.price {
        font-size: 1.2rem;
        color: #4CAF50; /* Green for price */
        margin-bottom: 20px;
    }

    .product-details p {
        color: #666; /* Medium grey text color */
        line-height: 1.6;
    }

    .product-details form {
        margin-top: 20px;
    }

    .product-details .btn {
        background-color: #FF9800; /* Orange button */
        color: #fff; /* White text */
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .product-details .btn:hover {
        background-color: #F57C00; /* Darker orange on hover */
    }

    .review-section {
        max-width: 600px;
        background-color: #fff; /* White background */
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 20px;
        padding-right: 40px;
        margin-top: 20px;
    }

    .review-section h2 {
        color: #333; /* Dark text color */
        margin-bottom: 20px;
    }

    .review-section form {
        margin-bottom: 20px;
    }

    .review-section label {
        display: block;
        margin-bottom: 5px;
        color: #333; /* Dark text color */
    }

    .review-section input[type="text"],
    .review-section select,
    .review-section textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc; /* Light grey border */
        border-radius: 4px;
        font-size: 1rem;
    }

    .review-section textarea {
        resize: vertical;
    }

    .review-section .btn {
        background-color: #FF9800;
        color: #fff; /* White text */
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .review-section .btn:hover {
        background-color: #F57C00;
    }

    .reviews {
        margin-top: 20px;
    }

    .review {
        background-color: #f9f9f9; /* Light grey background */
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .review h3 {
        color: #333; /* Dark text color */
        margin-bottom: 5px;
    }

    .review p {
        color: #666; /* Medium grey text color */
        margin-bottom: 5px;
    }

    /* Star rating styles */
    .rating {
        display: flex;
        align-items: center;
    }

    .rating input[type="radio"] {
        display: none;
    }

    .rating label {
        font-size: 24px;
        color: #ddd; /* Light grey star color */
        cursor: pointer;
        transition: color 0.3s; /* Smooth transition for color change */
        margin-right: 5px; /* Adjust spacing between stars */
    }

    .rating label:hover,
    .rating input[type="radio"]:hover ~ label {
        color: #FFD700; /* Gold star color on hover */
    }

    .rating input[type="radio"]:checked ~ label {
        color: #FFD700; /* Gold star color when checked */
    }

    .fa-star {
        font-size: 24px;
        color: #ddd; /* Light grey star color */
        transition: color 0.3s; /* Smooth transition for color change */
    }

    .fa-star.checked {
        color: #FFD700; /* Gold star color */
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

<div class="product-details-container">
    <div class="product-details">
        <img src="<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
        <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
        <p class="price">$<?php echo htmlspecialchars($product['product_price']); ?></p>
        <p><?php echo htmlspecialchars($product['product_description']); ?></p>
        <form method="post" action="add_to_cart.php">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
        </form>
    </div>

    <div class="review-section">
        <h2>Product Reviews</h2>

        <!-- Form for submitting a review -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="rating">Your Rating:</label>
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="5" required><label for="star5"><i class="fa fa-star"></i></label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4"><i class="fa fa-star"></i></label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3"><i class="fa fa-star"></i></label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2"><i class="fa fa-star"></i></label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1"><i class="fa fa-star"></i></label>
            </div>
            
            <label for="comment">Your Review:</label>
            <textarea id="comment" name="comment" rows="4" required></textarea>
            
            <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
        </form>

        <!-- Display existing reviews -->
        <div class="reviews">
            <?php
            if ($result_reviews->num_rows > 0) {
                while ($row_review = $result_reviews->fetch_assoc()) {
                    echo '<div class="review">';
                    echo '<h3>' . htmlspecialchars($row_review['customer_name']) . '</h3>';
                    echo '<div class="rating">';
                    $rating = intval($row_review['rating']);
                    for ($i = 1; $i <= 5; $i++) {
                        $checked = ($i <= $rating) ? 'checked' : '';
                        echo '<span class="fa fa-star ' . $checked . '"></span>';
                    }
                    echo '</div>';
                    echo '<p>' . htmlspecialchars($row_review['review']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No reviews yet. Be the first to review!</p>';
            }
            ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
