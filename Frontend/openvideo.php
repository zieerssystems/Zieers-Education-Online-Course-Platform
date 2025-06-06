<?php
include '../backend/db.php';

// Get the video ID and course ID from the query parameters
$video_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($course_id <= 0) {
    die("Invalid course ID.");
}

$db = new MySqlDB();
$course_videos = $db->fetchAllVideosInCourse($course_id); // Fetch all videos in the course

// Find the current video index
$current_video_index = -1;
foreach ($course_videos as $index => $video) {
    if ($video['id'] == $video_id) {
        $current_video_index = $index;
        break;
    }
}

if ($current_video_index == -1 && count($course_videos) > 0) {
    // If no video ID is provided, default to the first video
    $current_video_index = 0;
    $video_id = $course_videos[0]['id'];
}

// Fetch video details using the fetchVideoDetails function
$video_details = $db->fetchVideoDetails($video_id);

// Extract the YouTube video ID from the video link
$video_link = isset($video_details['video_link']) ? $video_details['video_link'] : '';
$youtube_video_id = '';
if (!empty($video_link)) {
    // Extract the video ID from the URL
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/';
    preg_match($pattern, $video_link, $matches);
    $youtube_video_id = isset($matches[1]) ? $matches[1] : '';
}

// Fetch next and previous video IDs
$next_video_id = $db->fetchNextVideoId($video_id, $course_id);
$prev_video_id = $db->fetchPrevVideoId($video_id, $course_id);

// Ensure $next_video_id and $prev_video_id are initialized properly
$next_video_id = $next_video_id !== null ? $next_video_id : 0;
$prev_video_id = $prev_video_id !== null ? $prev_video_id : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .video-container {
            margin: 20px auto;
            padding: 20px;
            background-color: #d8efdc; /* Changed to the specified color */
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            overflow: hidden;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .video-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }
        .video-header {
            padding-bottom: 10px;
            margin-bottom: 15px;
            text-align: center;
            position: relative;
        }
        .video-header h2 {
            margin: 0;
            color: #00008b; /* Dark blue color */
            font-size: 2.5em;
            font-weight: bold;
            background: #d8efdc; /* Changed to match the container color */
            display: inline-block;
            padding: 0 20px;
            position: relative;
            z-index: 1;
            border-radius: 10px;
        }
        .video-player-container {
            text-align: center;
            margin-bottom: 15px;
        }
        .video-player {
            position: relative;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .video-description-header {
            font-size: 2em;
            color: #0d6efd;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 5px;
            display: inline-block;
            position: relative;
            z-index: 1;
            background: linear-gradient(135deg, #087f5b, #0d6efd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
        .video-description {
            font-size: 1.2em;
            color: #555;
            line-height: 1.8;
            text-align: left;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
            position: relative;
            border-left: 5px solid #0d6efd;
        }
        .nav-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }
        .nav-button, .back-to-orders-button {
            background: linear-gradient(135deg, #087f5b, #0d6efd);
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 10px 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 350px;
            transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .nav-button i, .back-to-orders-button i {
            margin-right: 10px;
        }
        .nav-button:hover, .back-to-orders-button:hover {
            background: linear-gradient(135deg, #0d6efd, #087f5b);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
        }
        .back-to-orders-button {
            margin-top: 10px;
        }
        .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .icon-container i {
            font-size: 2em;
            color: #0d6efd;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .icon-container i:hover {
            color: #087f5b;
            transform: scale(1.1);
        }
        .additional-content {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .additional-content h3 {
            color: #0d6efd;
            margin-bottom: 10px;
        }
        .additional-content p {
            color: #555;
            line-height: 1.6;
        }
        .video-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #087f5b, #0d6efd);
            border-radius: 20px 20px 0 0;
        }
    </style>
</head>
<body>

<div class="video-container">
    <div class="video-header">
        <h2 id="video-name"><?php echo isset($video_details['video_name']) ? htmlspecialchars($video_details['video_name']) : 'Video Name'; ?></h2>
    </div>
    <div class="video-player-container">
        <div class="video-player">
            <!-- Embed YouTube video here -->
            <div id="video-player"></div>
        </div>
    </div>
    <div class="video-description-header">Description</div>
    <div class="video-description">
        <?php echo isset($video_details['description']) ? htmlspecialchars($video_details['description']) : 'No description available.'; ?>
    </div>
    <div class="icon-container">
        <?php if ($prev_video_id > 0): ?>
            <i class="fas fa-arrow-left" onclick="navigateToVideo(<?php echo $prev_video_id; ?>, <?php echo $course_id; ?>)" title="Previous Video"></i>
        <?php endif; ?>
        <?php if ($next_video_id > 0): ?>
            <i class="fas fa-arrow-right" onclick="navigateToVideo(<?php echo $next_video_id; ?>, <?php echo $course_id; ?>)" title="Next Video"></i>
        <?php endif; ?>
    </div>
    <div class="nav-buttons">
        <button class="back-to-orders-button" onclick="goBackToOrders()">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </button>
    </div>
</div>

<!-- Include the YouTube IFrame API script -->
<script src="https://www.youtube.com/iframe_api"></script>

<script>
    let player;
    const videoList = <?php echo json_encode($course_videos); ?>;
    const currentVideoIndex = <?php echo $current_video_index; ?>;

    // Function to load the YouTube video
    function loadYouTubeVideo(videoId) {
        console.log("Loading video ID: " + videoId);
        if (player) {
            player.cueVideoById(videoId);
        } else {
            player = new YT.Player('video-player', {
                height: '360',
                width: '640',
                videoId: videoId,
                playerVars: {
                    modestbranding: 1, // Reduce the YouTube logo visibility
                    rel: 0,           // Do not show related videos at the end
                    controls: 1,       // Show player controls
                    fs: 1,            // Enable fullscreen button
                    cc_load_policy: 0, // Hide closed captions by default
                    iv_load_policy: 3, // Hide video annotations
                    autohide: 1,       // Hide video controls when not in use
                    autoplay: 0,      // Do not autoplay the video
                    disablekb: 1,      // Disable keyboard controls
                    showinfo: 0,      // Hide the video title and other info
                    enablejsapi: 1,    // Enable the JavaScript API
                    origin: window.location.origin // Set the origin to prevent security issues
                },
                events: {
                    'onReady': onPlayerReady,
                    'onError': onPlayerError
                }
            });
        }
    }

    // Called when the player is ready
    function onPlayerReady(event) {
        console.log("Player is ready");
        // Disable the context menu on the player
        document.getElementById('video-player').addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    }

    // Called when an error occurs
    function onPlayerError(event) {
        console.error("An error occurred: " + event.data);
        let errorMessage = "An error occurred while playing the video. ";
        switch (event.data) {
            case 2:
                errorMessage += "The request contains an invalid parameter value.";
                break;
            case 5:
                errorMessage += "The requested content cannot be played in an HTML5 player.";
                break;
            case 100:
                errorMessage += "The video requested does not allow playback in the embedded player.";
                break;
            case 101:
            case 150:
                errorMessage += "The video requested cannot be embedded as per the video owner's settings.";
                break;
            default:
                errorMessage += "Error code: " + event.data;
        }
        alert(errorMessage);
    }

    // Navigate to a specific video
    function navigateToVideo(videoId, courseId) {
        if (videoId > 0 && courseId > 0) {
            window.location.href = 'openvideo.php?id=' + videoId + '&course_id=' + courseId;
        } else {
            alert("Invalid video ID or course ID.");
        }
    }

    // Go back to orders.php
    function goBackToOrders() {
        window.location.href = 'orders.php';
    }

    // Load the YouTube video when the page loads
    function loadInitialVideo() {
        const videoId = "<?php echo $youtube_video_id; ?>";
        console.log("Initial video ID: " + videoId);
        if (videoId) {
            loadYouTubeVideo(videoId);
        } else {
            console.error("No video ID provided.");
            alert("No video ID provided. Please try again later.");
        }
    }

    // Check if the YouTube API is already loaded
    if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
        window.onYouTubeIframeAPIReady = loadInitialVideo;
    } else {
        loadInitialVideo();
    }

    // Disable right-click on the entire document
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Disable keyboard shortcuts that might reveal the video URL
    document.addEventListener('keydown', function(e) {
        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I') || (e.ctrlKey && e.shiftKey && e.key === 'J') || (e.ctrlKey && e.key === 'U')) {
            e.preventDefault();
        }
    });

    // Obfuscate the video URL by using a server-side proxy
    function getObfuscatedVideoUrl(videoId) {
        return 'proxy.php?video_id=' + videoId;
    }
    document.getElementById('video-player').addEventListener('contextmenu', function(e) {
    e.preventDefault();
});

</script>

</body>
</html>
