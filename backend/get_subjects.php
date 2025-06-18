<?php
require('db.php');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT id, subject_name FROM subjects";
$result = $conn->query($query);

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

echo json_encode($subjects);
$conn->close();
?>
