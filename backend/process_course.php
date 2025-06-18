<?php
header("Content-Type: application/json");
require_once "db.php";

$db = new MySqlDB($servername, $username, $password, $database);

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Fetch subjects
if ($action === 'fetch_subjects') {
    $result = $db->fetchSubject();
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
    $requiredFields = ['courseName', 'description', 'authorName', 'subjectId'];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(["success" => false, "message" => "Missing value for: $field"]);
            exit;
        }
    }

    $courseName = trim($_POST['courseName']);
    $description = trim($_POST['description']);
    $authorName = trim($_POST['authorName']);
    $subjectId = intval($_POST['subjectId']);

    $price = isset($_POST['price']) ? max(0, floatval($_POST['price'])) : 0;
    $offerPrice = isset($_POST['offerPrice']) ? max(0, floatval($_POST['offerPrice'])) : 0;

    // Handle Image Upload
    $imageName = "";
    if (!empty($_FILES['courseImage']['name'])) {
        $imageName = time() . '_' . basename($_FILES['courseImage']['name']);
        $uploadDir = "uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($_FILES['courseImage']['tmp_name'], $uploadDir . $imageName)) {
            echo json_encode(["success" => false, "message" => "Failed to upload image"]);
            exit;
        }
    }

    $result = $db->addCourse($subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Course added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add course"]);
    }
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

    $price = isset($_POST['price']) ? max(0, floatval($_POST['price'])) : 0;
    $offerPrice = isset($_POST['offerPrice']) ? max(0, floatval($_POST['offerPrice'])) : 0;

    if ($db->courseNameExists($courseName, $id)) {
        echo json_encode(["success" => false, "message" => "This course name already exists under another subject!"]);
        exit;
    }

    $currentImage = $db->getCurrentImage($id);

    if (!empty($_FILES['courseImage']['name'])) {
        $newImageName = time() . '_' . basename($_FILES['courseImage']['name']);
        if (move_uploaded_file($_FILES['courseImage']['tmp_name'], "uploads/" . $newImageName)) {
            if (!empty($currentImage) && file_exists("uploads/" . $currentImage)) {
                unlink("uploads/" . $currentImage);
            }
            $imageName = $newImageName;
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload new image"]);
            exit;
        }
    } else {
        $imageName = $currentImage;
    }

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

    $imageName = $db->getCourseImage($id);

    if (!empty($imageName) && file_exists("uploads/" . $imageName)) {
        unlink("uploads/" . $imageName);
    }

    $result = $db->deleteCourse($id);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Course deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete course"]);
    }
    exit;
}

// Fetch courses
if ($action === 'fetch_courses') {
    $result = $db->fetchCourse();
    echo json_encode($result);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>
