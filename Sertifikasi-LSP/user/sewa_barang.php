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
    $tanggalSewa = $_POST['tanggalSewa'];
    $productId = $_POST['productId'];
    $userId = $_SESSION['user_id'];

    $rentalCountQuery = "SELECT COUNT(*) AS rental_count FROM rental_history WHERE user_id = '$userId'";
    $rentalCountResult = $conn->query($rentalCountQuery);

    if (!$rentalCountResult) {
        die("Error: " . $conn->error);
    }

    $rentalCountRow = $rentalCountResult->fetch_assoc();
    $rentalCount = $rentalCountRow['rental_count'];

    if ($rentalCount < 2) {
        $returnDate = date('Y-m-d', strtotime($tanggalSewa . ' + 5 days'));

        $insertQuery = "INSERT INTO rental_history (user_id, product_id, rental_date, return_date)
                        VALUES ('$userId', '$productId', '$tanggalSewa', '$returnDate')";

        if ($conn->query($insertQuery) === TRUE) {
            echo "Rental successful";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "You can only rent a maximum of 2 items.";
    }
}

$userId = $_SESSION['user_id'];
$query = "SELECT rental_history.id, products.name AS product_name, rental_history.rental_date, rental_history.return_date
            FROM rental_history
            INNER JOIN products ON rental_history.product_id = products.id
            WHERE rental_history.user_id = '$userId'";
$result = $conn->query($query);

if (!$result) {
    die("Error: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">DrillTools</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ml-auto">
                    <a class="nav-link" aria-current="page" href="./user_dashboard.php">Home</a>
                    <a class="nav-link" href="/logout.php">Logout</a>
                    <a class="nav-link" href="./sewa_barang.php">Sewa</a>
                    <a class="nav-link" href="./user_profile.php">
                        <?php echo $_SESSION['username']; ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Rental History</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Rental Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?= $row['id'] ?>
                        </td>
                        <td>
                            <?= $row['product_name'] ?>
                        </td>
                        <td>
                            <?= $row['rental_date'] ?>
                        </td>
                        <td>
                            <?= $row['return_date'] ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>