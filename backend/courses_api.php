<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Allow specific methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers

// Read raw data from the request body
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Retrieve action from the request body
$action = $data['action'] ?? '';

// Debugging: Log the action and request details
error_log("Received action: " . $action);
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("Request data: " . json_encode($data));

require_once "db.php";
$db = new MySqlDB();

// Base URL for images
$baseImageUrl = 'http://localhost/admin_panel/backend/uploads/';

try {
    // Fetch Subjects
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'fetch_subjects') {
        $result = $db->fetchSubject();
        echo json_encode(["success" => true, "data" => $result]);
        exit;
    }

    // Fetch Courses
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'getCourses') {
        $subjectId = $data['subjectId'] ?? null;

        if ($subjectId !== null) {
            $result = $db->fetchCourseByid($subjectId);
        } else {
            $result = $db->fetchCourse();
        }

        // Add base URL to image paths
        foreach ($result as &$course) {
            if (!empty($course['image'])) {
                $course['image'] = $baseImageUrl . $course['image'];
            }
        }

        echo json_encode(["success" => true, "data" => $result]);
        http_response_code(200); // OK
        exit;
    }

    // Add or Update Course
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'addCourse' || $action === 'updateCourse')) {
        $subjectId = $data['subjectId'] ?? 0;
        $courseId = $data['courseId'] ?? 0;
        $courseName = $data['courseName'] ?? '';
        $description = $data['description'] ?? '';
        $authorName = $data['authorName'] ?? '';
        $price = $data['price'] ?? 0.0;
        $offerPrice = $data['offerPrice'] ?? 0.0;
        $imageName = $data['imageName'] ?? '';

        if ($action === 'addCourse') {
            $result = $db->addCourse($subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName);
        } else {
            $result = $db->updateCourse($courseId, $subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName);
        }

        if ($result) {
            echo json_encode(["success" => true, "message" => ($action === 'addCourse') ? "Course added successfully." : "Course updated successfully."]);
            http_response_code(200); // OK
        } else {
            echo json_encode(["success" => false, "message" => "Failed to execute statement."]);
            http_response_code(500); // Internal Server Error
        }
        exit;
    }

    // Add to Cart
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add_to_cart') {
        $courseId = $data['course_id'] ?? 0;
        $courseName = $data['course_name'] ?? '';

        $result = $db->addToCart($courseId, $courseName);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Item added to cart successfully."]);
            http_response_code(200); // OK
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add item to cart."]);
            http_response_code(500); // Internal Server Error
        }
        exit;
    }

    // Get Cart Items
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'get_cart_items') {
        $result = $db->getCartItems();

        // Add base URL to image paths
        foreach ($result as &$item) {
            if (!empty($item['image'])) {
                $item['image'] = $baseImageUrl . $item['image'];
            }
        }

        echo json_encode(["success" => true, "data" => $result]);
        http_response_code(200); // OK
        exit;
    }

    // Remove from Cart
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'remove_from_cart') {
        $itemId = $data['item_id'] ?? 0;

        $result = $db->removeFromCart($itemId);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Item removed from cart successfully."]);
            http_response_code(200); // OK
        } else {
            echo json_encode(["success" => false, "message" => "Failed to remove item from cart."]);
            http_response_code(500); // Internal Server Error
        }
        exit;
    }

    // Place Order
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'place_order') {
        $formData = $data['data'] ?? [];
        $firstName = $formData['firstName'] ?? '';
        $lastName = $formData['lastName'] ?? '';
        $city = $formData['city'] ?? '';
        $phone = $formData['phone'] ?? '';
        $email = $formData['email'] ?? '';
        $collegeName = $formData['collegeName'] ?? '';
        $currentSemester = $formData['currentSemester'] ?? '';
        $hearAboutUs = $formData['hearAboutUs'] ?? '';

        // Log form data for debugging
        error_log("Place Order Data: " . json_encode($formData));

        // Validate required fields
        if (empty($firstName) || empty($lastName) || empty($city) || empty($phone) || empty($email) || empty($collegeName) || empty($currentSemester) || empty($hearAboutUs)) {
            echo json_encode(["success" => false, "message" => "All fields are required."]);
            http_response_code(400); // Bad Request
            exit;
        }

        $result = $db->placeOrder($firstName, $lastName, $city, $phone, $email, $collegeName, $currentSemester, $hearAboutUs);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Order placed successfully."]);
            http_response_code(200); // OK
        } else {
            echo json_encode(["success" => false, "message" => "Failed to place order."]);
            http_response_code(500); // Internal Server Error
        }
        exit;
    }

    // Process Payment
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'process_payment') {
        $userId = $data['user_id'] ?? 0;
        $amount = $data['amount'] ?? 0.0;

        // Validate required fields
        if (empty($userId) || $amount <= 0) {
            echo json_encode(["success" => false, "message" => "User ID and a valid amount are required."]);
            http_response_code(400); // Bad Request
            exit;
        }

        $result = $db->processPayment($userId, $amount);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Payment processed successfully."]);
            http_response_code(200); // OK
        } else {
            echo json_encode(["success" => false, "message" => "Failed to process payment."]);
            http_response_code(500); // Internal Server Error
        }
        exit;
    }

    else {
        // Invalid request
        error_log("Invalid request with action: " . $action);
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        http_response_code(400); // Bad Request
        exit;
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "An unexpected error occurred."]);
    http_response_code(500); // Internal Server Error
    exit;
}
?>
