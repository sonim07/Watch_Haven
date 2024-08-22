<?php
session_start();
include 'header.php'; // Include your header
include 'db.php'; // Include your database connection script

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}
// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags($data));
}

// Function to update user details
function updateUser($conn, $id, $username, $email) {
    $sql = "UPDATE users SET username=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $email, $id);
    $stmt->execute();
    $stmt->close();
}

// Function to delete user
function deleteUser($conn, $id) {
    $sql = "DELETE FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all users from database
$sql = "SELECT id, username, email FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your main CSS file -->
    <style>
        /* Additional styles specific to this page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        td {
            color: #666;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
        }
        .action-buttons form {
            margin: 0 5px;
        }
        .action-buttons input[type="submit"] {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .action-buttons input[type="submit"]:hover {
            background-color: #d32f2f;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
            text-decoration: none;
        }
        .back-link:hover {
            color: #333;
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
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . sanitize_input($row['id']) . "</td>";
                        echo "<td>" . sanitize_input($row['username']) . "</td>";
                        echo "<td>" . sanitize_input($row['email']) . "</td>";
                        echo '<td class="action-buttons">
                                <form action="edit_user.php" method="post">
                                    <input type="hidden" name="id" value="' . sanitize_input($row['id']) . '">
                                    <input type="submit" value="Edit">
                                </form>
                                <form action="delete_user.php" method="post">
                                    <input type="hidden" name="id" value="' . sanitize_input($row['id']) . '">
                                    <input type="submit" value="Delete" onclick="return confirm(\'Are you sure?\');">
                                </form>
                              </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a class="back-link" href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
