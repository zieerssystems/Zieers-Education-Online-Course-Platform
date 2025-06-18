<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;
require_once __DIR__ . '/../backend/db.php';

$db = new MySqlDB();
$course = $db->getCourseDetails($courseId);
$subjects = $db->getSubjectsByCourse($courseId);
$videos = $db->getVideosByCourse($courseId);
$pdfs = $db->getPDFsByCourse($courseId); // Fetch PDFs for the course

$course = is_array($course) ? $course : [];
$subjects = is_array($subjects) ? $subjects : [];
$videos = is_array($videos) ? $videos : [];
$pdfs = is_array($pdfs) ? $pdfs : [];

$courseName = $course['course_name'] ?? 'Course Not Found';
$description = $course['description'] ?? 'Course details not available.';
$authorName = $course['author_name'] ?? 'Author Name Not Found';
$price = $course['price'] ?? 'Price Not Available';
$offerPrice = $course['offer_price'] ?? 'Offer Price Not Available';

// Base URL for PDF files
$pdfBaseUrl = '/admin_panel/uploads/';

// Debugging: Log the base URL and PDF paths
error_log("Base URL: " . $_SERVER['DOCUMENT_ROOT'] . $pdfBaseUrl);
foreach ($pdfs as $pdf) {
    $pdfPath = $_SERVER['DOCUMENT_ROOT'] . $pdfBaseUrl . htmlspecialchars($pdf['pdf_path'] ?? '');
    error_log("PDF Path: " . $pdfPath);

    // Check if the file exists
    if (!file_exists($pdfPath)) {
        error_log("File not found: " . $pdfPath);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Course Details | LearnPro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e6e6fa, #d8bfd8);
      padding: 40px 20px;
    }

    .course-container {
      max-width: 1000px;
      margin: auto;
      background: linear-gradient(145deg, #ffffff, #e6e6fa);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
    }

    .course-title {
      font-size: 2.8rem;
      font-weight: 700;
      color: #4b0082;
      text-align: center;
      margin-bottom: 20px;
    }

    .course-info {
      text-align: center;
      margin-bottom: 30px;
    }

    .course-info p {
      font-size: 1.1rem;
      color: #555;
      margin: 6px 0;
    }

    .price-tag, .offer-price-tag {
      display: inline-block;
      padding: 8px 18px;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 12px;
      margin-top: 10px;
    }

    .price-tag {
      background-color: #e6e6fa;
      color: #4b0082;
    }

    .offer-price-tag {
      background-color: #f0e6ff;
      color: #9370db;
      margin-left: 10px;
    }

    .section-title {
      font-size: 1.8rem;
      font-weight: 600;
      color: #4b0082;
      text-align: center;
      margin-top: 40px;
      margin-bottom: 25px;
    }

    .subject-item {
      background: linear-gradient(145deg, #f5f5ff, #e6e6fa);
      border-left: 6px solid #9370db;
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .subject-item:hover {
      background: linear-gradient(145deg, #e6e6fa, #f5f5ff);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      transform: translateY(-4px);
    }

    .subject-item h5 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #9370db;
    }

    .subject-item p {
      color: #4b0082;
      margin-top: 10px;
      font-size: 1rem;
    }

    .pdf-item {
      background: linear-gradient(145deg, #f5f5ff, #e6e6fa);
      border-left: 6px solid #9370db;
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .pdf-item:hover {
      background: linear-gradient(145deg, #e6e6fa, #f5f5ff);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      transform: translateY(-4px);
    }

    .pdf-item h5 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #9370db;
    }

    .pdf-link {
      color: #9370db;
      text-decoration: none;
      font-weight: bold;
    }

    .pdf-link:hover {
      color: #4b0082;
    }

    .video-item {
      background: linear-gradient(145deg, #f5f5ff, #e6e6fa);
      border-left: 6px solid #9370db;
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .video-item:hover {
      background: linear-gradient(145deg, #e6e6fa, #f5f5ff);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      transform: translateY(-4px);
    }

    .video-item h5 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #9370db;
    }

    .video-item video {
      width: 100%;
      height: auto;
      border-radius: 10px;
    }

    .footer-note {
      margin-top: 40px;
      font-size: 1rem;
      text-align: center;
      color: #6c757d;
    }

    @media (max-width: 768px) {
      .course-title {
        font-size: 2rem;
      }
      .section-title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="course-container">
  <h1 class="course-title"><?php echo htmlspecialchars($courseName); ?></h1>

  <div class="course-info">
    <p><?php echo htmlspecialchars($description); ?></p>
    <p><strong>Author:</strong> <?php echo htmlspecialchars($authorName); ?></p>
    <p><strong>Price:</strong> <span class="price-tag">₹<?php echo htmlspecialchars($price); ?></span></p>
    <p><strong>Offer:</strong> <span class="offer-price-tag">₹<?php echo htmlspecialchars($offerPrice); ?></span></p>
  </div>

  <!-- PDF Section -->
  <div class="pdf-section">
    <h2 class="section-title">📄 Course Syllabus</h2>
    <?php if (!empty($pdfs)): ?>
      <?php foreach ($pdfs as $pdf): ?>
        <div class="pdf-item">
          <h5><?php echo htmlspecialchars($pdf['video_name'] ?? ''); ?></h5>
          <a href="<?php echo $pdfBaseUrl . htmlspecialchars($pdf['pdf_path'] ?? ''); ?>" class="pdf-link" target="_blank">View PDF</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">No syllabus PDF found for this course.</p>
    <?php endif; ?>
  </div>

  <div class="subject-section">
    <h2 class="section-title">📘 Curriculum Breakdown</h2>
    <?php if (!empty($subjects)): ?>
      <?php foreach ($subjects as $subject): ?>
        <div class="subject-item">
          <h5><?php echo htmlspecialchars($subject['subject_name'] ?? ''); ?></h5>
          <p><?php echo htmlspecialchars($subject['about'] ?? ''); ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">No subjects found for this course.</p>
    <?php endif; ?>
  </div>
</body>
</html>
