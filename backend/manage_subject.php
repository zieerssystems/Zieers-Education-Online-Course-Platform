<?php
header("Content-Type: application/json");

require('db.php');
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    if (!empty($_POST['subject_name']) && !empty($_POST['description'])) {
        $subjectName = trim($_POST['subject_name']);
        $description = trim($_POST['description']);

        $stmt = $conn->prepare("INSERT INTO subjects (subject_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $subjectName, $description);
        $stmt->execute();

        echo json_encode(["message" => "Subject added successfully"]);
        $stmt->close();
    } else {
        echo json_encode(["message" => "Please fill in all required fields"]);
    }
}

// ** Updated Subject Name & Description with Partial Edit Allowed **
elseif ($action === 'update') {
    if (!empty($_POST['id']) && !empty($_POST['subject_name']) && !empty($_POST['description']) && !empty($_POST['original_name'])) {
        $id = intval($_POST['id']);
        $subjectName = trim($_POST['subject_name']);
        $originalName = trim($_POST['original_name']);
        $description = trim($_POST['description']);

        // Allow minor modifications but restrict full name change
        similar_text($subjectName, $originalName, $percentSimilarity);
        if ($percentSimilarity < 70) {  // If similarity is less than 70%, deny update
            echo json_encode(["message" => "Only small modifications to the subject name are allowed."]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE subjects SET subject_name=?, description=? WHERE id=?");
        $stmt->bind_param("ssi", $subjectName, $description, $id);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Subject updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update subject"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["message" => "Please provide all required fields"]);
    }
}

// ** Delete Subject **
elseif ($action === 'delete') {
    if (!empty($_POST['id'])) {
        $id = intval($_POST['id']);

        $stmt = $conn->prepare("DELETE FROM subjects WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(["message" => "Subject deleted successfully"]);
        $stmt->close();
    }
}

// ** Fetch Subjects **
else {
    $result = $conn->query("SELECT * FROM subjects");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

$conn->close();
?>
