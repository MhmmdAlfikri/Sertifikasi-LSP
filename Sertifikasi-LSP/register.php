<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (email, username, password, role) VALUES ('$email', '$username', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";

        if ($role === 'admin') {
            header("Location: /admin/admin_dashboard.php");
            exit();
        } elseif ($role === 'user') {
            header("Location: /user/user_dashboard.php");
            exit();
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: auto;
            margin-top: 100px;
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        label {
            margin-bottom: 5px;
            color: #555;
        }

        input,
        select {
            margin-bottom: 15px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <form action="./register.php" method="POST" class="container">
        <h2>Register</h2>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" class="form-control" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
        <p class="login-link">Have an account? <a href="login.php">Login</a></p>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>