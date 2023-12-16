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

$query = "SELECT rental_history.id, users.username, products.name AS product_name, rental_history.rental_date, rental_history.return_date
            FROM rental_history
            INNER JOIN users ON rental_history.user_id = users.id
            INNER JOIN products ON rental_history.product_id = products.id
            ORDER BY rental_history.rental_date DESC"; // You can adjust the ORDER BY clause as needed
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
    <title>All Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        #content {
            padding: 20px;
        }

        .table {
            margin-top: 20px;
        }

        body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }

    #sidebar {
      height: 100vh;
      background-color: #343a40;
      padding-top: 20px;
      padding-right: 10px;
    }

    #content {
      padding: 20px;
    }

    .navbar {
      background-color: #007bff;
    }

    .navbar-dark .navbar-toggler-icon {
      background-color: white;
    }

    .navbar-nav {
      margin-left: auto;
    }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="./admin_dashboard.php">
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="./rental_produk.php">
                                Rentals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./user.php">
                                Users
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main id="content" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <nav class="navbar navbar-dark bg-primary">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarToggle">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Welcome, Admin!</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/logout.php">Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            
                <h2 class="mt-4">All Rentals</h2>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>Rental Date</th>
                                <th>Return Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['product_name'] . "</td>";
                                echo "<td>" . $row['rental_date'] . "</td>";
                                echo "<td>" . $row['return_date'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>