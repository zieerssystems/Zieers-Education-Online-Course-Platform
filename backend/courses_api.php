<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);
$action = $data['action'] ?? '';

error_log("Received action: " . $action);
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("Request data: " . json_encode($data));

require_once "db.php";
$db = new MySqlDB($servername, $username, $password, $database);
$baseImageUrl = '../backend/uploads/';

try {
    // Fetch Subjects
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'fetch_subjects') {
        $result = $db->fetchSubject();
        if ($result === false) {
            error_log("Failed to fetch subjects.");
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to fetch subjects."]);
            exit;
        }
        echo json_encode(["success" => true, "data" => $result]);
        exit;
    }

    // Fetch Courses
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'getCourses') {
        $subjectId = $data['subjectId'] ?? null;

        if ($subjectId !== null && !is_numeric($subjectId)) {
            error_log("Invalid subject ID provided: " . $subjectId);
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Invalid subject ID."]);
            exit;
        }

        if ($subjectId !== null) {
            $result = $db->fetchCourseById($subjectId);
        } else {
            $result = $db->fetchCourse();
        }

        if ($result === false) {
            error_log("Failed to fetch courses.");
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to fetch courses."]);
            exit;
        }

        foreach ($result as &$course) {
            if (!empty($course['image'])) {
                $course['image'] = $baseImageUrl . $course['image'];
            }
        }
        http_response_code(200);
        echo json_encode(["success" => true, "data" => $result]);
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

        if (empty($courseName) || !is_numeric($subjectId) || $subjectId <= 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Invalid input data."]);
            exit;
        }

        if ($action === 'addCourse') {
            $result = $db->addCourse($subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName);
        } else {
            $result = $db->updateCourse($courseId, $subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName);
        }

        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => ($action === 'addCourse') ? "Course added successfully." : "Course updated successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to execute statement."]);
        }
        exit;
    }

    // Add to Cart
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add_to_cart') {
        $courseId = $data['course_id'] ?? 0;
        $courseName = $data['course_name'] ?? '';

        if (empty($courseName) || !is_numeric($courseId) || $courseId <= 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Invalid course data."]);
            exit;
        }

        $result = $db->addToCart($courseId, $courseName);

        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Item added to cart successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to add item to cart."]);
        }
        exit;
    }

    // Get Cart Items
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'get_cart_items') {
        $result = $db->getCartItems();

        if ($result === false) {
            error_log("Failed to fetch cart items.");
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to fetch cart items."]);
            exit;
        }

        foreach ($result as &$item) {
            if (!empty($item['image'])) {
                $item['image'] = $baseImageUrl . $item['image'];
            }
        }
        http_response_code(200);
        echo json_encode(["success" => true, "data" => $result]);
        exit;
    }

    // Remove from Cart
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'remove_from_cart') {
        $itemId = $data['item_id'] ?? 0;

        if (!is_numeric($itemId) || $itemId <= 0) {
            error_log("Invalid item ID provided: " . $itemId);
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Invalid item ID."]);
            exit;
        }

        $result = $db->removeFromCart($itemId);

        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Item removed from cart successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to remove item from cart."]);
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

        error_log("Place Order Data: " . json_encode($formData));

        if (empty($firstName) || empty($lastName) || empty($city) || empty($phone) || empty($email) || empty($collegeName) || empty($currentSemester) || empty($hearAboutUs)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "All fields are required."]);
            exit;
        }

        $result = $db->placeOrder($firstName, $lastName, $city, $phone, $email, $collegeName, $currentSemester, $hearAboutUs);

        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Order placed successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to place order."]);
        }
        exit;
    }

    // Process Payment
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'process_payment') {
        $userId = $data['user_id'] ?? 0;
        $amount = $data['amount'] ?? 0.0;

        if (empty($userId) || $amount <= 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "User ID and a valid amount are required."]);
            exit;
        }

        $result = $db->processPayment($userId, $amount);

        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Payment processed successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to process payment."]);
        }
        exit;
    }

    // Clear Cart
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'clear_cart') {
        $result = $db->clearCart();

        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Cart cleared successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to clear cart."]);
        }
        exit;
    }

    else {
        error_log("Invalid request with action: " . $action);
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit;
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An unexpected error occurred: " . $e->getMessage()]);
    exit;
}
?>
