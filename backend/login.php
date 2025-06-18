<?php
session_start();
$errorMessage = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : "";
unset($_SESSION['errorMessage']); // Clear the error message after displaying it
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-form h2 {
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

        .login-button {
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

        .login-button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
    <script>
        // Clear the form fields when the page loads
        window.onload = function() {
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
        };

        // Clear the form fields before submitting the form
        document.querySelector('.login-form').addEventListener('submit', function() {
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
        });
    </script>
</head>
<body>
    <div class="login-container">
        <form action="process_login.php" method="POST" class="login-form">
            <h2>Admin Login</h2>
            <?php if ($errorMessage !== ""): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
