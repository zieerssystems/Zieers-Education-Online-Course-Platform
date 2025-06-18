<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f0f2f5, #c3cfe2);
        }

        .register-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .register-form h2 {
            margin-bottom: 24px;
            color: #333333;
            font-size: 28px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555555;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 14px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #007BFF;
            outline: none;
        }

        .register-button {
            width: 100%;
            padding: 16px;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            color: #ffffff;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 16px;
        }

        .register-button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
            color: #d9534f;
        }

        .success {
            color: #5cb85c;
        }

        .login-link {
            margin-top: 20px;
            font-size: 16px;
            color: #007BFF;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.register-form').on('submit', function(event) {
                event.preventDefault();
                $('.message').remove();

                var username = $('#username').val().trim();
                var password = $('#password').val().trim();
                var confirm_password = $('#confirm_password').val().trim();

                // Password validation
                var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (!passwordRegex.test(password)) {
                    $('.register-form').append('<div class="message">Password must contain at least 8 characters, including uppercase, lowercase letters, numbers, and special characters.</div>');
                    return;
                }

                if (password !== confirm_password) {
                    $('.register-form').append('<div class="message">Passwords do not match.</div>');
                    return;
                }

                $.ajax({
                    url: 'process_register.php',
                    type: 'POST',
                    data: {
                        username: username,
                        password: password,
                        confirm_password: confirm_password
                    },
                    success: function(response) {
                        if (response === 'true') {
                            $('.register-form').append('<div class="message success">Registration successful.</div>');

                            // Hide the form fields
                            $('.form-group').hide();
                            $('.register-button').hide();

                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 2000);
                        } else {
                            $('.register-form').append('<div class="message">' + response + '</div>');
                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 2000);
                        }

                        // Clear the form fields
                        $('#username').val('');
                        $('#password').val('');
                        $('#confirm_password').val('');
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="register-container">
        <form class="register-form">
            <h2>Admin Registration</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="register-button">Register</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Login here.</a>
    </div>
</body>
</html>
