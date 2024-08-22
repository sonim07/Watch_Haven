<?php
session_start();

include 'db.php'; // Include database connection

// Initialize variables
$username = $password = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a select statement
    $sql = "SELECT id, username, password FROM admins WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($id, $username, $hashed_password);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        session_start();

                        // Store data in session variables
                        $_SESSION["admin_id"] = $id;
                        $_SESSION["admin_username"] = $username;
                        $_SESSION["admin_logged_in"] = true;

                        // Redirect user to admin dashboard page
                        header("location: admin_dashboard.php");
                        exit(); // Ensure script stops execution after redirection
                    } else {
                        // Display an error message if password is not valid
                        $error_message = "Invalid password.";
                    }
                }
            } else {
                // Display an error message if username doesn't exist
                $error_message = "Invalid username.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
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
    <title>Admin Login</title>
    <!-- Add your CSS link -->
    <style>
        /* Form styles */
        body{
            background-image: url('../images/1.jpg');
            background-size: cover;
        }
        .admin-login {
            max-width: 400px;
            margin: 50px auto;
            padding: 40px;
            padding-right: 70px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-image: url('../images/3.jpg');
            background-size: cover;
            color: white;
        }

        .admin-login h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .has-error .form-control {
            border-color: #ff6347;
        }

        .help-block {
            color: #ff6347;
            font-size: 14px;
        }

        .btn {
            background-color: #ff6347;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #ff4500;
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
<main class="admin-login">
    <h2>Admin Login</h2>
    <?php if (!empty($error_message)) echo "<p>$error_message</p>"; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Login</button>
            <a href="register.php" class="btn">Register</a>
        </div>
    </form>
</main>
</body>
</html>
