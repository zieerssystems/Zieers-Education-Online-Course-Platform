<?php
session_start();
include '../backend/db.php';

$db = new MySqlDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentId = $_POST['payment_id'];
    $userId = $_SESSION['user_id']; // Assuming you have user_id stored in session

    // Update payment status
    $query = "UPDATE orders SET payment_status = 'completed', payment_id = ? WHERE user_id = ?";
    $stmt = $db->conn->prepare($query);
    $stmt->bind_param("si", $paymentId, $userId);
    $stmt->execute();

    // Clear cart
    $query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $db->conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
