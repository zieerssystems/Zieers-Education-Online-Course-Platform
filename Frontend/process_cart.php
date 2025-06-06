<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_to_cart'])) {
    $course_id = $_POST['course_id'];

    // Initialize cart session if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Avoid duplicates
    if (!in_array($course_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $course_id;
    }

    header("Location: cart.php");
    exit();
}
