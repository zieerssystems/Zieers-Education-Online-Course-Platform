<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { width: 80%; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .course-image { width: 100px; height: auto; }
    </style>
</head>
<body>

<div class="container">
    <h2>Course List</h2>
    <select id="subjectSelect">
        <option value="">Select Subject</option>
        <!-- Options will be populated dynamically -->
    </select>
    <button id="fetchCoursesButton">Fetch Courses</button>

    <h3>Available Courses</h3>
    <table id="courseTable">
        <thead>
            <tr>
                <th>Course Image</th>
                <th>Course Name</th>
                <th>Description</th>
                <th>Author</th>
                <th>Price</th>
                <th>Offer Price</th>
                <th>Subject</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(document).ready(function () {
    // Load subjects into the subject dropdown
    loadSubjects();

    // Function to load subjects into the subject dropdown
    function loadSubjects() {
        $.ajax({
            url: "http://localhost/admin_panel/backend/courses_api",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ action: "fetch_subjects" }),
            success: function (response) {
                if (response.success1 && Array.isArray(response.data)) {
                    let subjectSelect = $('#subjectSelect');
                    subjectSelect.empty().append('<option value="">Select Subject</option>');
                    response.data.forEach(subject => subjectSelect.append(`<option value="${subject.id}">${subject.subject_name}</option>`));
                } else {
                    console.error("Error fetching subjects:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                console.log("Response Text:", xhr.responseText);
            }
        });
    }

    // Fetch courses when the button is clicked
    $('#fetchCoursesButton').click(function () {
        let subjectId = $('#subjectSelect').val();
        let data = { action: "getCourses" };

        if (subjectId) {
            data.subjectId = subjectId;
        }

        $.ajax({
            url: "http://localhost/admin_panel/backend/courses_api",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (response) {
                if (response.success && Array.isArray(response.data)) {
                    displayCourses(response.data);
                } else {
                    console.error("Error fetching courses:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                console.log("Response Text:", xhr.responseText);
            }
        });
    });

    // Function to display courses in the table
    function displayCourses(courses) {
        let tbody = $('#courseTable tbody');
        tbody.empty();
        courses.forEach(course => {
            tbody.append(`
                <tr>
                    <td>${course.image ? `<img src="${course.image}" alt="${course.course_name}" class="course-image">` : 'No Image'}</td>
                    <td>${course.course_name}</td>
                    <td>${course.description}</td>
                    <td>${course.author_name}</td>
                    <td>${course.price}</td>
                    <td>${course.offer_price}</td>
                    <td>${course.subject_name}</td>
                </tr>
            `);
        });
    }
});
</script>

</body>
</html>
