<?php include 'header.php'; ?>
<style>
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
<div class="review-section">
    <h2>Leave a Review</h2>
    <form action="submit_review.php" method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>"> <!-- Replace with dynamic product ID -->
        <label for="customer_name">Name:</label>
        <input type="text" id="customer_name" name="customer_name" required>
        <label for="rating">Rating:</label>
        <select id="rating" name="rating" required>
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Good</option>
            <option value="3">3 - Average</option>
            <option value="2">2 - Poor</option>
            <option value="1">1 - Terrible</option>
        </select>
        <label for="review">Review:</label>
        <textarea id="review" name="review" required></textarea>
        <input type="submit" value="Submit Review">
    </form>
</div>

<?php include 'footer.php'; ?>
