<!DOCTYPE html>
<html>
<head>
    <title>Login Portal</title>
    <style>
        body {
            background-repeat: no-repeat;
            background-size: cover;
        }

        .login-form {
            width: 350px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
        }

        .login-form h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .login-form input {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .login-form button {
            width: 95%;
            height: 40px;
            font-size: 16px;
            color: white;
            background-color: #333;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <form method="post" action="process_login.php">
            <label for="login_id">Username:</label>
            <input type="text" name="login_id" required>
            <br><br>
            <label for="pwd">Password:</label>
            <input type="password" name="pwd" required>
            <br><br>
            <input type="submit" value="Login">
        </form>
        <br>
        <button onclick="location.href='normal.php'">Login as Guest</button>
    </div>
</body>
</html>
