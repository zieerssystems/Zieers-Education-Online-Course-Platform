<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require('db.php');

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT id, subject_id, course_name, description, author_name, price, offer_price, image FROM courses";
$result = $conn->query($sql);

$courses = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

echo json_encode($courses);
$conn->close();
?>
