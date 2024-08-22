<?php
session_start();
include 'db.php'; // Include your database connection script

// Initialize variables for form input and errors
$password = '';
$confirm_password = '';
$password_err = '';
$confirm_password_err = '';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reset_password'])) {
        // Form submission to reset password
        $password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);
        
        // Validate password
        if (empty($password)) {
            $password_err = "Please enter a password.";
        } elseif (strlen($password) < 6) {
            $password_err = "Password must have at least 6 characters.";
        }

        // Validate confirm password
        if (empty($confirm_password)) {
            $confirm_password_err = "Please confirm your password.";
        } elseif ($password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }

        // Check input errors before updating the database
        if (empty($password_err) && empty($confirm_password_err)) {
            // Get email from session
            $email = $_SESSION['email'];
            
            // Prepare an update statement
            $sql = "UPDATE users SET password = ? WHERE email = ?";
            
            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $param_password, $param_email);
                
                // Set parameters
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                $param_email = $email;
                
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Password updated successfully. Destroy the session and redirect to login page
                    session_destroy();
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            background-image: url('images/1.jpg');
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form wrapper styling */
        .form-wrapper {
            max-width: 400px;
            padding: 50px;
            background-color: #fff;
            color: #fff;
            background-image: url('images/2.jpg');
            background-size: cover;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Form styling */
        .form-wrapper form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group .error {
            color: red;
            font-size: 12px;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Back home link styling */
        .back-home {
            text-align: center;
            margin-top: 10px;
        }

        .back-home a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            border: 1px solid #ccc;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .back-home a:hover {
            background-color: #333;
            color: #fff;
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
    <div class="form-wrapper">
        <h2 style="text-align: center; margin-bottom: 20px;">Reset Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" value="<?php echo htmlspecialchars($confirm_password); ?>" required>
                <span class="error"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="reset_password" value="Reset Password">
            </div>
        </form>
        <div class="back-home">
            <a href="index.php">Go back to homepage</a>
        </div>
    </div>
</body>
</html>
