<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        .user-profile {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
            text-align: center;
        }

        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-control:disabled {
            background-color: #eee;
        }
    </style>
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

<div class="user-profile container">
    <h1>User Profile</h1>
    <form>
        <div class="mb-3 row">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="username" placeholder="<?php echo $_SESSION['username']; ?>" disabled>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="email" placeholder="<?php echo $_SESSION['username']; ?>" disabled>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="password" placeholder="******" disabled>
            </div>
        </div>
    </form>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
</div>

<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="update_profile.php">
                    <div class="mb-3">
                        <label for="new_username" class="form-label">New Username:</label>
                        <input type="text" class="form-control" id="new_username" name="new_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_email" class="form-label">New Email:</label>
                        <input type="text" class="form-control" id="new_email" name="new_email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>