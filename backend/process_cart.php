<?php
require('db.php');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_id = $_POST['course_id'];

$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
    $insert = $conn->prepare("INSERT INTO cart (course_id, course_name, price) VALUES (?, ?, ?)");
    $insert->bind_param("isd", $course['id'], $course['course_name'], $course['price']);
    $insert->execute();

    echo json_encode(["status" => "success", "message" => "Course added to cart"]);
} else {
    echo json_encode(["status" => "error", "message" => "Course not found"]);
}
?>
