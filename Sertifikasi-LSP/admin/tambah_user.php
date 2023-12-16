<?php

session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "auth";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', 'password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "User added successfully";
    } else {
        $_SESSION['error_message'] = "Error adding user: " . $conn->error;
    }

    header("Location: user.php");
    exit();
}

$conn->close();
?>
