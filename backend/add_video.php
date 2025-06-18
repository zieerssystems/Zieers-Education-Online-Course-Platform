<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #4c8069;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 15px;
            text-align: center;
        }
        th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .action-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px; /* Space between buttons */
        }
        .action-buttons button {
            padding: 12px 20px;
            cursor: pointer;
            border: none;
            color: white;
            border-radius: 5px;
            width: 120px; /* Ensure consistent width */
            height: 45px; /* Ensure consistent height */
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
            background-color: #acbf60; /* Default color for all buttons */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .action-buttons button:hover {
            background-color: #9bbc50; /* Hover color for all buttons */
            transform: translateY(-2px);
        }
        .highlight {
            background-color: #fff3cd !important;
            transition: background-color 1s ease;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            font-size: 16px;
            display: block;
            margin: 15px 0 5px;
            color: #333;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #acbf60;
            outline: none;
        }
        #submitButtonContainer, #editButtonContainer {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        #submitButton, #updateButton, #cancelButton {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #acbf60; /* Default color for all buttons */
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease;
            height: 45px; /* Ensure consistent height */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        #submitButton:hover, #updateButton:hover, #cancelButton:hover {
            background-color: #9bbc50; /* Hover color for all buttons */
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Video Library</h2>
    <form id="videoForm" enctype="multipart/form-data">
        <input type="hidden" id="editId" name="id">

        <label>Subject:</label>
        <select id="subjectSelect" name="subjectId" required></select>

        <label>Course:</label>
        <select id="courseSelect" name="courseId" required>
            <option value="">Select Course</option>
        </select>

        <label>Video Name:</label>
        <input type="text" id="videoName" name="videoName" required>

        <label>Video Link:</label>
        <input type="text" id="videoLink" name="videoLink" required>

        <label>Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label>Upload PDF (Syllabus):</label>
        <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf">
        <div id="existingPdf"></div>

        <div id="submitButtonContainer">
            <button type="submit" id="submitButton">Submit</button>
        </div>
        <div id="editButtonContainer" style="display: none;">
            <button type="submit" id="updateButton">Update</button>
            <button type="button" id="cancelButton" class="cancel">Cancel</button>
        </div>
    </form>

    <h3>Video Inventory</h3>
    <table id="videoTable">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Course</th>
                <th>Video Name</th>
                <th>Video</th>
                <th>Description</th>
                <th>PDF</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(document).ready(function () {
    loadSubjects();
    loadVideos();

    function loadSubjects() {
        $.get("process_video.php?action=fetch_subjects", function (response) {
            if (response.success && Array.isArray(response.data)) {
                let subjectSelect = $('#subjectSelect');
                subjectSelect.empty().append('<option value="">Select Subject</option>');
                response.data.forEach(subject => subjectSelect.append(`<option value="${subject.id}">${subject.subject_name}</option>`));
            } else {
                console.error("Error fetching subjects:", response.message);
            }
        }, "json");
    }

    $('#subjectSelect').change(function () {
        let subjectId = $(this).val();
        let courseSelect = $('#courseSelect');
        courseSelect.empty().append('<option value="">Loading...</option>');

        $.get(`process_video.php?action=getCourses&subjectId=${subjectId}`, function (response) {
            if (response.success && Array.isArray(response.data)) {
                courseSelect.empty().append('<option value="">Select Course</option>');
                response.data.forEach(course => courseSelect.append(`<option value="${course.id}">${course.course_name}</option>`));
            } else {
                console.error("Error fetching courses:", response.message);
            }
        }, "json");
    });

    $('#videoForm').submit(function (event) {
        event.preventDefault();
        let formData = new FormData(this);
        let actionType = $('#editId').val() ? 'updateVideo' : 'addVideo';
        formData.append('action', actionType);
        let editedId = $('#editId').val(); // capture before clearing

        $.ajax({
            url: "process_video.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                alert(response.message);
                if (response.success) {
                    loadVideos();

                    // Scroll to table after update
                    if (actionType === 'updateVideo') {
                        setTimeout(function () {
                            $('html, body').animate({
                                scrollTop: $("#videoTable").offset().top
                            }, 500);

                            // highlight the row briefly
                            let row = $('#row-' + editedId);
                            row.addClass('highlight');
                            setTimeout(() => row.removeClass('highlight'), 2000);
                        }, 500);
                    }

                    $('#videoForm')[0].reset();
                    $('#editId').val('');
                    $('#existingPdf').html('');
                    $('#submitButtonContainer').show();
                    $('#editButtonContainer').hide();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                console.log("Response Text:", xhr.responseText);
            }
        });
    });

    function loadVideos() {
        $.get("process_video.php?action=getVideos", function (response) {
            if (response.success && Array.isArray(response.data)) {
                let tbody = $('#videoTable tbody');
                tbody.empty();
                response.data.forEach(video => {
                    tbody.append(`
                        <tr id="row-${video.id}">
                            <td>${video.subject_name}</td>
                            <td>${video.course_name}</td>
                            <td>${video.video_name}</td>
                            <td>${video.video_link}</td>
                            <td>${video.description}</td>
                            <td>${video.pdf_path ? `<a href="uploads/${video.pdf_path}" target="_blank">View PDF</a>` : "No PDF"}</td>
                            <td class="action-buttons">
                                <button class="edit" onclick="editVideo(${video.id}, '${video.subject_id}', '${video.course_id}', \`${video.video_name}\`, \`${video.video_link}\`, \`${video.description}\`, '${video.pdf_path}')">Edit</button>
                                <button class="delete" onclick="deleteVideo(${video.id})">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                console.error("Error fetching videos:", response.message);
            }
        }, "json");
    }

    window.editVideo = function (id, subjectId, courseId, videoName, videoLink, description, pdfPath) {
        $('#editId').val(id);
        $('#subjectSelect').val(subjectId).trigger('change');

        setTimeout(() => {
            $('#courseSelect').val(courseId);
        }, 300);

        $('#videoName').val(videoName);
        $('#videoLink').val(videoLink);
        $('#description').val(description);
        $('#existingPdf').html(pdfPath ? `<a href="uploads/${pdfPath}" target="_blank">Current PDF</a>` : '');

        $('#submitButtonContainer').hide();
        $('#editButtonContainer').show();

        $('html, body').animate({
            scrollTop: $("#videoForm").offset().top
        }, 500);
        $('#videoName').focus();
    };

    window.deleteVideo = function (id) {
        if (confirm("Are you sure you want to delete this video?")) {
            $.post("process_video.php", { action: "deleteVideo", id: id }, function (response) {
                alert(response.message);
                if (response.success) {
                    loadVideos();
                }
            }, "json");
        }
    };

    $('#cancelButton').click(function () {
        $('#videoForm')[0].reset();
        $('#editId').val('');
        $('#existingPdf').html('');
        $('#submitButtonContainer').show();
        $('#editButtonContainer').hide();
    });
});
</script>
</body>
</html>
