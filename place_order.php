<?php
session_start();
include 'db.php'; // Include your database connection script
include 'logger.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load Composer's autoloader for PHPMailer

// Function to send invoice email
function sendInvoiceEmail($recipientEmail, $recipientName, $orderDetails) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'havenwatch2@gmail.com';
        $mail->Password   = 'blep dask tmxk wmwz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('havenwatch2@gmail.com', 'Watch Haven');
        $mail->addAddress($recipientEmail, $recipientName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Invoice from Watch Haven';
        $mail->Body    = $orderDetails;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function generateInvoiceHtml($order_id, $customer_name, $customer_email, $customer_phone, $customer_address, $cartItems, $total_amount) {
    $html = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice</title>
        <style>
            /* Reset and base styles */
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                background-color: #f0f0f0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            
            .invoice-container {
                max-width: 800px;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            }
            
            .invoice-header {
                text-align: center;
                margin-bottom: 20px;
            }
            
            .invoice-header h2 {
                color: #333;
            }
            
            .invoice-details {
                margin-bottom: 20px;
            }
            
            .invoice-details table {
                width: 100%;
                border-collapse: collapse;
            }
            
            .invoice-details th, .invoice-details td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
                text-align: left;
            }
            
            .invoice-details th {
                background-color: #f4f4f4;
                color: #333;
            }
            
            .invoice-details tbody tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            
            .invoice-total {
                margin-top: 20px;
                text-align: right;
            }
            
            .invoice-total strong {
                font-size: 1.2em;
                color: #333;
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
        <div class="invoice-container">
            <div class="invoice-header">
                <h2>Invoice</h2>
            </div>
            <div class="invoice-details">
                <p><strong>Order ID:</strong> ' . $order_id . '</p>
                <p><strong>Customer Name:</strong> ' . $customer_name . '</p>
                <p><strong>Customer Email:</strong> ' . $customer_email . '</p>
                <p><strong>Customer Phone:</strong> ' . $customer_phone . '</p>
                <p><strong>Customer Address:</strong> ' . $customer_address . '</p>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($cartItems as $item) {
                        $html .= '<tr>';
                        $html .= '<td>' . $item["name"] . '</td>';
                        $html .= '<td>$' . $item["price"] . '</td>';
                        $html .= '<td>' . $item["quantity"] . '</td>';
                        $html .= '<td>$' . number_format(($item["price"] * $item["quantity"]), 2) . '</td>';
                        $html .= '</tr>';
                    }
                $html .= '</tbody>
                </table>
            </div>
            <div class="invoice-total">
                <strong>Total: $' . number_format($total_amount, 2) . '</strong>
            </div>
        </div>
    </body>
    </html>';
    return $html;
}

// Check if form is submitted and process the order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all necessary fields are provided
    if (isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['payment_method'])) {
        if (!isset($_SESSION["cart_item"]) || empty($_SESSION["cart_item"])) {
            echo "Your cart is empty. Please add items to your cart before placing an order.";
            exit;
        }

        $customer_name = $_POST['name'];
        $customer_email = $_POST['email'];
        $customer_phone = $_POST['phone'];
        $customer_address = $_POST['address'];
        $payment_method = $_POST['payment_method'];

        // Calculate total amount from session or however you store it
        $total_amount = 0;
        foreach ($_SESSION["cart_item"] as $item) {
            $total_amount += $item["price"] * $item["quantity"];
        }

        // Capture current timestamp for order date
        $order_date = date('Y-m-d H:i:s');

        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, order_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdss", $customer_name, $customer_email, $customer_phone, $customer_address, $total_amount, $payment_method, $order_date);

        if ($stmt->execute()) {
            // Order successfully placed
            $order_id = $stmt->insert_id; // Get the ID of the inserted order

            $orderDetails = generateInvoiceHtml($order_id, $customer_name, $customer_email, $customer_phone, $customer_address, $_SESSION["cart_item"], $total_amount);

            // Send invoice email
            sendInvoiceEmail($customer_email, $customer_name, $orderDetails);

            // Display the invoice to the user
            echo $orderDetails;

            // Clear cart or perform other post-order actions
            unset($_SESSION['cart_item']);

            // Redirect to index.php after processing the order to prevent form resubmission
            echo '<script>alert("Your order has been placed. Invoice sent to your email."); window.location.href = "index.php";</script>';
        } else {
            // Error in executing the query
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        // Handle case where required fields are not provided
        echo "Please fill in all required fields.";
    }
} else {
    // Handle case where form was not submitted via POST
    echo "Invalid request.";
}
?>
