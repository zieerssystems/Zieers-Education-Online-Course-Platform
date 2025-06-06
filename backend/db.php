<?php
class MySqlDB {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "testdb";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            error_log("Connection failed: " . $this->conn->connect_error);
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Subject Management
    public function addSubject($subjectName, $desc) {
        $stmt = $this->conn->prepare("INSERT INTO subjects (subject_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $subjectName, $desc);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateSubject($id, $subjectName, $desc) {
        $stmt = $this->conn->prepare("UPDATE subjects SET subject_name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $subjectName, $desc, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteSubject($id) {
        $stmt = $this->conn->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function fetchSubject() {
        $stmt = $this->conn->prepare("SELECT * FROM subjects");
        $stmt->execute();
        $result = $stmt->get_result();
        $subjects = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $subjects;
    }

    // Course Management
    public function addCourse($subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName) {
        $stmt = $this->conn->prepare("INSERT INTO courses (subject_id, course_name, description, author_name, price, offer_price, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdds", $subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateCourse($id, $subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName) {
        $stmt = $this->conn->prepare("UPDATE courses SET subject_id = ?, course_name = ?, description = ?, author_name = ?, price = ?, offer_price = ?, image = ? WHERE id = ?");
        $stmt->bind_param("isssddsi", $subjectId, $courseName, $description, $authorName, $price, $offerPrice, $imageName, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function courseNameExists($courseName, $id) {
        $stmt = $this->conn->prepare("SELECT id FROM courses WHERE course_name = ? AND id != ?");
        $stmt->bind_param("si", $courseName, $id);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function getCurrentImage($id) {
        $stmt = $this->conn->prepare("SELECT image FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($currentImage);
        $stmt->fetch();
        $stmt->close();
        return $currentImage;
    }

    public function deleteCourse($id) {
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getCourseImage($id) {
        $stmt = $this->conn->prepare("SELECT image FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($imageName);
        $stmt->fetch();
        $stmt->close();
        return $imageName;
    }

    public function fetchCourse() {
        $stmt = $this->conn->prepare("
            SELECT c.id, c.course_name, c.description, c.author_name, c.price, c.offer_price, c.image, s.subject_name, c.subject_id
            FROM courses c
            JOIN subjects s ON c.subject_id = s.id
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $courses = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $courses;
    }

    public function fetchCourseByid($subjectId) {
        $stmt = $this->conn->prepare("
            SELECT c.id, c.course_name, c.description, c.author_name, c.price, c.offer_price, c.image, s.subject_name, c.subject_id
            FROM courses c
            JOIN subjects s ON c.subject_id = s.id
            WHERE c.subject_id = ?
        ");
        $stmt->bind_param('i', $subjectId);
        $stmt->execute();
        $result = $stmt->get_result();
        $courses = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $courses;
    }

    // Video Management
    public function getVideoPdfPath($id) {
        $stmt = $this->conn->prepare("SELECT pdf_path FROM videos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($pdfPath);
        $stmt->fetch();
        $stmt->close();
        return $pdfPath;
    }

    public function addVideo($subjectId, $courseId, $videoName, $videoLink, $description, $pdfPath) {
        $stmt = $this->conn->prepare("INSERT INTO videos (subject_id, course_id, video_name, video_link, description, pdf_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $subjectId, $courseId, $videoName, $videoLink, $description, $pdfPath);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateVideo($id, $subjectId, $courseId, $videoName, $videoLink, $description, $pdfPath) {
        $stmt = $this->conn->prepare("UPDATE videos SET subject_id = ?, course_id = ?, video_name = ?, video_link = ?, description = ?, pdf_path = ? WHERE id = ?");
        $stmt->bind_param("iissssi", $subjectId, $courseId, $videoName, $videoLink, $description, $pdfPath, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteVideo($id) {
        $pdfPath = $this->getVideoPdfPath($id);
        if (!empty($pdfPath) && file_exists("uploads/" . $pdfPath)) {
            unlink("uploads/" . $pdfPath);
        }

        $stmt = $this->conn->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function fetchVideo() {
        $stmt = $this->conn->prepare("
            SELECT v.id, v.subject_id, v.course_id, v.video_name, v.video_link, v.description, v.pdf_path, s.subject_name, c.course_name
            FROM videos v
            JOIN subjects s ON v.subject_id = s.id
            JOIN courses c ON v.course_id = c.id
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $videos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $videos;
    }

    public function getVideosByCourse($courseId) {
        $stmt = $this->conn->prepare("SELECT id, video_name, pdf_path FROM videos WHERE course_id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $videos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $videos;
    }

    // Course Details
    public function getCourseDetails($courseId) {
        $stmt = $this->conn->prepare("
            SELECT
                c.course_name, c.image, c.description, c.author_name, c.price, c.offer_price
            FROM courses c
            WHERE c.id = ?
        ");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $courseDetails = $result->fetch_assoc();
        $stmt->close();
        return $courseDetails;
    }

    public function getSubjectsByCourse($courseId) {
        $stmt = $this->conn->prepare("
            SELECT s.id, s.subject_name, s.description AS about
            FROM subjects s
            JOIN courses c ON s.id = c.subject_id
            WHERE c.id = ?
        ");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $subjects = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $subjects;
    }

    // Cart Management
    public function addToCart($courseId, $courseName) {
        $stmt = $this->conn->prepare("INSERT INTO cart (course_id, course_name) VALUES (?, ?)");
        $stmt->bind_param("is", $courseId, $courseName);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getCartItems() {
        $stmt = $this->conn->prepare("
            SELECT c.id, c.course_id, c.course_name, co.description, co.price, co.offer_price, co.image
            FROM cart c
            JOIN courses co ON c.course_id = co.id
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $items;
    }

    public function removeFromCart($itemId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Order Management
    public function getAllOrders() {
        $sql = "SELECT * FROM checkout";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Admin Authentication
    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_username'] = $username;
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return "Invalid password.";
            }
        } else {
            $stmt->close();
            return "No user found with this username.";
        }
    }

    public function register($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare("SELECT id FROM adminsreg WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return "Username already exists.";
        }

        $stmt = $this->conn->prepare("INSERT INTO adminsreg (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return true;
        } else {
            return "Error: " . $stmt->error;
        }
    }

    public function loginUser($username, $password) {
        $stmt = $this->conn->prepare("SELECT id, password FROM adminsreg WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return "Password incorrect.";
            }
        } else {
            $stmt->close();
            return "Username incorrect.";
        }
    }

    // Payment Processing
    public function processPayment($userId, $amount) {
        $stmt = $this->conn->prepare("INSERT INTO payments (user_id, amount, payment_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("id", $userId, $amount);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getUserDetails($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userDetails = $result->fetch_assoc();
        $stmt->close();
        return $userDetails;
    }

    public function placeOrder($firstName, $lastName, $city, $phone, $email, $collegeName, $currentSemester, $hearAboutUs) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $stmt = $this->conn->prepare("INSERT INTO checkout (first_name, last_name, city, phone, email, college_name, current_semester, hear_about_us) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("ssssssss", $firstName, $lastName, $city, $phone, $email, $collegeName, $currentSemester, $hearAboutUs);

        if ($stmt->execute()) {
            $orderId = $this->conn->insert_id;

            $updateCart = $this->conn->prepare("UPDATE cart SET order_id = ? WHERE order_id = 0");
            if (!$updateCart) {
                error_log("Error preparing update statement: " . $this->conn->error);
                return false;
            }

            $updateCart->bind_param("i", $orderId);

            if ($updateCart->execute()) {
                return true;
            } else {
                error_log("Error updating cart: " . $updateCart->error);
                return false;
            }
        } else {
            error_log("Error inserting order: " . $stmt->error);
            return false;
        }
    }

    public function getMergedOrders() {
        $sql = "
            SELECT
                c.order_id,
                c.first_name,
                c.last_name,
                c.city,
                c.phone,
                c.email,
                c.college_name,
                c.current_semester,
                c.hear_about_us,
                c.order_date,
                ca.course_id,
                ca.course_name,
                co.price,
                co.author_name,
                co.description,
                co.offer_price,
                co.image
            FROM
                checkout c
            JOIN
                cart ca ON c.order_id = ca.order_id
            JOIN
                courses co ON ca.course_id = co.id
        ";
        $result = $this->conn->query($sql);
        if (!$result) {
            error_log("Error in SQL query: " . $this->conn->error);
        }
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function fetchCourseAndVideos($course_id) {
        $sql = "SELECT c.course_name, v.id AS video_id, v.video_name
                FROM courses c
                JOIN videos v ON c.id = v.course_id
                WHERE c.id = ?
                ORDER BY v.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $course_data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $course_data;
    }

    public function fetchVideoDetails($video_id) {
        $stmt = $this->conn->prepare("SELECT video_name, video_link, description FROM videos WHERE id = ?");
        $stmt->bind_param("i", $video_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $video_details = $result->fetch_assoc();
        $stmt->close();
        return $video_details;
    }

    public function fetchNextVideoId($video_id, $course_id) {
        $stmt = $this->conn->prepare("SELECT id FROM videos WHERE course_id = ? AND id > ? ORDER BY id ASC LIMIT 1");
        $stmt->bind_param("ii", $course_id, $video_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    public function fetchPrevVideoId($video_id, $course_id) {
        $stmt = $this->conn->prepare("SELECT id FROM videos WHERE course_id = ? AND id < ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("ii", $course_id, $video_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    public function fetchAllVideosInCourse($course_id) {
        $stmt = $this->conn->prepare("SELECT * FROM videos WHERE course_id = ? ORDER BY id ASC");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $videos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $videos;
    }

    public function __destruct() {
        $this->conn->close();
    }
}
?>
