<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Organizer</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            text-align: center;
            color: #8dbfb7;
        }
        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        label {
            font-size: 18px;
            display: block;
            margin: 10px 0 5px;
            color: #343a40;
        }
        input, select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .button, .action-button {
            background-color: #bf8dae;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 120px;
            text-align: center;
        }
        .button:hover, .action-button:hover {
            background-color: #a67a96;
        }
        #submitButton {
            background-color: #8dbfb7;
        }
        #submitButton:hover {
            background-color: #73a59f;
        }
        .action-button {
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 15px;
            text-align: center;
        }
        th {
            background-color: #f1f3f5;
            color: #343a40;
        }
        img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
        }
        .highlight {
            background-color: #fff3cd !important;
            transition: background-color 1s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Course Organizer</h2>
        <div class="form-container">
            <form id="courseForm" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">
                <label>Subject:</label>
                <select id="subjectSelect" name="subjectId" required>
                    <option value="">Select Subject</option>
                </select>
                <label>Course Name:</label>
                <input type="text" id="courseName" name="courseName" required>
                <label>Description:</label>
                <input type="text" id="courseDescription" name="description" required>
                <label>Author Name:</label>
                <input type="text" id="authorName" name="authorName" required>
                <label>Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
                <label>Offer Price:</label>
                <input type="number" id="offerPrice" name="offerPrice" step="0.01">
                <label>Course Image:</label>
                <input type="file" id="courseImage" name="courseImage" accept="image/*">
                <div class="button-container">
                    <button type="submit" id="submitButton" class="button">Add</button>
                    <button type="button" id="cancelButton" class="button" style="display: none;">Cancel</button>
                </div>
            </form>
        </div>

        <h3>Course Inventory</h3>
        <table id="courseTable">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Offer Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            let lastUpdatedId = null;

            function loadSubjects() {
                $.getJSON('process_course.php?action=fetch_subjects', function (response) {
                    let subjectSelect = $('#subjectSelect');
                    subjectSelect.empty().append('<option value="">Select Subject</option>');
                    response.forEach(subject => {
                        subjectSelect.append(`<option value="${subject.id}">${subject.subject_name}</option>`);
                    });
                });
            }

            function loadCourses() {
                $.getJSON('process_course.php?action=fetch_courses', function (response) {
                    let tbody = $('#courseTable tbody');
                    tbody.empty();
                    response.forEach(course => {
                        let imageTag = course.image ? `<img src="uploads/${course.image}" alt="Course Image">` : 'No Image';
                        let highlightClass = course.id == lastUpdatedId ? 'highlight' : '';
                        tbody.append(`
                            <tr id="row-${course.id}" class="${highlightClass}">
                                <td>${course.subject_name}</td>
                                <td>${course.course_name}</td>
                                <td>${course.description}</td>
                                <td>${course.author_name}</td>
                                <td>${course.price}</td>
                                <td>${course.offer_price ? course.offer_price : 'N/A'}</td>
                                <td>${imageTag}</td>
                                <td>
                                    <button class="action-button" onclick="editCourse(${course.id}, '${course.subject_id}', '${course.course_name}', '${course.description}', '${course.author_name}', ${course.price}, ${course.offer_price ? course.offer_price : 0}, '${course.image}')">Edit</button>
                                    <button class="action-button" onclick="deleteCourse(${course.id})">Delete</button>
                                </td>
                            </tr>
                        `);
                    });

                    if (lastUpdatedId) {
                        setTimeout(() => {
                            $('#row-' + lastUpdatedId).removeClass('highlight');
                        }, 2000);

                        $('html, body').animate({
                            scrollTop: $('#row-' + lastUpdatedId).offset().top
                        }, 800);

                        lastUpdatedId = null;
                    }
                });
            }

            $('#courseForm').submit(function (event) {
                event.preventDefault();
                let formData = new FormData(this);
                let action = $('#editId').val() ? 'update' : 'add';
                formData.append('action', action);
                let editedId = $('#editId').val();

                $.ajax({
                    url: 'process_course.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (response) {
                        alert(response.message);
                        if (response.success) {
                            lastUpdatedId = response.id || editedId;

                            loadCourses();
                            $('#courseForm')[0].reset();
                            $('#editId').val('');
                            $('#submitButton').text('Add');
                            $('#cancelButton').hide();

                            $('#courseForm input, #courseForm select').blur();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("An error occurred while processing your request.");
                    }
                });
            });

            window.editCourse = function (id, subjectId, courseName, description, authorName, price, offerPrice, image) {
                $('#editId').val(id);
                $('#subjectSelect').val(subjectId);
                $('#courseName').val(courseName);
                $('#courseDescription').val(description);
                $('#authorName').val(authorName);
                $('#price').val(price);
                $('#offerPrice').val(offerPrice);

                $('#submitButton').text('Update');
                $('#cancelButton').show();

                $('#courseName').focus();

                $('html, body').animate({
                    scrollTop: $('#courseForm').offset().top
                }, 800);
            };

            window.deleteCourse = function (id) {
                if (confirm("Are you sure?")) {
                    $.post('process_course.php', { id: id, action: 'delete' }, function (response) {
                        if (response.success) {
                            $('#row-' + id).remove();
                        }
                    }, 'json');
                }
            };

            $('#cancelButton').click(function () {
                $('#courseForm')[0].reset();
                $('#editId').val('');
                $('#submitButton').text('Add');
                $(this).hide();
            });

            loadSubjects();
            loadCourses();
        });
    </script>
</body>
</html>
