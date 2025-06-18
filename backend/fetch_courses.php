<?php
// Connect to the database
require('db.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'subject_id' is set in the GET request
if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];
    
    // Fetch courses related to the selected subject
    $query = "SELECT * FROM courses WHERE subject_id = '$subject_id'";
    $result = $conn->query($query);

    // Start building the dropdown options
    $options = "<option value=''>-- Select Course --</option>";
    
    while ($row = $result->fetch_assoc()) {
        // Ensure correct column name: 'name' or 'course_name'
        $course_name = isset($row['course_name']) ? $row['course_name'] : $row['name'];
        $options .= "<option value='{$row['id']}'>{$course_name}</option>";
    }

    echo $options;
}

// Close the database connection
$conn->close();
?>
