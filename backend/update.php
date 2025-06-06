<?php
// Include database connection
include("db_connect.php");

// Check if data is received
if(isset($_POST['id']) && isset($_POST['subject_name']) && isset($_POST['description'])) {
    $id = $_POST['id'];
    $subject_name = $_POST['subject_name'];
    $description = $_POST['description'];

    // Update query
    $query = "UPDATE subjects SET subject_name='$subject_name', description='$description' WHERE id=$id";
    
    if(mysqli_query($conn, $query)) {
        echo "Success";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
