<?php
// proxy.php
$video_id = isset($_GET['video_id']) ? $_GET['video_id'] : '';
if (!empty($video_id)) {
    // Fetch the video content from YouTube or any other source
    // You can use cURL or any other method to fetch the video content
    $video_url = "https://www.youtube.com/embed/" . $video_id;
    header("Location: " . $video_url);
    exit;
} else {
    die("Invalid video ID.");
}
?>
