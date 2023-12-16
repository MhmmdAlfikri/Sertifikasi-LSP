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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addProduct'])) {
  $productUnit = $_POST['productUnit'];
  $productName = $_POST['productName'];
  $productType = $_POST['productType'];
  $productCategory = $_POST['productCategory'];

  $targetDirectory = "./image/";
  $targetFile = $targetDirectory . basename($_FILES["productImage"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Buat direktori target jika tidak ada
  if (!file_exists($targetDirectory)) {
    mkdir($targetDirectory, 0755, true);
  }

  $check = getimagesize($_FILES["productImage"]["tmp_name"]);
  if ($check === false) {
    echo "File is not an image.";
    $uploadOk = 0;
  }

  if (file_exists($targetFile)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }

  if ($_FILES["productImage"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

  $allowedFileFormats = ["jpg", "png", "jpeg", "gif"];
  if (!in_array($imageFileType, $allowedFileFormats)) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }

  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  } else {
    if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
      echo "The file " . htmlspecialchars(basename($_FILES["productImage"]["name"])) . " has been uploaded.";

      $imagePath = "image/" . basename($_FILES["productImage"]["name"]);
      $query = "INSERT INTO products (unit, name, type, image_path, category) VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssss", $productUnit, $productName, $productType, $imagePath, $productCategory);

      if ($stmt->execute()) {
        header("Location: /admin/admin_dashboard.php");
        exit();
      } else {
        echo "Error: " . $stmt->error;
      }

      $stmt->close();
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['searchName'])) {
  // Sanitasi input pencarian
  $searchName = htmlspecialchars($_GET['searchName'], ENT_QUOTES);

  $query = "SELECT * FROM products WHERE name LIKE '%$searchName%'";
  $result = $conn->query($query);
} else {
  
  $query = "SELECT * FROM products";
  $result = $conn->query($query);
}
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
      background-color: #343a40;
      color: white;
      border: none;
    }

    .list-group-item:hover {
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
              <a class="nav-link active" href="./admin_dashboard.php">
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle"
              aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
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

        <div class="container-fluid">
          <h2 class="mt-4">Product List</h2>
          <!-- Form Pencarian -->
          <form class="mb-3" method="get" action="admin_dashboard.php">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search by Product Name" name="searchName">
              <button class="btn btn-primary" type="submit">Search</button>
            </div>
          </form>

          <table id="product-table" class="table table-bordered">
            <thead>
              <tr>
                <th>Unit</th>
                <th>Name</th>
                <th>Type</th>
                <th>Category</th>
                <th>Image</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($result as $row): ?>
                <tr>
                  <td>
                    <?= $row["unit"] ?>
                  </td>
                  <td>
                    <?= $row["name"] ?>
                  </td>
                  <td>
                    <?= $row["type"] ?>
                  </td>
                  <td>
                    <?= $row["category"] ?>
                  </td>
                  <td><img src="<?= $row["image_path"] ?>" alt="Product Image" style="max-width: 100px;">
                  </td>
                  <td>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                      data-bs-target="#editProductModal_<?= $row["id"] ?>">Edit</button>

                    <form method="post" action="delete_produk.php"
                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                      <input type="hidden" name="productId" value="<?= $row["id"] ?>">
                      <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                  </td>
                </tr>

                <div class="modal fade" id="editProductModal_<?= $row["id"] ?>" tabindex="-1"
                  aria-labelledby="editProductModalLabel_<?= $row["id"] ?>" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel_<?= $row["id"] ?>">Edit
                          Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="edit_produk.php" method="POST" enctype="multipart/form-data">
                          <input type="hidden" name="editProductId" value="<?= $row["id"] ?>">
                          <div class="mb-3">
                            <label for="editProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="editProductName" name="editProductName"
                              value="<?= $row["name"] ?>" required>
                          </div>
                          <button type="submit" class="btn btn-primary" name="editProduct">Save
                            Changes</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </tbody>
          </table>

          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            Add Product
          </button>

          <a href="admin_dashboard.php" class="btn btn-success">Show All Products</a>

          <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="admin_dashboard.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label for="productUnit" class="form-label">Product Unit</label>
                      <input type="text" class="form-control" id="productUnit" name="productUnit" required>
                    </div>
                    <div class="mb-3">
                      <label for="productName" class="form-label">Product Name</label>
                      <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>
                    <div class="mb-3">
                      <label for="productType" class="form-label">Product Type</label>
                      <input type="text" class="form-control" id="productType" name="productType" required>
                    </div>
                    <div class="mb-3">
                      <label for="productCategory" class="form-label">Product Category</label>
                      <select class="form-select" id="productCategory" name="productCategory" required>
                        <option value="Pengeboran Kecil">Peralatan Pengeboran Kecil</option>
                        <option value="Pengeboran Listrik">Peralatan Pengeboran Listrik</option>
                        <option value="Pengeboran Berdiri">Peralatan Pengeboran Berdiri</option>
                        <option value="Pengeboran dengan Fungsi Palu">Peralatan Pengeboran
                          dengan Fungsi Palu</option>
                        <option value="Pengeboran Khusus Logam">Peralatan Pengeboran Khusus
                          Logam</option>
                        <option value="Pengeboran Serbaguna">Peralatan Pengeboran Serbaguna
                        </option>
                        <option value="Pengeboran untuk Material Keras">Peralatan Pengeboran
                          untuk Material Keras</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="productImage" class="form-label">Product Image</label>
                      <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*"
                        required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="addProduct">Add
                      Product</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
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