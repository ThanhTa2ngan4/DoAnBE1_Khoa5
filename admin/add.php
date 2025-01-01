<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($className) {
  require_once "../app/model/$className.php";
});
$categoryModel = new Category();
$categories = $categoryModel->all();
if (!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['description']) && !empty($_POST['image']) && !empty($_POST['category-id'])) {
  $productModel = new Product();
  $name = $_POST['name'];
  $price = $_POST['price'];
  $description = $_POST['description'];
  $image = $_POST['image'];
  $categories = $_POST['category-id']; // Đây là mảng

  // Xử lý việc thêm sản phẩm cùng với danh mục
  if ($productModel->add($name, $price, $description, $image, $categories)) {
    $_SESSION['notification'] = "Thêm thành công!";
    header("Location: http://localhost/khoa5_doan_be1/admin/products.php");
    exit();
  } else {
    $_SESSION['notification'] = "Thêm thất bại!";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Add Product - Dashboard HTML Template</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
  <!-- https://fonts.google.com/specimen/Roboto -->
  <link rel="stylesheet" href="../css/fontawesome.min.css" />
  <!-- https://fontawesome.com/ -->
  <link rel="stylesheet" href="jquery-ui-datepicker/jquery-ui.min.css" type="text/css" />
  <!-- http://api.jqueryui.com/datepicker/ -->
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <!-- https://getbootstrap.com/ -->
  <link rel="stylesheet" href="../css/templatemo-style.css">
  <!--
	Product Admin CSS Template
	https://templatemo.com/tm-524-product-admin
	-->
</head>
<style>
  .image-preview-container {
    width: 100%;
    height: auto;
    border: 2px dashed #ccc;
    border-radius: 10px;
    padding: 10px;
    display: flex;
    align-items: center;
    background-color: #fff6;
  }

  .image-preview {
    max-width: 100%;
    height: auto;
    object-fit: contain;
  }
</style>

<body>
  <nav class="navbar navbar-expand-xl">
    <div class="container h-100">
      <a class="navbar-brand" href="index.php">
        <h1 class="tm-site-title mb-0">Product Admin</h1>
      </a>
      <button
        class="navbar-toggler ml-auto mr-0"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <i class="fas fa-bars tm-nav-icon"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto h-100">
          <li class="nav-item">
            <a class="nav-link" href="index.html">
              <i class="fas fa-tachometer-alt"></i> Dashboard
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="far fa-file-alt"></i>
              <span> Reports <i class="fas fa-angle-down"></i> </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Daily Report</a>
              <a class="dropdown-item" href="#">Weekly Report</a>
              <a class="dropdown-item" href="#">Yearly Report</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="products.php">
              <i class="fas fa-shopping-cart"></i> Products
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="accounts.html">
              <i class="far fa-user"></i> Accounts
            </a>
          </li>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="fas fa-cog"></i>
              <span> Settings <i class="fas fa-angle-down"></i> </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Profile</a>
              <a class="dropdown-item" href="#">Billing</a>
              <a class="dropdown-item" href="#">Customize</a>
            </div>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link d-block" href="login.html">
              Admin, <b>Logout</b>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!--  -->
  <div class="container tm-mt-big tm-mb-big">
    <div class="row">
      <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
        <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
          <div class="row">
            <div class="col-12">
              <h2 class="tm-block-title d-inline-block">Add Product</h2>
            </div>
          </div>
          <form action="add.php" method="post" enctype="multipart/form-data" class="tm-edit-product-form">
            <div class="row tm-edit-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="form-group mb-3">
                  <label for="name">Name</label>
                  <input id="name" name="name" type="text" class="form-control validate" required />
                </div>
                <div class="form-group mb-3">
                  <label for="price">Price</label>
                  <input id="price" name="price" type="number" class="form-control validate" required />
                </div>
                <div class="form-group mb-3">
                  <label for="description">Description</label>
                  <textarea id="description" name="description" class="form-control validate" rows="15" required></textarea>
                </div>
                <div class="form-group mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="text" class="form-control" id="image" name="image">
                </div>
                <div class="form-group mb-3">
                  <label for="category">Category</label>
                  <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <?php
                    foreach ($categories as $category) :
                    ?>
                      <input type="checkbox" class="btn-check" id="category-<?php echo $category['id'] ?>" autocomplete="off" value="<?php echo $category['id'] ?>" name="category-id[]">
                      <label class="btn btn-outline-primary" for="category-<?php echo $category['id'] ?>"><?php echo $category['name'] ?></label>
                    <?php
                    endforeach;
                    ?>
                  </div>
                </div>
              </div>
             <div class="row">
              <div class="col-3">
              <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Product Now</button>
              </div>
              <div class="col-9">
              <a href="products.php" class="btn btn-primary btn-block text-uppercase ">BACK</a>
              </div>
             </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
  <script src="js/jquery-3.3.1.min.js"></script>
  <!-- https://jquery.com/download/ -->
  <script src="jquery-ui-datepicker/jquery-ui.min.js"></script>
  <!-- https://jqueryui.com/download/ -->
  <script src="js/bootstrap.min.js"></script>
  <!-- https://getbootstrap.com/ -->
  <script>
    $(function() {
      $("#expire_date").datepicker();
    });
  </script>
</body>

</html>