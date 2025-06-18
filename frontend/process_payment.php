<?php
session_start();
require_once 'db.php';

// Initialize the database connection
$db = new MySqlDB();

// Check if the session ID is valid and process the payment
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $success = $db->processPayment($userId);

    // Clear the cart after successful payment
    if ($success) {
        $db->clearCart($userId); // Ensure the user ID is passed to clear the correct cart
    }
} else {
    $success = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5 text-center">
    <?php if ($success): ?>
        <h2 class="text-success">Payment Successful!</h2>
        <p>Thank you for your purchase. Your payment has been recorded.</p>
        <a href="orders.php" class="btn btn-primary mt-3">View Your Orders</a>
    <?php else: ?>
        <h2 class="text-danger">Payment Failed!</h2>
        <p>Something went wrong. Please try again.</p>
        <a href="cart.php" class="btn btn-danger mt-3">Return to Cart</a>
    <?php endif; ?>
</div>
</body>
</html>
