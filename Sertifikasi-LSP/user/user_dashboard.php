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

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: /user_dashboard.php");
    exit();
}

$query = "SELECT * FROM products";
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
    <title>User Dashboard</title>
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


    <h1>Welcome,
        <?php echo $_SESSION['username']; ?> (User)
    </h1>
    <p>This is the user dashboard.</p>

    <div class="container mt-4">
        <h2>Data Barang</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card">
                        <img src='/image/" . htmlspecialchars($row[' image_path']) . "' alt='Product Image' style='max-width: 200px;'>

                            <div class=" card-body">
                        <h5 class="card-title">
                            <?= $row['unit'] ?>
                        </h5>
                        <p class="card-text">Product Name:
                            <?= $row['name'] ?>
                        </p>
                        <p class="card-text">Product Category:
                            <?= $row['category'] ?>
                        </p>
                        
                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#sewaModal<?= $row['id'] ?>">Sewa</button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="sewaModal<?= $row['id'] ?>" tabindex="-1"
                aria-labelledby="sewaModalLabel<?= $row['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sewaModalLabel<?= $row['id'] ?>">Sewa
                                <?= $row['name'] ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                            <form action="sewa_barang.php" method="post">
                                <div class="mb-3">
                                    <label for="tanggalSewa<?= $row['id'] ?>" class="form-label">Tanggal Sewa</label>
                                    <input type="date" class="form-control" id="tanggalSewa<?= $row['id'] ?>"
                                        name="tanggalSewa" required>
                                </div>
                                <input type="hidden" name="productId" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-primary">Sewa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>