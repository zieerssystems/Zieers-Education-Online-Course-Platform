<?php
session_start();
include '../backend/db.php';

$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($course_id <= 0) die("Invalid course ID.");

$db = new MySqlDB();
$course_data = $db->fetchCourseAndVideos($course_id);

// Check if $course_data is not empty and is an array
if (empty($course_data) || !is_array($course_data)) {
    die("No videos available for this course.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Videos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('admin_panel/uploads/BLUE.JPEG') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .course-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 40px;
            background: rgba(248, 249, 250, 0.9); /* Adding transparency to the background color */
            border-radius: 20px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
            border: 1px solid #b3e5fc;
        }

        .course-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .course-header h2 {
            font-size: 2.6em;
            color: #00897b;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .course-header .quote {
            margin-top: 12px;
            font-style: italic;
            color: #00695c;
            font-size: 1.1em;
        }

        .video-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .video-list li {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 18px;
            background: #e0f2f1;
            border-left: 6px solid #00897b;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            transition: all 0.25s ease-in-out;
        }

        .video-list li:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .serial-number {
            width: 30px;
            font-weight: bold;
            color: #00897b;
            font-size: 1.3em;
            text-align: center;
        }

        .video-name {
            flex-grow: 1;
            font-size: 1em;
            color: #333;
            font-weight: 500;
        }

        .play-button {
            background: #00897b;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 0.95em;
            display: flex;
            align-items: center;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .play-button i {
            margin-right: 6px;
        }

        .play-button:hover {
            background: #00695c;
            transform: scale(1.05);
        }

        .back-button {
            margin-top: 30px;
            background-color: #00695c;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            cursor: pointer;
            font-size: 1em;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-button i {
            margin-right: 10px;
        }

        .back-button:hover {
            background-color: #004d40;
            transform: scale(1.05);
        }

        .button-container {
            text-align: center;
        }

        .course-description {
            margin: 20px 0;
            padding: 15px;
            background-color: #b2dfdb;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1em;
            color: #00695c;
            border: 1px solid #80cbc4;
        }

        .video-thumbnail {
            width: 50px;
            height: 50px;
            background-color: #4db6ac;
            border-radius: 8px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #004d40;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .video-thumbnail i {
            font-size: 1.5em;
        }

        @media (max-width: 768px) {
            .video-list li {
                flex-direction: column;
                align-items: flex-start;
            }

            .play-button {
                margin-top: 10px;
                align-self: flex-end;
            }

            .video-name {
                margin-top: 5px;
            }
        }
    </style>
</head>
<body oncontextmenu="return false;">

<div class="course-container">
    <div class="course-header">
        <h2><?php echo htmlspecialchars($course_data[0]['course_name'] ?? 'Course Name Not Available'); ?></h2>
        <div class="quote">"Education is the most powerful weapon which you can use to change the world." – Nelson Mandela</div>
    </div>

    <div class="course-description">
        Explore the comprehensive video lectures designed to enhance your understanding and skills in this course.
    </div>

    <?php if (!empty($course_data) && is_array($course_data)): ?>
        <ul class="video-list">
    <?php foreach ($course_data as $index => $video): ?>
        <li>
            <div class="serial-number"><?php echo $index + 1; ?></div>
            <div class="video-thumbnail"><i class="fas fa-video"></i></div>
            <div class="video-name"><?php echo htmlspecialchars($video['video_name'] ?? 'Video Name Not Available'); ?></div>
            <button class="play-button" onclick="playVideo(<?php echo htmlspecialchars($video['video_id'] ?? 0); ?>, <?php echo htmlspecialchars($course_id); ?>)">
                <i class="fas fa-play"></i> Play
            </button>
        </li>
    <?php endforeach; ?>
</ul>

    <?php else: ?>
        <p>No videos available for this course.</p>
    <?php endif; ?>

    <div class="button-container">
        <button class="back-button" onclick="window.location.href='orders.php'">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </button>
    </div>
</div>

<script>
    function playVideo(videoId, courseId) {
        if (videoId > 0 && courseId > 0) {
            window.location.href = `openvideo.php?id=${videoId}&course_id=${courseId}`;
        } else {
            alert("Invalid video ID or course ID.");
        }
    }

    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
    });
</script>

</body>
</html>
