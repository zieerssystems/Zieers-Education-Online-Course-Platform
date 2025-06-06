<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            color: #333;
        }
        .form-container {
            width: 70%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 2px solid #ddd;
        }
        th, td {
            padding: 14px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        th {
            background-color: #8dbfb7;
            color: white;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        button {
            background-color: #8dbfb7;
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100px; /* Ensure consistent width */
            text-align: center;
        }
        button:hover {
            background-color: #73a59f;
        }
        #cancelButton {
            display: none;
            background-color: #dc3545;
        }
        #cancelButton:hover {
            background-color: #c82333;
        }
        .highlight-row {
            animation: highlight 2s ease;
        }
        @keyframes highlight {
            from { background-color: #ffeeba; }
            to { background-color: white; }
        }
    </style>
</head>
<body>
    <h2>SUBJECT MANAGEMENT</h2>
    <div class="form-container" id="formSection">
        <form id="subjectForm">
            <input type="hidden" id="editIndex">
            <input type="hidden" id="originalSubjectName">

            <label>Subject Name:</label>
            <input type="text" id="subjectName" required>

            <label>Description:</label>
            <textarea id="subjectDescription" rows="4" required></textarea>

            <div class="action-buttons">
                <button type="submit" id="submitButton">Add</button>
                <button type="button" id="cancelButton">Cancel</button>
            </div>
        </form>
    </div>

    <h2>SUBJECT INVENTORY</h2>
    <div id="tableSection">
        <table id="subjectTable">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    let lastUpdatedId = null;

    function loadSubjects(callback) {
        $.ajax({
            url: 'process_subject.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let tbody = $('#subjectTable tbody');
                tbody.empty();
                response.forEach((subject) => {
                    tbody.append(`
                        <tr id="row-${subject.id}">
                            <td>${subject.subject_name}</td>
                            <td>${subject.description}</td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="editSubject('${subject.id}', \`${subject.subject_name}\`, \`${subject.description.replace(/`/g, "\\`")}\`)">Edit</button>
                                    <button onclick="deleteSubject(${subject.id})">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `);
                });

                if (callback) callback();
            }
        });
    }

    $('#subjectForm').submit(function(event) {
        event.preventDefault();
        let id = $('#editIndex').val();
        let name = $('#subjectName').val().trim();
        let description = $('#subjectDescription').val().trim();
        let originalName = $('#originalSubjectName').val();
        let action = id ? 'update' : 'add';

        $.ajax({
            url: 'process_subject.php',
            type: 'POST',
            data: { id, subject_name: name, description, original_name: originalName, action },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                resetForm();

                if (action === 'update') {
                    lastUpdatedId = id;  // Store ID to scroll later
                }

                loadSubjects(() => {
                    if (lastUpdatedId) {
                        // Delay to allow DOM to render
                        setTimeout(() => {
                            let row = $(`#row-${lastUpdatedId}`);
                            if (row.length) {
                                $('html, body').animate({
                                    scrollTop: row.offset().top - 100
                                }, 600);

                                row.addClass('highlight-row');
                                setTimeout(() => {
                                    row.removeClass('highlight-row');
                                }, 2000);
                            }
                            lastUpdatedId = null;
                        }, 200);
                    }
                });
            }
        });
    });

    window.editSubject = function(id, name, description) {
        $('#subjectName').val(name);
        $('#subjectDescription').val(description);
        $('#editIndex').val(id);
        $('#originalSubjectName').val(name);
        $('#submitButton').text('Update');
        $('#cancelButton').show();
        $('#subjectName').focus();

        $('html, body').animate({
            scrollTop: $('#formSection').offset().top
        }, 600);
    };

    window.deleteSubject = function(id) {
        if (confirm("Are you sure you want to delete this subject?")) {
            $.ajax({
                url: 'process_subject.php',
                type: 'POST',
                data: { id, action: 'delete' },
                success: function() {
                    loadSubjects();
                }
            });
        }
    };

    $('#cancelButton').click(function() {
        resetForm();
    });

    function resetForm() {
        $('#subjectForm')[0].reset();
        $('#editIndex').val('');
        $('#originalSubjectName').val('');
        $('#submitButton').text('Add');
        $('#cancelButton').hide();
    }

    loadSubjects();
});
</script>
</body>
</html>
