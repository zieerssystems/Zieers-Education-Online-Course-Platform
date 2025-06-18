<!DOCTYPE html>
<html lang="en">
<head>
    <title>Course - Courses</title>
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
        /* Your CSS styles here */
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        .course-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
            transition: transform 0.3s;
            width: 300px;
            display: flex;
            flex-direction: column;
            margin-right: 20px;
        }
        .course-card:hover {
            transform: translateY(-10px);
        }
        .course-image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        .course-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .course-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .course-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .course-description-link {
            background-color: transparent;
            border: 1px solid #f4f4f9;
            color: #666;
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            width: 100%;
            text-align: left;
            display: inline-block;
        }
        .course-description-link:hover {
            background-color: #f4f4f9;
            color: #333;
        }
        .course-meta {
            font-size: 0.875rem;
            color: #999;
        }
        .price-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .price-box, .offer-price-box {
            background-color: rgb(56, 64, 167);
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-right: 10px;
        }
        .price-box {
            text-decoration: line-through;
        }
        .offer-price-box {
            background-color: rgb(39, 40, 69);
        }
        .course-author {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .course-author img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .course-container {
            margin-top: 20px;
        }
        .course-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .course-container select, .course-container button {
            margin-bottom: 20px;
        }
        .course_boxes {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }
        #fetchCoursesButton {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #fetchCoursesButton:hover {
            background-color: #218838;
        }
        .add-to-cart-btn {
            background-color: #ff9800;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .add-to-cart-btn:hover {
            background-color: #e68600;
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
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
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
                    <img src="images/logo.png" alt="">
                    <span>course</span>
                </div>
            </div>
            <!-- Main Navigation -->
            <nav class="main_nav_container">
                <div class="main_nav">
                    <ul class="main_nav_list">
                        <li class="main_nav_item"><a href="index.html">Home</a></li>
                        <li class="main_nav_item"><a href="#">About Us</a></li>
                        <!-- Dropdown for Courses -->
                        <li class="main_nav_item dropdown">
                            <a href="#" class="dropbtn">Courses</a>
                            <div class="dropdown-content">
                                <a href="orders.php">My Courses</a>
                            </div>
                        </li>
                        <li class="main_nav_item"><a href="elements.html">Elements</a></li>
                        <li class="main_nav_item"><a href="news.html">News</a></li>
                        <li class="main_nav_item"><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="header_side d-flex flex-row justify-content-center align-items-center">
            <img src="images/phone-call.svg" alt="">
            <span>+91 93410 59619</span>
            <div class="cart-icon" onclick="viewCart()">
                <i class="fas fa-shopping-cart"></i>
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
                    <li class="menu_item menu_mm"><a href="#">Courses</a></li>
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
        <div class="home_content">
            <h1>Courses</h1>
        </div>
    </div>

    <!-- Popular -->
    <div class="popular page_section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title text-center">
                        <h1>Popular Courses</h1>
                    </div>
                </div>
            </div>
            <div class="row course_boxes">
                <div class="course-container">
                    <h2>Course List</h2>
                    <select id="subjectSelect" class="form-control">
                        <option value="">Select Subject</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                    <button id="fetchCoursesButton" class="btn btn-primary">Fetch Courses</button>
                    <h3>Available Courses</h3>
                    <div id="courseCardsContainer" class="course_boxes"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <!-- Newsletter -->
            <div class="newsletter">
                <div class="row">
                    <div class="col">
                        <div class="section_title text-center">
                            <h1>Subscribe to newsletter</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <div class="newsletter_form_container mx-auto">
                            <form action="post">
                                <div class="newsletter_form d-flex flex-md-row flex-column flex-xs-column align-items-center justify-content-center">
                                    <input id="newsletter_email" class="newsletter_email" type="email" placeholder="Email Address" required="required" data-error="Valid email is required.">
                                    <button id="newsletter_submit" type="submit" class="newsletter_submit_btn trans_300" value="Submit">Subscribe</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Content -->
            <div class="footer_content">
                <div class="row">
                    <!-- Footer Column - About -->
                    <div class="col-lg-3 footer_col">
                        <!-- Logo -->
                        <div class="logo_container">
                            <div class="logo">
                                <img src="images/logo.png" alt="">
                                <span>course</span>
                            </div>
                        </div>
                        <p class="footer_about_text">Education fuels the vision that shapes tomorrow’s world. It builds the strength within, where dreams are unfurled. With knowledge as our compass, we rise and refine, unlocking human potential, one thought at a time.</p>
                    </div>
                    <!-- Footer Column - Menu -->
                    <div class="col-lg-3 footer_col">
                        <div class="footer_column_title">Menu</div>
                        <div class="footer_column_content">
                            <ul>
                                <li class="footer_list_item"><a href="index.html">Home</a></li>
                                <li class="footer_list_item"><a href="#">About Us</a></li>
                                <li class="footer_list_item"><a href="#">Courses</a></li>
                                <li class="footer_list_item"><a href="news.html">News</a></li>
                                <li class="footer_list_item"><a href="contact.html">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Footer Column - Useful Links -->
                    <div class="col-lg-3 footer_col">
                        <div class="footer_column_title">Useful Links</div>
                        <div class="footer_column_content">
                            <ul>
                                <li class="footer_list_item"><a href="#">Testimonials</a></li>
                                <li class="footer_list_item"><a href="#">FAQ</a></li>
                                <li class="footer_list_item"><a href="#">Community</a></li>
                                <li class="footer_list_item"><a href="#">Campus Pictures</a></li>
                                <li class="footer_list_item"><a href="#">Tuitions</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Footer Column - Contact -->
                    <div class="col-lg-3 footer_col">
                        <div class="footer_column_title">Contact</div>
                        <div class="footer_column_content">
                            <ul>
                                <li class="footer_contact_item">
                                    <div class="footer_contact_icon">
                                        <img src="images/placeholder.svg" alt="https://www.flaticon.com/authors/lucy-g">
                                    </div>
                                    Babusapalya Main Road, Bengaluru, Karnataka 560043, India
                                </li>
                                <li class="footer_contact_item">
                                    <div class="footer_contact_icon">
                                        <img src="images/smartphone.svg" alt="https://www.flaticon.com/authors/lucy-g">
                                    </div>
                                    +91 93410 59619
                                </li>
                                <li class="footer_contact_item">
                                    <div class="footer_contact_icon">
                                        <img src="images/envelope.svg" alt="https://www.flaticon.com/authors/lucy-g">
                                    </div>
                                    hr@zieers.com
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Copyright -->
            <div class="footer_bar d-flex flex-column flex-sm-row align-items-center">
                <div class="footer_copyright">
                   <span id="copyright">
    Copyright &copy; All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
</span>

<script>
    document.getElementById('copyright').innerHTML += new Date().getFullYear();
</script>

                </div>
                <div class="footer_social ml-sm-auto">
                    <ul class="menu_social">
                        <li class="menu_social_item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        <li class="menu_social_item"><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                        <li class="menu_social_item"><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li class="menu_social_item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li class="menu_social_item"><a href="#"><i class="fab fa-twitter"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
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
    loadSubjects();

    function loadSubjects() {
        $.ajax({
            url: "../backend/courses_api.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ action: "fetch_subjects" }),
            success: function (response) {
                console.log("Subjects fetched:", response);
                if (response.success && Array.isArray(response.data)) {
                    let subjectSelect = $('#subjectSelect');
                    subjectSelect.empty().append('<option value="">Select Subject</option>');
                    response.data.forEach(subject => subjectSelect.append(`<option value="${subject.id}">${subject.subject_name}</option>`));
                } else {
                    console.error("Error fetching subjects:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    $('#fetchCoursesButton').click(function () {
        let subjectId = $('#subjectSelect').val();
        let data = { action: "getCourses" };

        if (subjectId) {
            data.subjectId = subjectId;
        }

        $.ajax({
            url: "../backend/courses_api.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (response) {
                console.log("Courses fetched:", response);
                if (response.success && Array.isArray(response.data)) {
                    displayCourses(response.data);
                } else {
                    console.error("Error fetching courses:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    });

    function displayCourses(courses) {
        let container = $('#courseCardsContainer');
        container.empty();
        courses.forEach(course => {
            let cardHtml = `
                <div class="course-card">
                    <div class="course-image-container">
                        ${course.image ? `<img src="${course.image}" alt="${course.course_name}" class="course-image">` : '<p>No Image</p>'}
                    </div>
                    <div class="course-content">
                        <h4 class="course-title">${course.course_name}</h4>
                        <a class="course-description-link" href="course_details.php?id=${course.id}">${course.description}</a>
                        <div class="course-author">
                            <img src="images/avatar.jpg" alt="Avatar">
                            <span>${course.author_name}</span>
                        </div>
                        <div class="price-container">
                            <div class="price-box">₹${course.price}</div>
                            <div class="offer-price-box">₹${course.offer_price}</div>
                        </div>
                        <button class="add-to-cart-btn" onclick="addToCart(${course.id}, '${course.course_name.replace(/'/g, "\\'")}')">Add to Cart</button>
                    </div>
                </div>
            `;
            container.append(cardHtml);
        });
    }

    window.addToCart = function (courseId, courseName) {
        $.ajax({
            url: "../backend/courses_api.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ action: "add_to_cart", course_id: courseId, course_name: courseName }),
            success: function (response) {
                if (response.success) {
                    alert(`${courseName} has been added to your cart.`);
                    window.location.href = 'cart.php';
                } else {
                    alert("Failed to add to cart: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                alert("An error occurred while adding to cart.");
            }
        });
    };

    window.viewCart = function () {
        window.location.href = 'cart.php';
    };
});
</script>

</body>
</html>
