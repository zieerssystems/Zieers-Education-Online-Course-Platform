<!DOCTYPE html>
<html lang="en">
<head>
<title>Course - Cart</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Course Project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/cart_styles.css">
<link rel="stylesheet" type="text/css" href="styles/cart_responsive.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    body {
        background-color: #f1f3f6;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .cart-container {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        margin: 30px auto;
        padding: 30px;
        max-width: 1000px;
        animation: fadeIn 0.5s ease-in-out;
    }

    h2 {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 25px;
    }

    .cart-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        border-bottom: 1px solid #e0e0e0;
        padding: 20px 0;
    }

    .cart-item img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .cart-item-description {
        font-size: 1rem;
        color: #555;
        line-height: 1.4;
        margin-bottom: 12px;
    }

    .price-wrapper {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .cart-item-price {
        font-size: 1rem;
        color: #999;
        text-decoration: line-through;
    }

    .cart-item-offer-price {
        font-size: 1.3rem;
        color: #27ae60;
        font-weight: 600;
    }

    .cart-item-actions {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-end;
    }

    .remove-from-cart-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .remove-from-cart-btn:hover {
        background-color: #c0392b;
    }

    .cart-total, .cart-gst, .cart-final-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #eee;
    }

    .cart-total-label, .cart-gst-label, .cart-final-total-label {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .cart-total-value, .cart-gst-value, .cart-final-total-value {
        font-size: 1.2rem;
        color: #2c3e50;
    }

    .checkout-btn, .add-course-btn {
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 6px;
        width: auto;
        margin-top: 15px;
    }

    .checkout-btn {
        background-color: #3498db;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .checkout-btn:hover {
        background-color: #2980b9;
    }

    .add-course-btn {
        background-color: #1abc9c;
    }

    .add-course-btn:hover {
        background-color: #16a085;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .cart-item img {
            margin-bottom: 10px;
        }

        .cart-item-actions {
            margin-top: 10px;
        }
    }
</style>
</head>
<body>

<div class="super_container">

    <!-- Cart Container -->
    <div class="cart-container">
        <h2>Shopping Basket</h2>
        <div id="cartItemsContainer"></div>
        <div class="cart-total">
            <span class="cart-total-label">Subtotal:</span>
            <span class="cart-total-value" id="cartSubtotalValue">0.00</span>
        </div>
        <div class="cart-gst">
            <span class="cart-gst-label">GST:</span>
            <span class="cart-gst-value" id="cartGSTValue">0.00</span>
        </div>
        <div class="cart-final-total">
            <span class="cart-final-total-label">Total:</span>
            <span class="cart-final-total-value" id="cartFinalTotalValue">0.00</span>
        </div>
        <button class="checkout-btn" onclick="proceedToCheckout()">Proceed to Checkout</button>
        <button class="add-course-btn" onclick="addCourseToCart()">Add Another Course</button>
    </div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/scrollTo/jquery.scrollTo.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/cart_custom.js"></script>
<script>
$(document).ready(function () {
    let isCartEmpty = true;

    loadCartItems();

    function loadCartItems() {
        $.ajax({
            url: "../backend/courses_api",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ action: "get_cart_items" }),
            success: function (response) {
                if (response.success && Array.isArray(response.data)) {
                    if (response.data.length === 0) {
                        isCartEmpty = true;
                        $('#cartItemsContainer').html('<p>Your cart is currently empty.</p>');
                        $('#cartSubtotalValue').text(`0.00`);
                        $('#cartGSTValue').text(`0.00`);
                        $('#cartFinalTotalValue').text(`0.00`);
                        $('.checkout-btn').prop('disabled', true).css('background-color', '#ccc').css('cursor', 'not-allowed');
                    } else {
                        isCartEmpty = false;
                        $('.checkout-btn').prop('disabled', false).css('background-color', '#3498db').css('cursor', 'pointer');
                        displayCartItems(response.data);
                        calculateTotal(response.data);
                    }
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
        let container = $('#cartItemsContainer');
        container.empty();
        items.forEach(item => {
            let itemHtml = `
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.course_name}">
                    <div class="cart-item-details">
                        <div class="cart-item-title">${item.course_name}</div>
                        <div class="cart-item-description">${item.description}</div>
                        <div class="price-wrapper">
                            <div class="cart-item-price">₹${item.price}</div>
                            <div class="cart-item-offer-price">₹${item.offer_price}</div>
                        </div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="remove-from-cart-btn" onclick="removeFromCart(${item.id})">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            container.append(itemHtml);
        });
    }

    function calculateTotal(items) {
        let subtotal = items.reduce((sum, item) => sum + parseFloat(item.offer_price), 0);
        let gst = subtotal * 0.18; // 18% GST
        let finalTotal = subtotal + gst;
        $('#cartSubtotalValue').text(`₹${subtotal.toFixed(2)}`);
        $('#cartGSTValue').text(`₹${gst.toFixed(2)}`);
        $('#cartFinalTotalValue').text(`₹${finalTotal.toFixed(2)}`);
    }

    window.removeFromCart = function (itemId) {
        $.ajax({
            url: "../backend/courses_api.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ action: "remove_from_cart", item_id: itemId }),
            success: function (response) {
                if (response.success) {
                    loadCartItems();
                } else {
                    console.error("Error removing from cart:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    };

    window.proceedToCheckout = function () {
        if (isCartEmpty) {
            alert("Your cart is empty. Please add items before proceeding to checkout.");
        } else {
            window.location.href = 'checkout.php';
        }
    };

    window.addCourseToCart = function () {
        window.location.href = 'courses.php';
    };
});
</script>

</body>
</html>
