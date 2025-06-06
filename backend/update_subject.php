<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_panel";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$subject_name = $_POST['subject_name'];
$description = $_POST['description'];

$sql = "UPDATE subjects SET subject_name='$subject_name', description='$description' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Subject updated successfully!";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
