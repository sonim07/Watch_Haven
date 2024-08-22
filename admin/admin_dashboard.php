<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Include header and footer
include 'header.php';
include '../logger.php';
// Include database connection or functions to fetch data
include 'db.php';

// Fetch total sales data
$sql = "SELECT SUM(total_amount) AS total_sales FROM orders";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalSales = $row['total_sales'];
} else {
    $totalSales = 0;
}

// Fetch active users (last login within the last 30 days)
$sqlActiveUsers = "SELECT COUNT(*) AS active_users FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$resultActiveUsers = $conn->query($sqlActiveUsers);
if ($resultActiveUsers->num_rows > 0) {
    $rowActiveUsers = $resultActiveUsers->fetch_assoc();
    $activeUsers = $rowActiveUsers['active_users'];
} else {
    $activeUsers = 0;
}

// Fetch recent orders (orders placed within the last 30 days)
$sqlRecentOrders = "SELECT COUNT(*) AS recent_orders FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$resultRecentOrders = $conn->query($sqlRecentOrders);
if ($resultRecentOrders->num_rows > 0) {
    $rowRecentOrders = $resultRecentOrders->fetch_assoc();
    $recentOrders = $rowRecentOrders['recent_orders'];
} else {
    $recentOrders = 0;
}

// Fetch user activity data
$sql_activity = "SELECT last_login FROM users WHERE last_login IS NOT NULL ORDER BY last_login ASC";
$result_activity = $conn->query($sql_activity);
$activityData = [];
while ($row_activity = $result_activity->fetch_assoc()) {
    $activityData[] = $row_activity['last_login'];
}

// Process the activity data to count logins per day
$logins_per_day = [];
foreach ($activityData as $date) {
    $day = date('Y-m-d', strtotime($date));
    if (!isset($logins_per_day[$day])) {
        $logins_per_day[$day] = 0;
    }
    $logins_per_day[$day]++;
}

// Prepare data for Chart.js
$dates = array_keys($logins_per_day);
$logins = array_values($logins_per_day);

// Handle log clearing request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_logs'])) {
    $logFile = 'path/to/log.txt'; // Ensure this path is correct
    if (file_exists($logFile)) {
        file_put_contents($logFile, ''); // Clear the log file
        header('Location: admin_dashboard.php'); // Redirect to avoid resubmission
        exit;
    }
}
?>
<style>
    /* styles.css */

    /* Body and main container */
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .admin-dashboard {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ccc;
    }

    .dashboard-header h1 {
        font-size: 24px;
        color: #333;
        margin: 0;
    }

    .logout-link {
        color: #fff;
        background-color: #ff6347;
        text-decoration: none;
        font-weight: bold;
        padding: 8px 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .logout-link:hover {
        background-color: #e74c3c;
    }

    .dashboard-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .dashboard-option {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .dashboard-option:hover {
        transform: translateY(-5px);
    }

    .dashboard-link {
        color: #333;
        text-decoration: none;
        font-size: 18px;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    .dashboard-link:hover {
        color: #ff6347;
    }

    .dashboard-option p {
        margin-top: 10px;
        color: #666;
        font-size: 14px;
    }

    /* Chart.js specific styles */
    .chart-container {
        margin-bottom: 20px;
        height: 300px; /* Adjust height as needed */
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
<main class="admin-dashboard">
    <div class="dashboard-header">
        <h1>Welcome, Admin!</h1>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

    <!-- Total Sales Chart -->
    <div class="dashboard-option">
        <h2>Total Sales</h2>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Platform Activity -->
    <div class="dashboard-option">
        <h2>Platform Activity</h2>
        <p>Active Users (Last 30 Days): <strong><?php echo $activeUsers; ?></strong></p>
        <p>Recent Orders (Last 30 Days): <strong><?php echo $recentOrders; ?></strong></p>
    </div>

    <!-- User Activity Chart -->
    <div class="dashboard-option">
        <h2>User Activity</h2>
        <div class="chart-container">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <!-- Audit Log Display -->
    <div class="dashboard-option">
        <h2>Audit Trail</h2>
        <form method="post" action="clearlogs.php">
            <button type="submit" name="clear_logs" style="background-color: #ff6347; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">Clear Logs</button>
        </form>
        <div class="log-container">
            <h3>Recent Logs:</h3>
            <pre><?php
                $logFile = '../log.txt'; // Ensure this path is correct
                if (file_exists($logFile)) {
                    echo htmlspecialchars(file_get_contents($logFile));
                } else {
                    echo 'No logs available.';
                }
            ?></pre>
        </div>
    </div>

    <div class="dashboard-options">
        <div class="dashboard-option">
            <a href="add_product.php" class="dashboard-link">Add Product</a>
            <p>Add new products to the store.</p>
        </div>
        <div class="dashboard-option">
            <a href="delete_product.php" class="dashboard-link">Delete Product</a>
            <p>Delete products from the store.</p>
        </div>
        <div class="dashboard-option">
            <a href="manage_orders.php" class="dashboard-link">Manage Orders</a>
            <p>View and manage customer orders.</p>
        </div>
        <div class="dashboard-option">
            <a href="manage_users.php" class="dashboard-link">Manage Users</a>
            <p>Manage user accounts and permissions.</p>
        </div>
        <div class="dashboard-option">
            <a href="manage_reviews.php" class="dashboard-link">Manage Reviews</a>
            <p>Manage user reviews.</p>
        </div>
        <div class="dashboard-option">
            <a href="admin_messages.php" class="dashboard-link">Customer Messages</a>
            <p>Manage Customer Messages.</p>
        </div>
    </div>
</main>

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Get total sales data from PHP
    var totalSales = <?php echo json_encode($totalSales); ?>;

    // Chart.js code for total sales
    var ctxSales = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: ['Total Sales'],
            datasets: [{
                label: 'Total Sales',
                data: [totalSales],
                backgroundColor: '#FF6347', // Reddish color for bars
                borderColor: '#FF6347',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Get user activity data from PHP
    var dates = <?php echo json_encode($dates); ?>;
    var logins = <?php echo json_encode($logins); ?>;

    // Chart.js code for user activity
    var ctxActivity = document.getElementById('activityChart').getContext('2d');
    var activityChart = new Chart(ctxActivity, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Logins per Day',
                data: logins,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include 'footer.php'; ?>
