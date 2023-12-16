<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "auth";

$conn = new mysqli($host, $username, $password, $database);

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result === false) {
    die("Error in SQL query: " . $conn->error);
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
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

        .list-group-item {
            background-color: white;
            color: black;
            border: none;
        }

        .list-group-item:hover {
            color: white;
            background-color: #007bff;
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

                <div class="container mt-5">
                    <div class="row">

                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary text-light mb-2" data-bs-toggle="modal"
                                data-bs-target="#tambahUserModal">
                                Tambah User
                            </button>

                            <div class="input-group mb-2">
                                <input type="text" class="form-control rounded-2" id="searchKeyword"
                                    placeholder="Cari user...">
                                <button type="submit" class="btn btn-primary text-light">Cari</button>
                            </div>

                            <div class="modal fade" id="tambahUserModal" tabindex="-1"
                                aria-labelledby="tambahUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="tambahUserModalLabel">Tambah User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="./tambah_user.php" method="POST">
                                                <div class="form-group mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username"
                                                        name="username" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="password">Password:</label>
                                                    <input type="password" id="password" name="password"
                                                        class="form-control" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="role">Role:</label>
                                                    <select id="role" name="role" class="form-control" required>
                                                        <option value="user">User</option>
                                                        <option value="admin">Admin</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2">
                                <div class="card-header bg-primary text-white">
                                    Detail User
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Email</th>
                                                <th scope="col">Username</th>
                                                <th scope="col">Role</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            if (!empty($users)) {
                                                foreach ($users as $user) {
                                                    echo "<tr>";
                                                    echo "<td>" . $user["email"] . "</td>";
                                                    echo "<td>" . $user["username"] . "</td>";
                                                    echo "<td>" . $user["role"] . "</td>";
                                                    echo "<td>";
                                                    echo "<button type='button' class='btn btn-primary text-light' data-bs-toggle='modal' data-bs-target='#editUserModal'>
                                                                Edit</button>";
                                                    echo "<button type='button' class='btn btn-danger'>Delete</button>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>User not found</td></tr>";
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="modal fade" id="editUserModal" tabindex="-1"
                                aria-labelledby="editUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel">Tambah User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="./edit_user.php" method="POST">
                                                <div class="form-group mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username"
                                                        name="username">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email">
                                                </div>

                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <nav aria-label="...">
                                <ul class="pagination">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item active" aria-current="page">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>