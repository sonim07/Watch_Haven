<?php
session_start();
include 'db.php'; // Include your database connection script
include 'logger.php';
// Initialize variables for form input
$username = $password = '';
$username_err = $password_err = $account_locked_err = '';

// Define constants for account lockout and password expiry
define('MAX_FAILED_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutes
define('PASSWORD_EXPIRY_DAYS', 90);

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username and password for login
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement to fetch user data
        $sql = "SELECT id, username, password, failed_login_attempts, last_failed_login, account_locked_until FROM users WHERE username = ?";

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
                    $stmt->bind_result($id, $username, $hashed_password, $failed_login_attempts, $last_failed_login, $account_locked_until);
                    if ($stmt->fetch()) {

                        // Check if the account is locked
                        if ($account_locked_until && time() < strtotime($account_locked_until)) {
                            $account_locked_err = "Your account is locked. Please try again later.";
                        } else {
                            // Reset failed login attempts if account lockout has expired
                            if ($failed_login_attempts >= MAX_FAILED_ATTEMPTS) {
                                $failed_login_attempts = 0;
                                $account_locked_until = NULL;
                                $stmt_reset_lockout = $conn->prepare("UPDATE users SET failed_login_attempts = ?, account_locked_until = ? WHERE id = ?");
                                $stmt_reset_lockout->bind_param("isi", $failed_login_attempts, $account_locked_until, $id);
                                $stmt_reset_lockout->execute();
                                $stmt_reset_lockout->close();
                            }

                            if (password_verify($password, $hashed_password)) {
                                // Password is correct, check password expiry
                                $stmt_check_expiry = $conn->prepare("SELECT MAX(created_at) FROM password_history WHERE user_id = ?");
                                $stmt_check_expiry->bind_param("i", $id);
                                $stmt_check_expiry->execute();
                                $stmt_check_expiry->bind_result($last_password_change);
                                $stmt_check_expiry->fetch();
                                $stmt_check_expiry->close();

                                if ($last_password_change && (time() - strtotime($last_password_change)) > (PASSWORD_EXPIRY_DAYS * 24 * 60 * 60)) {
                                    // Password expired, redirect to reset_password.php
                                    header("location: reset_password.php");
                                    exit;
                                }

                                // Start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;

                                // Update last_login column and reset failed attempts
                                $update_last_login = "UPDATE users SET last_login = NOW(), failed_login_attempts = 0, account_locked_until = NULL WHERE id = ?";
                                if ($stmt_update = $conn->prepare($update_last_login)) {
                                    $stmt_update->bind_param("i", $id);
                                    $stmt_update->execute();
                                    $stmt_update->close();
                                }
                                
                                // Redirect user to index.php
                                header("location: index.php");
                                exit;
                            } else {
                                // Password is incorrect, increment failed attempts
                                $failed_login_attempts++;
                                if ($failed_login_attempts >= MAX_FAILED_ATTEMPTS) {
                                    // Lock the account
                                    $account_locked_until = date("Y-m-d H:i:s", time() + LOCKOUT_TIME);
                                    $password_err = "Your account is locked due to too many failed login attempts. Please try again later.";
                                } else {
                                    $password_err = "The password you entered is not valid.";
                                }

                                // Update the failed login attempts in the database
                                $stmt_update_attempts = $conn->prepare("UPDATE users SET failed_login_attempts = ?, last_failed_login = NOW(), account_locked_until = ? WHERE id = ?");
                                $stmt_update_attempts->bind_param("isi", $failed_login_attempts, $account_locked_until, $id);
                                $stmt_update_attempts->execute();
                                $stmt_update_attempts->close();
                            }
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
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
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
    <style>
        /* Additional styles specific to this page */
        body {
            background-image: url('images/1.jpg'); /* Adjust path to your background image */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .form-wrapper {
            max-width: 400px;
            padding: 40px;
            padding-right: 70px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            background-image: url('images/2.jpg'); /* Adjust path to your background image */
            background-blend-mode: color-dodge;
        }

        .form-wrapper h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="submit"] {
            background-color: orange;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-group input[type="submit"]:hover {
            background-color: pink;
        }

        .error {
            color: red;
        }

        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-home a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            border: 1px solid #ccc;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-home a:hover {
            background-color: #333;
            color: #fff;
        }

        .forgot-password {
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }

        .forgot-password a:hover {
            color: #0056b3;
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
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                <span class="error"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <span class="error"><?php echo $password_err; ?></span>
                <span class="error"><?php echo $account_locked_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>

        <div class="forgot-password">
            <a href="forgot_password.php">Forgot your password?</a>
        </div>

        <div class="back-home">
            <a href="index.php">Go back to homepage</a>
        </div>
    </div>
</body>
</html>
