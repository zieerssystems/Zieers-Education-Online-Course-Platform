<?php
include 'db.php'; // Ensure correct path

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Prevent SQL injection

    // Debugging: Check if ID is received
    echo "Deleting subject with ID: " . htmlspecialchars($id) . "<br>";

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Subject deleted successfully. Redirecting...";
        header("Refresh: 2; URL=add_subject.php"); // Redirect after 2 seconds
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
