<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Haven</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<header>
    <div class="nav-container">
        <nav>
            <div class="logo"><a href="index.php">Watch Haven</a></div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php
                // Check if logged in
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    // Show profile, logout, and cart links
                    echo '<li><a href="profile.php">Profile</a></li>';
                    echo '<li><a href="logout.php">Logout</a></li>';
                    echo '<li><a href="cart.php"><i class="fa fa-shopping-cart"></i> Cart (' . (isset($_SESSION['cart_item']) ? count($_SESSION['cart_item']) : +0) . ')</a></li>';
                } else {
                    // Show login, register, and disabled cart link
                    echo '<li><a href="login.php">Login</a></li>';
                    echo '<li><a href="register.php">Register</a></li>';
                    echo '<li><a href="login.php" onclick="alert(\'Please login to proceed to checkout.\');"><i class="fa fa-shopping-cart"></i> Cart ('. (isset($_SESSION['cart_item']) ? count($_SESSION['cart_item']) : +0) . ')</a></li>';
                }
                ?>
                
            </ul>
        </nav>
    </div>
</header>

<!-- Your page content here -->

</body>
</html>
