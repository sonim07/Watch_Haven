<?php
session_start();
// Clear the cart after successful payment
unset($_SESSION["cart_item"]);

// Redirect to thank you page or order summary
header("Location: thank_you.php");
exit;
?>
