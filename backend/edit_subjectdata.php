<?php
include 'db.php';

// Get subject ID from URL
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM subjects WHERE id=$id");
$subject = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name = $_POST['subject_name'];
    $description = $_POST['description'];

    // Update query
    $stmt = $conn->prepare("UPDATE subjects SET name=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $subject_name, $description, $id);

    if ($stmt->execute()) {
        header("Location: add_subject.php"); // Redirect back after editing
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<form method="POST">
    <h2>Edit Subject</h2>
    
    <label>Subject Name:</label>
    <input type="text" name="subject_name" value="<?= $subject['NAME']; ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?= $subject['description']; ?></textarea>

    <button type="submit">Update Subject</button>
</form>
