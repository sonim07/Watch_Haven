<?php
session_start();

include 'header.php';
include 'logger.php';
?>

<style>
    .checkout-container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .collection h2 {
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }

    .collection table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .collection th,
    .collection td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .collection th {
        background-color: #f4f4f4;
        color: #333;
    }

    .collection tr:hover {
        background-color: #f9f9f9;
    }

    .checkout-form {
        margin-top: 20px;
    }

    .checkout-form .form-group {
        margin-bottom: 15px;
    }

    .checkout-form label {
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    .checkout-form input[type="text"],
    .checkout-form input[type="email"],
    .checkout-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .checkout-form input[type="submit"] {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .checkout-form input[type="submit"]:hover {
        background-color: #218838;
    }

    /* Payment Method Styles */
    .payment-method {
        margin-top: 20px;
        text-align: center;
    }

    .payment-method img {
        max-width: 100px;
        display: block;
        margin: 0 auto;
    }

    .payment-method label {
        display: block;
        margin-top: 10px;
        font-size: 1.1em;
        color: #333;
    }

    /* Footer Styles */
    footer {
        background-color: #222;
        color: #f9f9f9;
        text-align: center;
        padding: 20px 0;
        margin-top: 50px;
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

<main class="checkout-container">
    <div class="collection">
        <?php
        if (!isset($_SESSION["cart_item"]) || empty($_SESSION["cart_item"])) {
            echo '<h2>Your Cart is Empty</h2>';
        } else {
            echo '<h2>Checkout</h2>';
            echo '<table>';
            echo '<thead><tr><th>Name</th><th>Price</th><th>Quantity</th></tr></thead>';
            echo '<tbody>';
            $total_price = 0;

            foreach ($_SESSION["cart_item"] as $item) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($item["name"]) . '</td>';
                echo '<td>$' . htmlspecialchars($item["price"]) . '</td>';
                echo '<td>' . htmlspecialchars($item["quantity"]) . '</td>';
                echo '</tr>';
                $total_price += $item["price"] * $item["quantity"];
            }

            echo '</tbody>';
            echo '</table>';
            echo '<h3>Total Price: $' . number_format($total_price, 2) . '</h3>';
        }
        ?>
    </div>

    <div class="checkout-form">
        <h3>Shipping Details</h3>
        <form action="place_order.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="4" required></textarea>
            </div>

            <div class="payment-method">
                <h3>Payment Method</h3>
                <img src="images/cod_image.jpg" alt="Cash on Delivery">
                <label for="payment_method">
                    <input type="radio" id="payment_method" name="payment_method" value="COD" checked>
                    Cash on Delivery
                </label>
            </div>

            <input type="submit" value="Place Order">
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
