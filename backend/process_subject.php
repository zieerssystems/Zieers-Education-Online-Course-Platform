<?php
header("Content-Type: application/json");
require_once "db.php";

$db = new MySqlDB($servername, $username, $password, $database);

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    if (!empty($_POST['subject_name']) && !empty($_POST['description'])) {
        $subjectName = trim($_POST['subject_name']);
        $description = trim($_POST['description']);

        $result = $db->addSubject($subjectName, $description);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Subject added successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add subject"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Please fill in all required fields"]);
    }
}

// Updated Subject Name & Description with Partial Edit Allowed
elseif ($action === 'update') {
    if (!empty($_POST['id']) && !empty($_POST['subject_name']) && !empty($_POST['description']) && !empty($_POST['original_name'])) {
        $id = intval($_POST['id']);
        $subjectName = trim($_POST['subject_name']);
        $originalName = trim($_POST['original_name']);
        $description = trim($_POST['description']);

        // Allow minor modifications but prevent complete renaming
        if (strcasecmp($subjectName, $originalName) !== 0) {
            similar_text($subjectName, $originalName, $percentSimilarity);

            // Allow small changes like pluralization or spelling fixes (80% threshold)
            if ($percentSimilarity < 80) {
                echo json_encode(["success" => false, "message" => "Subject Name is already Exists!"]);
                exit;
            }
        }

        $result = $db->updateSubject($id, $subjectName, $description);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Subject updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update subject"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Please provide all required fields"]);
    }
}

// Delete Subject
elseif ($action === 'delete') {
    if (!empty($_POST['id'])) {
        $id = intval($_POST['id']);

        $result = $db->deleteSubject($id);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Subject deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete subject"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Please provide the subject ID"]);
    }
}

// Fetch Subjects
else {
    $result = $db->fetchSubject();
    echo json_encode($result);
}
?>
