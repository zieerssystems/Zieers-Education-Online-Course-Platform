<?php
session_start();
include '../backend/db.php';

$db = new MySqlDB();

// Fetch user details from session
$userDetails = isset($_SESSION['user_details']) ? $_SESSION['user_details'] : null;

$mergedData = $db->getMergedOrders();

$baseImageUrl = 'http://localhost/admin_panel/backend/uploads/';
$defaultImage = 'default_course.png';

// Calculate subtotal, GST, and total
$subtotal = array_sum(array_column($mergedData, 'offer_price'));
$gst = $subtotal * 0.18;
$total = $subtotal + $gst;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment - My Courses</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .card-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: linear-gradient(145deg, #ffffff, #e0f2f1);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            border: none;
        }

        .card-title {
            border-bottom: 2px solid #00796b;
            padding-bottom: 10px;
            margin-bottom: 15px;
            text-align: center;
            color: #004d40;
            font-size: 1.5rem;
        }

        .user-details-card .detail-row {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            padding: 8px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 5px;
        }

        .user-details-card .detail-row label {
            font-weight: bold;
            color: #00796b;
        }

        .user-details-card .detail-row span {
            color: #004d40;
        }

        .summary-card .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 5px;
        }

        .summary-card .product-info {
            display: flex;
            align-items: center;
        }

        .summary-card .product-info img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            margin-right: 10px;
        }

        .summary-card .product-price {
            font-weight: bold;
            color: #00796b;
        }

        .order-summary {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .order-summary .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #004d40;
        }

        .order-summary .summary-row:last-child {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #00796b;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #b2dfdb;
            border-radius: 5px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pay-button, .cancel-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .pay-button {
            background-color: #00796b;
            color: white;
        }

        .cancel-button {
            background-color: #b71c1c;
            color: white;
        }

        .pay-button:hover {
            background-color: #004d40;
        }

        .cancel-button:hover {
            background-color: #800000;
        }

        .payment-success {
            display: none;
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card-container">
        <!-- User Details Card -->
        <?php if ($userDetails): ?>
            <div class="card user-details-card">
                <h3 class="card-title">User Details</h3>
                <div class="detail-row">
                    <label>First Name:</label>
                    <span><?= htmlspecialchars($userDetails['firstName']) ?></span>
                </div>
                <div class="detail-row">
                    <label>Last Name:</label>
                    <span><?= htmlspecialchars($userDetails['lastName']) ?></span>
                </div>
                <div class="detail-row">
                    <label>City:</label>
                    <span><?= htmlspecialchars($userDetails['city']) ?></span>
                </div>
                <div class="detail-row">
                    <label>Phone:</label>
                    <span><?= htmlspecialchars($userDetails['phone']) ?></span>
                </div>
                <div class="detail-row">
                    <label>Email:</label>
                    <span><?= htmlspecialchars($userDetails['email']) ?></span>
                </div>
                <div class="detail-row">
                    <label>College Name:</label>
                    <span><?= htmlspecialchars($userDetails['collegeName'] ?? 'N/A') ?></span>
                </div>
                <div class="detail-row">
                    <label>Current Semester:</label>
                    <span><?= htmlspecialchars($userDetails['currentSemester'] ?? 'N/A') ?></span>
                </div>
                <div class="detail-row">
                    <label>How did you hear about us?:</label>
                    <span><?= htmlspecialchars($userDetails['hearAboutUs'] ?? 'N/A') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Summary Card -->
        <div class="card summary-card">
            <h3 class="card-title">Summary</h3>
            <div class="card-body">
                <?php foreach ($mergedData as $item): ?>
                    <div class="product-item">
                        <div class="product-info">
                            <img src="<?= htmlspecialchars($baseImageUrl . ($item['image'] ?? $defaultImage)) ?>" alt="<?= htmlspecialchars($item['course_name'] ?? '') ?>">
                            <div>
                                <div><?= htmlspecialchars($item['course_name'] ?? '') ?></div>
                                <div><?= htmlspecialchars($item['description'] ?? '') ?></div>
                            </div>
                        </div>
                        <div class="product-price">₹<?= htmlspecialchars($item['offer_price'] ?? '0.00') ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>₹<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>GST:</span>
                        <span>₹<?= number_format($gst, 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Total:</span>
                        <span>₹<?= number_format($total, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="card">
        <h3 class="card-title">Payment Information</h3>
        <div class="button-container">
            <button class="cancel-button" onclick="cancelPayment()">Cancel</button>
            <button class="pay-button" onclick="payNow()">Pay Now</button>
        </div>
    </div>

    <div id="paymentSuccess" class="payment-success">
        Payment successful! Thank you for your purchase.
    </div>
</div>

<!-- Include Razorpay JavaScript Library -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function payNow() {
    var options = {
        "key": "rzp_test_nKyYkRw2gRb1zO", // Enter the Key ID generated from the Dashboard
        "amount": "<?= $total * 100 ?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        "currency": "INR",
        "name": "Your Company Name",
        "description": "Test Transaction",
        "image": "https://example.com/your_logo", // You can add your company logo URL here
        "handler": function (response){
            // Handle successful payment response
            document.getElementById('paymentSuccess').style.display = 'block';
            // Optionally, you can redirect to a success page or perform other actions here
            alert("Payment successful! Payment ID: " + response.razorpay_payment_id);
        },
        "prefill": {
            "name": "<?= htmlspecialchars($userDetails['firstName'] . ' ' . $userDetails['lastName']) ?>",
            "email": "<?= htmlspecialchars($userDetails['email']) ?>",
            "contact": "<?= htmlspecialchars($userDetails['phone']) ?>"
        },
        "notes": {
            "address": "Razorpay Corporate Office"
        },
        "theme": {
            "color": "#00796b"
        }
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
}

function cancelPayment() {
    alert("Payment has been canceled.");
    window.location.href = 'checkout.php';
}
</script>

</body>
</html>
