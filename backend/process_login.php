<?php
session_start();
require_once 'db.php';

$db = new MySqlDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    $loginResult = $db->loginUser($inputUsername, $inputPassword);

    if ($loginResult === true) {
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['errorMessage'] = $loginResult;
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
