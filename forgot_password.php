<?php
session_start();
include 'db.php'; // Include your database connection script
include 'logger.php';
// Initialize variables for form input and errors
$email = '';
$email_err = '';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['send_otp'])) {
        // Form submission to send OTP
        $email = trim($_POST["email"]);

        if (empty($email)) {
            $email_err = "Please enter your email.";
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);

            // Store OTP and email in session
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_email'] = $email;

            // Send OTP via email
            require 'vendor/autoload.php'; // Include PHPMailer autoload file

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'havenwatch2@gmail.com'; // Your Gmail address
                $mail->Password = 'blep dask tmxk wmwz'; // Your app-specific password
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('havenwatch2@gmail.com', 'Haven Watch');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'OTP Verification';
                $mail->Body = "Your OTP code is $otp.";

                $mail->send();
                
                // Redirect to verify OTP page
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                $email_err = "Failed to send OTP. Please try again. Mailer Error: " . $mail->ErrorInfo;
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
    <title>Forgot Password</title>
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

        .form-group input[type="email"],
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
        <h2 style="text-align: center; margin-bottom: 20px;">Forgot Password</h2>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="send_otp" value="Send OTP">
            </div>
        </form>

        <div class="back-home">
            <a href="index.php">Go back to homepage</a>
        </div>
    </div>
</body>
</html>
