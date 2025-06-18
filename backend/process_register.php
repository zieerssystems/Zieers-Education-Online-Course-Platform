<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Ensure passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Create an instance of MySqlDB
    $db = new MySqlDB();

    // Call the register method
    $result = $db->register($username, $password);

    if ($result === true) {
        echo 'true';
    } else {
        echo $result; // Display error message
    }
}
?>
