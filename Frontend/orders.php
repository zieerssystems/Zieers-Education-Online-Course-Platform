<?php
session_start();
include '../backend/db.php';

$db = new MySqlDB();
$mergedData = $db->getMergedOrders();

$baseImageUrl = 'http://localhost/admin_panel/backend/uploads/';
$defaultImage = 'default_course.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Course - My Courses</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Course Project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/courses_styles.css">
    <link rel="stylesheet" type="text/css" href="styles/courses_responsive.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .course-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .course-image-container {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        .course-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        .course-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .course-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .course-description {
            font-size: 1rem;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .course-author {
            font-size: 1rem;
            color: #666;
            margin-bottom: 15px;
        }
        .start-button {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 10px;
            text-align: center;
        }
        .start-button:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }
        .course-container {
            margin-top: 20px;
        }
        .course-container h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .course-container select, .course-container button {
            margin-bottom: 20px;
        }
        .course_boxes {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .course_boxes .col-md-4 {
            flex: 0 0 auto;
            width: 30%;
        }
        .cart-icon {
            position: relative;
            display: inline-block;
            margin-right: 20px;
            cursor: pointer;
        }
        .cart-icon .fa-shopping-cart {
            font-size: 24px;
            color: #3498db;
        }
        .cart-icon .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header_content {
            display: flex;
            align-items: center;
        }
        .main_nav_container {
            margin-left: 20px;
        }
        .main_nav_list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
        }
        .main_nav_item {
            margin-right: 20px;
        }
        .main_nav_item a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            transition: color 0.3s;
        }
        .main_nav_item a:hover {
            color: #007bff;
        }
        .header_side {
            display: flex;
            align-items: center;
        }
        .header_side img {
            margin-right: 10px;
        }
        .header_side span {
            margin-right: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 40px 0;
        }
        .footer_content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .footer_col {
            width: calc(100% / 4 - 30px);
            margin-bottom: 30px;
        }
        .footer_column_title {
            font-size: 1.25rem;
            color: #333;
            margin-bottom: 15px;
        }
        .footer_column_content ul {
            list-style: none;
            padding: 0;
        }
        .footer_column_content ul li {
            margin-bottom: 10px;
        }
        .footer_column_content ul li a {
            color: #666;
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer_column_content ul li a:hover {
            color: #007bff;
        }
        .footer_contact_item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .footer_contact_item img {
            margin-right: 10px;
        }
        .footer_contact_item span {
            color: #666;
        }
        .footer_bar {
            background-color: #fff;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer_copyright {
            color: #666;
        }
        .footer_social {
            display: flex;
            align-items: center;
        }
        .footer_social ul {
            list-style: none;
            padding: 0;
            display: flex;
        }
        .footer_social ul li {
            margin-right: 15px;
        }
        .footer_social ul li a {
            color: #666;
            transition: color 0.3s;
        }
        .footer_social ul li a:hover {
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="super_container">

    <!-- Header -->
    <header class="header d-flex flex-row">
        <div class="header_content d-flex flex-row align-items-center">
            <!-- Logo -->
            <div class="logo_container">
                <div class="logo">
                    <img src="images/logo.png" alt="Logo">
                    <span>course</span>
                </div>
            </div>
            <!-- Main Navigation -->
            <nav class="main_nav_container">
                <div class="main_nav">
                    <ul class="main_nav_list">
                        <li class="main_nav_item"><a href="index.html">Home</a></li>
                        <li class="main_nav_item"><a href="#">About Us</a></li>
                        <li class="main_nav_item"><a href="courses.php">Courses</a></li>
                        <li class="main_nav_item"><a href="elements.html">Elements</a></li>
                        <li class="main_nav_item"><a href="news.html">News</a></li>
                        <li class="main_nav_item"><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="header_side d-flex flex-row justify-content-center align-items-center">
            <img src="images/phone-call.svg" alt="Phone Icon">
            <span>+91 93410 59619</span>
            <div class="cart-icon" onclick="viewCart()">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cartCount">0</span>
            </div>
        </div>
        <!-- Hamburger -->
        <div class="hamburger_container">
            <i class="fas fa-bars trans_200"></i>
        </div>
    </header>

    <!-- Menu -->
    <div class="menu_container menu_mm">
        <!-- Menu Close Button -->
        <div class="menu_close_container">
            <div class="menu_close"></div>
        </div>
        <!-- Menu Items -->
        <div class="menu_inner menu_mm">
            <div class="menu menu_mm">
                <ul class="menu_list menu_mm">
                    <li class="menu_item menu_mm"><a href="index.html">Home</a></li>
                    <li class="menu_item menu_mm"><a href="#">About us</a></li>
                    <li class="menu_item menu_mm"><a href="courses.php">Courses</a></li>
                    <li class="menu_item menu_mm"><a href="elements.html">Elements</a></li>
                    <li class="menu_item menu_mm"><a href="news.html">News</a></li>
                    <li class="menu_item menu_mm"><a href="contact.html">Contact</a></li>
                </ul>
                <!-- Menu Social -->
                <div class="menu_social_container menu_mm">
                    <ul class="menu_social menu_mm">
                        <li class="menu_social_item menu_mm"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        <li class="menu_social_item menu_mm"><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                        <li class="menu_social_item menu_mm"><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li class="menu_social_item menu_mm"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li class="menu_social_item menu_mm"><a href="#"><i class="fab fa-twitter"></i></a></li>
                    </ul>
                </div>
                <div class="menu_copyright menu_mm">Colorlib All rights reserved</div>
            </div>
        </div>
    </div>

    <!-- Home -->
    <div class="home">
        <div class="home_background_container prlx_parent">
            <div class="home_background prlx" style="background-image:url(images/std.jpg)"></div>
        </div>
    </div>

    <!-- Popular -->
    <div class="popular page_section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title text-center">
                        <h1>Purchased Courses</h1>
                    </div>
                </div>
            </div>
            <div class="row course_boxes">
                <?php if (!empty($mergedData)): ?>
                    <?php foreach ($mergedData as $order): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="course-card">
                                <div class="course-image-container">
                                    <?php
                                    $imageFile = !empty($order['image']) ? $order['image'] : $defaultImage;
                                    $imagePath = __DIR__ . '/../backend/uploads/' . $imageFile;
                                    $imageUrl = $baseImageUrl . $imageFile;

                                    if (!file_exists($imagePath)) {
                                        $imageUrl = $baseImageUrl . $defaultImage;
                                    }
                                    ?>
                                    <img src="<?= $imageUrl ?>" alt="<?= htmlspecialchars($order['course_name']) ?>" class="course-image">
                                </div>
                                <div class="course-content">
                                    <h4 class="course-title"><?= htmlspecialchars($order['course_name']) ?></h4>
                                    <div class="course-author">
                                        <strong>Author:</strong> <?= htmlspecialchars($order['author_name']) ?>
                                    </div>
                                    <div class="course-description">
                                        <?= htmlspecialchars($order['description']) ?>
                                    </div>
                                    <button class="start-button" onclick="startCourse(<?= $order['course_id'] ?>)">Start</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-12 text-center">
                        <p>No orders found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

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
<script src="js/courses_custom.js"></script>
<script>
$(document).ready(function () {
    // Update cart count
    function updateCartCount() {
        $.ajax({
            url: "http://localhost/admin_panel/backend/courses_api",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ action: "get_cart_count" }),
            success: function (response) {
                if (response.success) {
                    $('#cartCount').text(response.count);
                } else {
                    console.error("Error getting cart count:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // Initial cart count update
    updateCartCount();

    // View cart details
    window.viewCart = function () {
        window.location.href = 'cart.php';
    };

    // Start course function
    window.startCourse = function (courseId) {
        // Redirect to the videolink.php page with the course ID
        window.location.href = 'videolink.php?id=' + courseId;
    };
});
</script>

</body>
</html>
