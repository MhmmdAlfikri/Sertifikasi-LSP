<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: /login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "auth";

    $conn = new mysqli($host, $username, $password, $database);

    $email = $_POST['email'];
    $newUsername = $_POST['username'];

    $checkQuery = "SELECT * FROM users WHERE username = '$newUsername' AND username != '{$_SESSION['username']}'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        $_SESSION['error'] = 'Username already in use. Please choose a different username.';
        header("Location: ./user.php");
        exit();
    }

    $updateQuery = "UPDATE users SET email = '$email', username = '$newUsername' WHERE username = '{$_SESSION['username']}'";
    $updateResult = $conn->query($updateQuery);

    if ($updateResult === false) {
        die("Error in SQL query: " . $conn->error);
    }

    $conn->close();

    header("Location: ./user.php");
    exit();
} else {
    header("Location: ./user.php");
    exit();
}
?>
