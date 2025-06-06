<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            height: 100%;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .wrapper {
            display: flex;
            height: 100%;
        }
        .sidebar {
            width: 280px;
            background-color: #343a40;
            color: #ffffff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100%;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 1.8em;
            color: #ffffff;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 20px 0;
        }
        .sidebar ul li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 1.2em;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }
        .sidebar ul li a:hover {
            color: #007bff;
        }
        .sidebar ul li a .icon {
            margin-right: 10px;
            font-size: 1.4em;
        }
        .main-content {
            margin-left: 300px;
            padding: 30px;
            background-color: #f8f9fa;
            width: calc(100% - 300px);
        }
        .content-header {
            text-align: left;
            margin-bottom: 20px;
        }
        .content-header h1 {
            font-size: 2.2em;
            color: #343a40;
        }
        .content-header p {
            font-size: 1.2em;
            color: #6c757d;
        }
        .welcome-section {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .welcome-section h2 {
            font-size: 2.5em;
            color: #007bff;
            margin-bottom: 20px;
        }
        .welcome-section p {
            font-size: 1.3em;
            color: #6c757d;
            max-width: 700px;
            margin: 20px auto;
        }
        .admin-image {
            max-width: 100%;
            height: auto;
            margin-top: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h3 {
            font-size: 1.8em;
            color: #343a40;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 1.1em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2 class="admin-title">🎓 Visionary Hub</h2>
            <ul>
                <li><a href="?page=add_subject"><span class="icon">📘</span> Add Subject</a></li>
                <li><a href="?page=add_course"><span class="icon">📚</span> Add Course</a></li>
                <li><a href="?page=add_video"><span class="icon">🎥</span> Add Video</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- <div class="content-header">
                <h1>Admin Dashboard</h1>
                <p>Manage your educational content with ease.</p>
            </div> -->
            <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if ($page == "add_subject") {
                        include 'add_subject.php';
                    } elseif ($page == "add_course") {
                        include 'add_course.php';
                    } elseif ($page == "add_video") {
                        include 'add_video.php';
                    } else {
                        echo "<div class='card'><h3>Empowering You: The Admin Experience</h3><p>Select a valid option from the sidebar to proceed.</p></div>";
                    }
                } else {
                    echo "<div class='welcome-section'>";
                    echo "<h2>System Access Granted: Admin Mode On</h2>";
                    echo "<p>Efficiently organize, manage, and elevate your learning experience!</p>";
                    // echo "<img src='images/admin_image.jpg' alt='Admin Dashboard Image' class='admin-image'>";
                    echo "<p>Smart learning, simplified. Explore knowledge from anywhere, anytime.</p>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>
</body>
</html>
