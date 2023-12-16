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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editProduct'])) {
    $productId = $_POST['editProductId'];
    $productName = $_POST['editProductName'];

    $query = "UPDATE products SET name=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $productName, $productId); 

    if ($stmt->execute()) {
        header("Location: /admin/admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>