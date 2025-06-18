<?php
session_start();
include '../backend/db.php';

// Load the configuration file
$config = parse_ini_file('../backend/config.ini', true);

// Extract database configuration
$servername = $config['database']['servername'];
$username = $config['database']['username'];
$password = $config['database']['password'];
$database = $config['database']['database'];

// Create an instance of MySqlDB with the configuration details
$db = new MySqlDB($servername, $username, $password, $database);

// Fetch user details from session
$userDetails = $_SESSION['user_details'] ?? null;
$cartItems = $db->getCartItems();

// Load Razorpay key from config
$razorpayKey = $config['razorpay']['key'];

$baseImageUrl = '../backend/uploads/';
$defaultImage = 'default_course.png';

// Calculate subtotal, GST, total
$subtotal = array_sum(array_column($cartItems, 'offer_price'));
$gst = $subtotal * 0.18;
$total = $subtotal + $gst;

// Format only for display
$formattedGst = number_format($gst, 2);
$formattedTotal = number_format($total, 2);

// Log the total amount for debugging
error_log("Total amount calculated: " . $total);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment - My Courses</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles/bootstrap4/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Razorpay JS -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #4b0082, #008080, #000080);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
            color: #333;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }
        .card {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .card h3 {
            color: #4b0082;
            border-bottom: 3px solid #008080;
            padding-bottom: 12px;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .card p {
            margin: 12px 0;
            font-size: 17px;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .product-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .product-info img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-price {
            font-weight: bold;
            color: #4b0082;
            font-size: 18px;
        }
        .button {
            padding: 14px 28px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 18px;
            border: none;
            transition: background-color 0.3s;
            margin: 0 15px;
            background-color: rgb(67, 108, 100);
            color: white;
        }
        .button:hover {
            background-color: rgb(57, 98, 90);
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .payment-success {
            display: none;
            background: #d1e8d4;
            color: #008080;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 12px 0;
            font-size: 19px;
        }
        .summary-row:last-child {
            font-weight: bold;
            font-size: 22px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- User Details -->
    <?php if ($userDetails): ?>
        <div class="card">
            <h3>User Details</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($userDetails['first_name'] . ' ' . $userDetails['last_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($userDetails['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($userDetails['phone']) ?></p>
        </div>
    <?php endif; ?>

    <!-- Order Summary -->
    <div class="card">
        <h3>Order Summary</h3>
        <?php foreach ($cartItems as $item): ?>
            <div class="product-item">
                <div class="product-info">
                    <img src="<?= htmlspecialchars($baseImageUrl . ($item['image'] ?? $defaultImage)) ?>" alt="<?= htmlspecialchars($item['course_name'] ?? '') ?>">
                    <div>
                        <div><strong><?= htmlspecialchars($item['course_name']) ?></strong></div>
                        <div><?= htmlspecialchars($item['description'] ?? '') ?></div>
                    </div>
                </div>
                <div class="product-price">₹<?= number_format($item['offer_price'], 2) ?></div>
            </div>
        <?php endforeach; ?>
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>₹<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="summary-row">
            <span>GST (18%):</span>
            <span>₹<?= number_format($gst, 2) ?></span>
        </div>
        <div class="summary-row">
            <span>Total:</span>
            <span>₹<?= number_format($total, 2) ?></span>
        </div>
    </div>

    <!-- Payment Buttons -->
    <div class="card text-center">
        <div class="button-container">
            <button class="button" onclick="window.location.href='checkout.php'">Cancel</button>
            <button class="button" onclick="processPayment()">Pay Now</button>
            <button class="button" onclick="window.location.href='orders.php'">Go to Orders</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="paymentSuccess" class="payment-success text-center">
        Payment successful! Thank you for your purchase.
    </div>
</div>

<script>
function processPayment() {
    var options = {
        "key": "<?php echo $razorpayKey; ?>",
        "amount": "<?$formattedTotal ?>", // Ensure the amount is in paise
        "currency": "INR",
        "name": "My Courses",
        "description": "Course Purchase",
        "image": "images/logo.png", // Add your logo URL here
        "handler": function (response) {
            // Handle successful payment
            $.ajax({
                url: "process_payment.php",
                type: "POST",
                data: { payment_id: response.razorpay_payment_id },
                success: function(data) {
                    if (data.success) {
                        document.getElementById('paymentSuccess').style.display = 'block';
                        setTimeout(function() {
                            window.location.href = 'orders.php';
                        }, 2000);
                    } else {
                        console.error("Error:", data.message);
                        alert("Payment successful.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("An error occurred while processing your payment.");
                }
            });
        },
        "prefill": {
            "name": "<?= $userDetails['first_name'] ?? '' ?> <?= $userDetails['last_name'] ?? '' ?>",
            "email": "<?= $userDetails['email'] ?? '' ?>",
            "contact": "<?= $userDetails['phone'] ?? '' ?>"
        },
        "theme": {
            "color": "#008080"
        },
        "modal": {
            "ondismiss": function() {
                console.log("Payment form closed");
            }
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
}
</script>

</body>
</html>
