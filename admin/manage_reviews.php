<?php
session_start();
include 'header.php';
include 'db.php';

// Check if user is logged in as admin (you can adjust this based on your authentication mechanism)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch all reviews
$sql_reviews = "SELECT * FROM reviews ORDER BY created_at DESC";
$result_reviews = $conn->query($sql_reviews);

// Handle delete review action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_review'])) {
    $review_id = $_POST['review_id'];
    
    // Use prepared statement to delete review
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    if ($stmt->execute()) {
        // Successful deletion
        echo '<script>alert("Review deleted successfully.");</script>';
        // Redirect to refresh the page after deletion
        echo '<script>window.location.href = "manage_reviews.php";</script>';
    } else {
        // Error in deletion
        echo '<script>alert("Failed to delete review.");</script>';
    }
    $stmt->close();
}
?>
<style>
    /* Additional styles specific to manage_reviews.php */

    .review-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f9f9f9; /* Light grey background */
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .review {
        border: 1px solid #e0e0e0; /* Light grey border */
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
        background-color: #ffffff; /* White background */
        transition: box-shadow 0.3s ease;
    }

    .review:hover {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Shadow on hover */
    }

    .review h3 {
        color: #333; /* Dark text color */
        margin-bottom: 5px;
    }

    .review p {
        color: #666; /* Medium grey text color */
        margin-bottom: 10px;
    }

    .review .btn {
        background-color: #FF5722; /* Red button for delete */
        color: #fff; /* White text */
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .review .btn:hover {
        background-color: #F44336; /* Darker red on hover */
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

<div class="review-container">
    <h2 style="text-align: center; margin-bottom: 20px;">Manage Product Reviews</h2>

    <?php
    if ($result_reviews->num_rows > 0) {
        while ($row_review = $result_reviews->fetch_assoc()) {
            ?>
            <div class="review">
                <h3><?php echo htmlspecialchars($row_review['customer_name']); ?></h3>
                <p><strong>Product ID:</strong> <?php echo htmlspecialchars($row_review['product_id']); ?></p>
                <p><strong>Rating:</strong> <?php echo htmlspecialchars($row_review['rating']); ?></p>
                <p><strong>Review:</strong> <?php echo htmlspecialchars($row_review['review']); ?></p>
                <p><strong>Submitted On:</strong> <?php echo htmlspecialchars($row_review['created_at']); ?></p>
                
                <!-- Delete Review Button -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="review_id" value="<?php echo $row_review['id']; ?>">
                    <button type="submit" name="delete_review" class="btn btn-danger">Delete</button>
                </form>
            </div>
            <?php
        }
    } else {
        echo '<p>No reviews found.</p>';
    }
    ?>
</div>

<?php include 'footer.php'; ?>
