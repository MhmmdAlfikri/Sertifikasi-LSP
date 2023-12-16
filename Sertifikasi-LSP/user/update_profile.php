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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['new_username'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['new_email'], ENT_QUOTES);

    $userId = $_SESSION['user_id'];
    $updateQuery = "UPDATE users SET username='$username', email='$email' WHERE id='$userId'";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: /login.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>