<?php include 'header.php'; ?>
<?php include 'db.php'; ?>
<?php include 'logger.php'; ?>
<style>
    body {
        background-image: url('images/2.jpg');
        background-size: cover;
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

<div class="contact-section">
    <h1>Contact Us</h1>
    <form action="contact.php" method="POST" id="contactForm">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required> <!-- Added phone input field -->
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>
        <input type="submit" value="Submit">
    </form>
</div>

<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // SQL query to insert data into contacts table
    $sql = "INSERT INTO contacts (name, email, phone, message, created_at) 
            VALUES ('$name', '$email', '$phone', '$message', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Display success message via JavaScript
        echo '<script>
                alert("Message sent successfully");
                window.location.href = "index.php"; // Redirect to homepage
              </script>';
        exit; // Stop further execution
    } else {
        // Display error message
        echo '<script>alert("Error: ' . $conn->error . '");</script>';
    }

    $conn->close();
}
?>

<?php include 'footer.php'; ?>
