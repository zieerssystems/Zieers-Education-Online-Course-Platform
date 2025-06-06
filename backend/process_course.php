<?php
require_once "db.php";
$db = new MySqlDB();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Fetch subjects
if ($action === 'fetch_subjects') {
    $result = $db->fetchSubject();
    //$result = $conn->query("SELECT id, subject_name FROM subjects");
    echo json_encode($result);
    exit;
}

// Ensure subjectId is set and valid before proceeding with course actions
if (in_array($action, ['add', 'update'])) {
    if (!isset($_POST['subjectId']) || !is_numeric($_POST['subjectId'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing subjectId']);
        exit;
    }
    $subjectId = intval($_POST['subjectId']);
}

if ($action === 'add') {
    error_log("Add action triggered");

    $requiredFields = ['courseName', 'description', 'authorName', 'subjectId'];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            error_log("Missing value for: $field");
            echo json_encode(["success" => false, "message" => "Missing value for: $field"]);
            exit;
        }
    }

    $courseName = trim($_POST['courseName']);
    $description = trim($_POST['description']);
    $authorName = trim($_POST['authorName']);
    $subjectId = intval($_POST['subjectId']);

    // Ensure price & offerPrice are never negative
    $price = isset($_POST['price']) ? max(0, floatval($_POST['price'])) : 0;
    $offerPrice = isset($_POST['offerPrice']) ? max(0, floatval($_POST['offerPrice'])) : 0;

    // Handle Image Upload
    $imageName = "";
    if (!empty($_FILES['courseImage']['name'])) {
        $imageName = time() . '_' . basename($_FILES['courseImage']['name']);
        $uploadDir = "uploads/";

        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($_FILES['courseImage']['tmp_name'], $uploadDir . $imageName)) {
            error_log("Failed to upload image");
            echo json_encode(["success" => false, "message" => "Failed to upload image"]);
            exit;
        }
    }

    // // Check for duplicate course name
    // $checkQuery = $db->conn->prepare("SELECT id FROM courses WHERE course_name = ? AND subject_id = ?");
    // $checkQuery->bind_param("si", $courseName, $subjectId);
    // $checkQuery->execute();
    // $checkQuery->store_result();

    // if ($checkQuery->num_rows > 0) {
    //     error_log("Duplicate course name found");
    //     echo json_encode(["success" => false, "message" => "This course name already exists under another subject!"]);
    // } else {
        // Add the course using the addCourse method
        $result = $db->addCourse($subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName);

        if ($result) {
            error_log("Course added successfully");
            echo json_encode(["success" => true, "message" => "Course added successfully"]);
        } else {
            error_log("Failed to add course");
            echo json_encode(["success" => false, "message" => "Failed to add course"]);
        }
    // }
    //$checkQuery->close();
    exit;
}

// Update course

if ($action === 'update') {
    if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(["success" => false, "message" => "Invalid course ID"]);
        exit;
    }

    $id = intval($_POST['id']);
    $courseName = trim($_POST['courseName']);
    $description = trim($_POST['description']);
    $authorName = trim($_POST['authorName']);
    $subjectId = intval($_POST['subjectId']);

    error_log("courseName = " . $courseName);

    // Ensure price & offerPrice are never negative
    $price = isset($_POST['price']) ? max(0, floatval($_POST['price'])) : 0;
    $offerPrice = isset($_POST['offerPrice']) ? max(0, floatval($_POST['offerPrice'])) : 0;

    // Check if course name already exists for another subject
    if ($db->courseNameExists($courseName, $id)) {
        echo json_encode(["success" => false, "message" => "This course name already exists under another subject!"]);
        exit;
    }

    // Retrieve the current image
    $currentImage = $db->getCurrentImage($id);

    // Handle Image Update
    if (!empty($_FILES['courseImage']['name'])) {
        $newImageName = time() . '_' . basename($_FILES['courseImage']['name']);
        if (move_uploaded_file($_FILES['courseImage']['tmp_name'], "uploads/" . $newImageName)) {
            // Delete old image if it exists
            if (!empty($currentImage) && file_exists("uploads/" . $currentImage)) {
                unlink("uploads/" . $currentImage);
            }
            $imageName = $newImageName;
            error_log("imageName".imageName);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload new image"]);
            exit;
        }
    } else {
        // Keep the existing image
        $imageName = $currentImage;
    }

    // Update course data using the updateCourse method
    $result = $db->updateCourse($id, $subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Course updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update course"]);
    }
    exit;
}



// Delete course


if ($action === 'delete') {
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(["success" => false, "message" => "Invalid course ID"]);
        exit;
    }

    $id = intval($_POST['id']);

    // Retrieve image filename to delete it
    $imageName = $db->getCourseImage($id);

    if (!empty($imageName) && file_exists("uploads/" . $imageName)) {
        unlink("uploads/" . $imageName);
    }

    // Delete course data using the deleteCourse method
    $result = $db->deleteCourse($id);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Course deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete course"]);
    }
    exit;
}

else {
    $result = $db->fetchCourse();
    echo json_encode($result);
}


?>
