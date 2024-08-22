<?php
session_start();


include 'header.php'; // Include header navigation

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}
// Dummy data for demonstration
$orders = [
    ['id' => 1, 'customer_name' => 'John Doe', 'total_price' => 120.50, 'status' => 'Pending'],
    ['id' => 2, 'customer_name' => 'Jane Smith', 'total_price' => 89.99, 'status' => 'Shipped'],
    ['id' => 3, 'customer_name' => 'Michael Brown', 'total_price' => 200.00, 'status' => 'Delivered'],
];
?>

<main class="admin-content">
    <h2>Manage Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Total Price</th>
            <th>Status</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo $order['customer_name']; ?></td>
            <td>$<?php echo number_format($order['total_price'], 2); ?></td>
            <td><?php echo $order['status']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include 'footer.php'; ?>
