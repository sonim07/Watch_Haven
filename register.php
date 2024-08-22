<?php
session_start();
include 'db.php'; // Include your database connection script

// Initialize variables for form input
$username = $email = $password = $confirm_password = '';
$username_err = $email_err = $password_err = $confirm_password_err = '';

// Function to check password strength
function is_password_strong($password) {
    $errors = [];

    // Check password length
    if (strlen($password) < 8 || strlen($password) > 12) {
        $errors[] = "Password must be between 8 and 12 characters long.";
    }

    // Check for uppercase letters
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must include at least one uppercase letter.";
    }

    // Check for lowercase letters
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must include at least one lowercase letter.";
    }

    // Check for numbers
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must include at least one number.";
    }

    // Check for special characters
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors[] = "Password must include at least one special character.";
    }

    return $errors;
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                $username_err = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
        $password_errors = is_password_strong($password);
        if (!empty($password_errors)) {
            $password_err = implode(" ", $password_errors);
        }
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement for the users table
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_email, $param_password);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Get the user_id of the newly created user
                $user_id = $conn->insert_id;

                // Insert the password into the password_history table
                $sql_history = "INSERT INTO password_history (user_id, password) VALUES (?, ?)";
                if ($stmt_history = $conn->prepare($sql_history)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt_history->bind_param("is", $user_id, $param_password);

                    // Execute the statement
                    $stmt_history->execute();

                    // Close the history statement
                    $stmt_history->close();
                }

                // Redirect to the login page
                header("location: login.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close the statement
            $stmt->close();
        }
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
    <style>
        /* Additional styles specific to this page */
        body {
            background-image: url('images/background.jpg');
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
            background-image: url('images/4.jpg');
            background-size: cover;
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
        .form-group input[type="email"],
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
    <script>
        function validatePassword() {
            const passwordInput = document.getElementById('password');
            const feedback = document.getElementById('passwordFeedback');
            const password = passwordInput.value;

            let strength = '';

            // Check password strength
            const strongPassword = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d@$!%*?&]{8,12}$/;
            if (strongPassword.test(password)) {
                strength = 'Strong';
                feedback.style.color = 'green';
            } else {
                strength = 'Weak';
                feedback.style.color = 'red';
            }

            feedback.textContent = `Password Strength: ${strength}`;
        }

        function showAlert(message) {
            alert(message);
        }

        function validateForm(event) {
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordFeedback = document.getElementById('passwordFeedback');

            let errors = [];

            // Validate username
            if (!usernameInput.value.trim()) {
                errors.push("Please enter a username.");
            }

            // Validate email
            if (!emailInput.value.trim()) {
                errors.push("Please enter an email.");
            }

            // Validate password
            const password = passwordInput.value;
            const strongPassword = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d@$!%*?&]{8,12}$/;
            if (!password) {
                errors.push("Please enter a password.");
            } else if (!strongPassword.test(password)) {
                errors.push("Password must be between 8 and 12 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.");
            }

            // Validate confirm password
            const confirmPassword = confirmPasswordInput.value;
            if (!confirmPassword) {
                errors.push("Please confirm your password.");
            } else if (password !== confirmPassword) {
                errors.push("Password did not match.");
            }

            // Show errors if any
            if (errors.length > 0) {
                showAlert(errors.join("\n"));
                event.preventDefault();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            passwordInput.addEventListener('input', validatePassword);

            const form = document.querySelector('form');
            form.addEventListener('submit', validateForm);
        });
    </script>
</head>
<body>
    <div class="form-wrapper">
        <h2>Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                <span class="error"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <span id="passwordFeedback"></span>
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <span class="error"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Register">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
