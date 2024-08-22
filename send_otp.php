<?php
session_start();
require 'vendor/autoload.php'; // Make sure this path is correct relative to your project

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db.php'; // Include your database connection script
include 'logger.php';
// Initialize variables for form input and errors
$email = $otp = '';
$email_err = $otp_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // If there are no errors, proceed with OTP generation and email sending
    if (empty($email_err)) {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in session
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $email;

        // Send OTP via email
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'havenwatch2@gmail.com'; // Replace with your Gmail address
            $mail->Password = 'havenwatch123'; // Replace with your Gmail password or app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('havenwatch2@gmail.com', 'Your Name'); // Update with your Gmail address and name
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'OTP Verification';
            $mail->Body = "Your OTP code is $otp.";

            $mail->send();
            echo 'OTP has been sent to your email address.';

            // Redirect to OTP verification page
            header("location: verify_otp.php");
            exit;
        } catch (Exception $e) {
            echo "Failed to send OTP. Please try again. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .form-group input[type="email"] {
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
        <h2>Forgot Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email">Enter your email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Send OTP">
            </div>
        </form>

        <div class="back-home">
            <a href="index.php">Go back to homepage</a>
        </div>
    </div>
</body>
</html>
