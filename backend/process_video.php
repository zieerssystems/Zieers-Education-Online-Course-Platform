<?php
header('Content-Type: application/json');

require_once "db.php";
$db = new MySqlDB();

// Ensure uploads directory exists
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Retrieve action from request
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Debugging: Log the action and request details
error_log("Received action: " . $action);
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("POST data: " . json_encode($_POST));
error_log("GET data: " . json_encode($_GET));
error_log("FILES data: " . json_encode($_FILES));

try {
    // Fetch Subjects
    if ($action === 'fetch_subjects') {
        $result = $db->fetchSubject();
        echo json_encode(["success" => true, "data" => $result]);
        exit;
    }

    // Fetch Courses based on selected subject
    elseif ($action === 'getCourses' && isset($_GET['subjectId'])) {
        $subjectId = intval($_GET['subjectId']);
        $result = $db->fetchCourseByid($subjectId);
        echo json_encode(["success" => true, "data" => $result]);
        exit;
    }

    // Fetch Videos
    elseif ($action === 'getVideos') {
        $result = $db->fetchVideo();
        echo json_encode(["success" => true, "data" => $result]);
        exit;
    }

    // Add or Update Video
    elseif ($action === 'addVideo' || $action === 'updateVideo') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "message" => "Invalid request method. POST required for add/update video."]);
            exit;
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $subjectId = intval($_POST['subjectId'] ?? 0);
        $courseId = intval($_POST['courseId'] ?? 0);
        $videoName = $_POST['videoName'] ?? '';
        $videoLink = $_POST['videoLink'] ?? '';
        $description = $_POST['description'] ?? '';
        $pdfPath = '';

        if (empty($subjectId) || empty($courseId) || empty($videoName) || empty($videoLink) || empty($description)) {
            echo json_encode(["success" => false, "message" => "All fields are required."]);
            exit;
        }

        // Fetch existing PDF if updating
        $existingPdf = '';
        if ($action === 'updateVideo' && $id > 0) {
            $existingPdf = $db->getVideoPdfPath($id);
        }

        // Handle PDF Upload
        if (!empty($_FILES['pdfFile']['name'])) {
            if ($_FILES["pdfFile"]["error"] !== UPLOAD_ERR_OK) {
                echo json_encode(["success" => false, "message" => "File upload error: " . $_FILES["pdfFile"]["error"]]);
                exit;
            }

            $fileType = mime_content_type($_FILES["pdfFile"]["tmp_name"]);
            if ($fileType !== "application/pdf") {
                echo json_encode(["success" => false, "message" => "Invalid file type. Only PDFs are allowed."]);
                exit;
            }

            // Delete old PDF if exists
            if ($action === 'updateVideo' && !empty($existingPdf) && file_exists($uploadDir . $existingPdf)) {
                unlink($uploadDir . $existingPdf);
            }

            // Move new file
            $pdfName = time() . "_" . basename($_FILES["pdfFile"]["name"]);
            $pdfTarget = $uploadDir . $pdfName;

            if (!move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $pdfTarget)) {
                echo json_encode(["success" => false, "message" => "Failed to move uploaded file. Check folder permissions."]);
                exit;
            }

            $pdfPath = $pdfName;
        } else {
            $pdfPath = $existingPdf;
        }

        // Insert or Update Database
        if ($action === 'addVideo') {
            $result = $db->addVideo($subjectId, $courseId, $videoName, $videoLink, $description, $pdfPath);
        } else {
            $result = $db->updateVideo($id, $subjectId, $courseId, $videoName, $videoLink, $description, $pdfPath);
        }

        if ($result) {
            echo json_encode(["success" => true, "message" => ($action === 'addVideo') ? "Video added successfully." : "Video updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: Failed to execute statement."]);
        }
        exit;
    }

    // Delete Video
    elseif ($action === 'deleteVideo' && isset($_POST['id'])) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "message" => "Invalid request method. POST required for delete video."]);
            exit;
        }

        $id = intval($_POST['id']);

        if ($db->deleteVideo($id)) {
            echo json_encode(["success" => true, "message" => "Video deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting video"]);
        }
        exit;
    } else {
        // Debugging: Log invalid request details
        error_log("Invalid request with action: " . $action);
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit;
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "An unexpected error occurred."]);
    exit;
}
?>
