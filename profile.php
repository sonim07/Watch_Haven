<?php
session_start();
include 'db.php'; // Include your database connection script
include 'functions.php'; // Include your user functions

// Redirect to login if user is not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch user details
$username = $_SESSION["username"];
$userDetails = getUserDetails($conn, $username);

// Initialize variables for form input and errors with default empty values
$name = $email = $phone = '';
$name_err = $email_err = $phone_err = '';

// Check if user details were fetched successfully
if ($userDetails !== null) {
    $name = isset($userDetails['name']) ? $userDetails['name'] : '';
    $email = isset($userDetails['email']) ? $userDetails['email'] : '';
    $phone = isset($userDetails['phone']) ? $userDetails['phone'] : '';
} else {
    // Handle error fetching user details
    echo "Error fetching user details.";
    exit;
}

// Process profile update form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])) {

    // Validate and update name
    if (isset($_POST["name"])) {
        $name = trim($_POST["name"]);
    }

    // Validate and update email
    if (isset($_POST["email"])) {
        $email = trim($_POST["email"]);
    }

    // Validate and update phone
    if (isset($_POST["phone"])) {
        $phone = trim($_POST["phone"]);
    }

    // Check input errors before updating profile
    if (empty($name_err) && empty($email_err) && empty($phone_err)) {
        if (updateUserProfile($conn, $username, $name, $email, $phone)) {
            // Update session variables if needed
            $_SESSION["username"] = $username;
            // Redirect to profile with success message
            header("location: profile.php");
            exit;
        } else {
            echo "Error updating profile.";
        }
    }
}

// Process password change form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {

    $current_password = trim($_POST["current_password"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validate current password
    if (!empty($current_password) && validatePassword($conn, $username, $current_password)) {
        // Validate new password
        if (!empty($new_password) && strlen($new_password) >= 6 && $new_password === $confirm_password) {
            // Update password
            if (updatePassword($conn, $username, $new_password)) {
                // Password updated successfully
                header("location: profile.php");
                exit;
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "Invalid new password or passwords do not match.";
        }
    } else {
        echo "Current password is incorrect.";
    }
}

// Process profile image update form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_image'])) {
    // Handle file upload for profile picture update
    $target_dir = "uploads/"; // Directory where uploaded images will be stored
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now update profile image path in database
            if (updateProfileImage($conn, $username, $target_file)) {
                echo "Profile image updated successfully.";
            } else {
                echo "Error updating profile image.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('images/pp.jpg');
            background-size: cover;
        }
        .profile-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-header h2 {
            margin-bottom: 10px;
        }
        .profile-content {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }
        .profile-content h3 {
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
        .error {
            color: red;
            font-size: 0.9em;
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
<header>
    <?php include 'header.php'; ?>
</header>
<main class="profile-container">
    <div class="profile-header">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    </div>
    <div class="profile-content">
        <h3>Edit Your Profile</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <span class="error"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                <span class="error"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Profile" name="update_profile">
            </div>
        </form>
        <h3>Change Password</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Change Password" name="change_password">
            </div>
        </form>
        <h3>Upload Profile Picture</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="upload_image">
        </form>
    </div>
</main>
<footer>
    <?php include 'footer.php'; ?>
</footer>
</body>
</html>
