<?php
session_start();
include 'header.php';
include 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}
// Function to fetch orders from the database
function fetchOrders($conn) {
    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);
    return $result;
}

// Function to update order status
function updateOrderStatus($conn, $order_id, $status) {
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submission for updating order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    updateOrderStatus($conn, $order_id, $status);
}

?>
<style>
    /* Global Styles */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.manage-orders-container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
}

.manage-orders {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.manage-orders h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.order-table th,
.order-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.order-table th {
    background-color: #f4f4f4;
    color: #333;
}

.order-table tbody tr:hover {
    background-color: #f9f9f9;
}

.order-table td select {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

.order-table td button {
    padding: 8px 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.order-table td button:hover {
    background-color: #0056b3;
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
<main class="manage-orders-container">
    <div class="manage-orders">
        <h2>Manage Orders</h2>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch orders from database
                $orders = fetchOrders($conn);

                if ($orders->num_rows > 0) {
                    while ($order = $orders->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($order["id"]) . '</td>';
                        echo '<td>' . htmlspecialchars($order["customer_name"]) . '</td>';
                        echo '<td>' . htmlspecialchars($order["order_date"]) . '</td>';
                        echo '<td>' . htmlspecialchars($order["status"]) . '</td>';
                        echo '<td>';
                        echo '<form method="post" action="manage_orders.php">';
                        echo '<input type="hidden" name="order_id" value="' . htmlspecialchars($order["id"]) . '">';
                        echo '<select name="status">';
                        echo '<option value="Processing">Processing</option>';
                        echo '<option value="Confirmed">Confirmed</option>';
                        echo '<option value="Shipped">Shipped</option>';
                        echo '<option value="Delivered">Delivered</option>';
                        echo '</select>';
                        echo '<button type="submit" name="update_status">Update</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No orders found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'footer.php'; ?>
