<?php
session_start();
include '../backend/db.php';

$db = new MySqlDB();

// Initialize $userDetails as null
$userDetails = null;

// Check if user_id is set in the session before attempting to fetch user details
if (isset($_SESSION['user_id'])) {
    $userDetails = $db->getUserDetails($_SESSION['user_id']);
}

$mergedData = $db->getMergedOrders();

$baseImageUrl = 'http://localhost/admin_panel/backend/uploads/';
$defaultImage = 'default_course.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Course - Checkout</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        .checkout-container {
            display: flex;
            flex-direction: row;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 20px auto;
            padding: 20px;
            max-width: 1200px;
        }
        .checkout-form {
            display: flex;
            flex-direction: column;
        }
        .checkout-form .form-group {
            margin-bottom: 15px;
        }
        .checkout-form .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .checkout-form input, .checkout-form textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(162, 66, 110, 0.1);
            padding: 20px;
            margin-right: 20px;
            flex: 1;
        }
        .card h3 {
            margin-bottom: 20px;
        }
        .card-body {
            display: flex;
            flex-direction: column;
        }
        .product-list {
            list-style-type: none;
            padding: 0;
        }
        .product-list li {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .product-list li .product-info {
            flex: 1;
            display: flex;
            align-items: center;
        }
        .product-list li .product-info img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .product-list li .product-price {
            flex: 0 0 auto;
            text-align: right;
        }
        .order-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .order-summary .total {
            font-weight: bold;
        }
        .place-order-button, .back-button {
            margin-top: 20px;
            padding: 6px 12px;
            background-color: #00008B;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        .place-order-button:hover, .back-button:hover {
            background-color: #00005f;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <div class="billing-card card">
        <h2>Checkout</h2>
        <h3>Billing Details</h3>
        <div class="card-body">
            <form class="checkout-form" id="checkoutForm">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="city">Town / City</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="collegeName">College Name</label>
                    <input type="text" id="collegeName" name="collegeName">
                </div>
                <div class="form-group">
                    <label for="currentSemester">Current Semester</label>
                    <input type="text" id="currentSemester" name="currentSemester">
                </div>
                <div class="form-group">
                    <label for="hearAboutUs">How did you hear about us?</label>
                    <textarea id="hearAboutUs" name="hearAboutUs" rows="4"></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="summary-card card">
        <h3>Summary</h3>
        <div class="card-body">
            <ul class="product-list" id="productList">
                <!-- Products will be dynamically added here -->
            </ul>
            <div class="order-summary">
                <div class="total">Subtotal:</div>
                <div class="total-amount" id="subtotalAmount">₹0.00</div>
            </div>
            <div class="order-summary">
                <div class="total">GST:</div>
                <div class="total-amount" id="gstAmount">₹0.00</div>
            </div>
            <div class="order-summary">
                <div class="total">Total:</div>
                <div class="total-amount" id="totalAmount">₹0.00</div>
            </div>
            <button class="place-order-button" id="placeOrderButton">Place Order</button>
            <button class="back-button" onclick="window.location.href='orders.php'">Back to Orders</button>
        </div>
    </div>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script>
$(document).ready(function () {
    function loadCartItems() {
        $.ajax({
            url: "http://localhost/admin_panel/backend/courses_api",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ action: "get_cart_items" }),
            success: function (response) {
                if (response.success) {
                    displayCartItems(response.data);
                    calculateTotal(response.data);
                } else {
                    console.error("Error fetching cart items:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    function displayCartItems(items) {
        let productList = $('#productList');
        productList.empty();
        items.forEach(item => {
            let listItem = `
                <li>
                    <div class="product-info">
                        <img src="${item.image}" alt="${item.course_name}" style="width: 50px; height: 50px; border-radius: 5px; margin-right: 10px;">
                        <div>
                            <div>${item.course_name}</div>
                            <div>${item.description}</div>
                        </div>
                    </div>
                    <div class="product-price">₹${item.offer_price}</div>
                </li>
            `;
            productList.append(listItem);
        });
    }

    function calculateTotal(items) {
        let subtotal = items.reduce((acc, item) => acc + parseFloat(item.offer_price), 0);
        let gst = subtotal * 0.18;
        let total = subtotal + gst;
        $('#subtotalAmount').text(`₹${subtotal.toFixed(2)}`);
        $('#gstAmount').text(`₹${gst.toFixed(2)}`);
        $('#totalAmount').text(`₹${total.toFixed(2)}`);
    }

    loadCartItems();

    $('#placeOrderButton').click(function () {
        if (!$('#checkoutForm')[0].checkValidity()) {
            alert("Please fill in all required fields.");
            return;
        }

        let formData = {
            firstName: $('#firstName').val(),
            lastName: $('#lastName').val(),
            city: $('#city').val(),
            phone: $('#phone').val(),
            email: $('#email').val(),
            collegeName: $('#collegeName').val() || null,
            currentSemester: $('#currentSemester').val() || null,
            hearAboutUs: $('#hearAboutUs').val() || null
        };

        $.ajax({
            url: "http://localhost/admin_panel/backend/courses_api",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ action: "place_order", data: formData }),
            success: function (response) {
                if (response.success) {
                    // Store user details in session
                    sessionStorage.setItem('userDetails', JSON.stringify(formData));
                    // Redirect to payment page
                    window.location.href = 'payment.php';
                } else {
                    alert("Error placing order: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                alert("An error occurred while placing the order.");
            }
        });
    });
});
</script>

</body>
</html>
