<?php
session_start();
include 'db.php';

// Ensure that the user is logged in and the username is available in the session
if (!isset($_SESSION['username'])) {
    $_SESSION["cart_error"] = "You must be logged in to view your cart.";
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

// Handle update and remove actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $productId = $_POST['id'];
    if ($_POST['action'] == "update" && isset($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
        if ($quantity <= 0) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $userId, $productId);
            $stmt->execute();
        }
    } elseif ($_POST['action'] == "remove") {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
    }
    header('Location: cart.php');
    exit;
}

// Fetch cart items for the user
$stmt = $conn->prepare("SELECT cart.product_id, cart.quantity, cart.price, products.product_name, products.product_image 
                        FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result();

include 'header.php';
?>

<style>
/* style.css */

/* Cart Container */
.cart-container {
    max-width: 1200px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

/* style.css */

.collection {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.collection h2 {
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

.collection table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.collection th,
.collection td {
    padding: 12px;
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

.collection .remove-btn {
    display: inline-block;
    padding: 8px 16px;
    background-color: #dc3545;
    color: #fff;
    border: none;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.collection .remove-btn:hover {
    background-color: #c82333;
}

.checkout-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    transition: background-color 0.3s;
    text-align: center;
}

.checkout-btn:hover {
    background-color: #218838;
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
<main class="cart-container">
    <div class="collection">
        <h2>Your Cart</h2>
        <?php
        if (isset($_SESSION["cart_error"])) {
            echo '<div class="alert alert-danger">' . $_SESSION["cart_error"] . '</div>';
            unset($_SESSION["cart_error"]);
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($cartItems->num_rows == 0) {
                    echo '<tr><td colspan="5" style="text-align:center">Your Cart is Empty</td></tr>';
                } else {
                    $total_price = 0;
                    while ($item = $cartItems->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><img src="' . $item["product_image"] . '" alt="' . $item["product_name"] . '" width="50" height="50"></td>';
                        echo '<td>' . $item["product_name"] . '</td>';
                        echo '<td>$' . $item["price"] . '</td>';
                        echo '<td>
                            <form method="post" action="cart.php">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="' . $item["product_id"] . '">
                                <button type="submit" name="quantity" value="' . ($item["quantity"] - 1) . '" class="remove-btn" ' . ($item["quantity"] == 1 ? 'disabled' : '') . '>-</button>
                                ' . $item["quantity"] . '
                                <button type="submit" name="quantity" value="' . ($item["quantity"] + 1) . '" class="remove-btn">+</button>
                            </form>
                        </td>';
                        echo '<td>
                                <form method="post" action="cart.php">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="id" value="' . $item["product_id"] . '">
                                    <button type="submit" class="remove-btn">Remove</button>
                                </form>
                            </td>';
                        echo '</tr>';
                        $total_price += ($item["price"] * $item["quantity"]);
                    }
                    echo '<tr><td colspan="5" style="text-align:right"><strong>Total: $' . number_format($total_price, 2) . '</strong></td></tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
        if ($cartItems->num_rows > 0) {
            echo '<a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>';
        }
        ?>
    </div>
</main>
<?php
$stmt->close();
$conn->close();
include 'footer.php';
?>
