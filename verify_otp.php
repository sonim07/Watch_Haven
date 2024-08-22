<?php
session_start();

// Initialize variables for form input and errors
$otp_err = '';
include 'logger.php';
// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['verify_otp'])) {
        // Form submission to verify OTP
        $enteredOtp = $_POST['otp'];
        $generatedOtp = $_SESSION['otp'];
        $email = $_SESSION['otp_email'];

        if ($enteredOtp == $generatedOtp) {
            // OTP verified, redirect to reset password form
            $_SESSION['email'] = $email; // Store email in session for password reset

            // Redirect to reset password page
            header("Location: reset_password.php");
            exit();
        } else {
            $otp_err = "Invalid OTP. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
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

        .form-group input[type="text"] {
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
        <h2 style="text-align: center; margin-bottom: 20px;">Verify OTP</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" name="otp" required>
                <span class="error"><?php echo $otp_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="verify_otp" value="Verify OTP">
            </div>
        </form>

        <div class="back-home">
            <a href="index.php">Go back to homepage</a>
        </div>
    </div>
</body>
</html>
