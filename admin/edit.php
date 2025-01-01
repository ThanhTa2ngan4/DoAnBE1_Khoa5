<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($className) {
  require_once "../app/model/$className.php";
});
$categoryModel = new Category();
$categories = $categoryModel->all();

$id = $_GET['id'];
$productModel = new Product();
$product = $productModel->find($id);

if (!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['description']) && !empty($_POST['image']) && !empty($_POST['category-id'])) {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $description = $_POST['description'];
  $image = $_POST['image'];
  $categoryId = $_POST['category-id'];
  if ($productModel->update($name, $price, $description, $image, $id, $categoryId))
    header("Location: http://localhost/khoa5_doan_be1/admin");
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
            <a class="nav-link" href="index.php">
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
            <a class="nav-link d-block" href="logout.html">
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
              <h2 class="tm-block-title d-inline-block">Edit Product</h2>
            </div>
          </div>
          <form action="edit.php" method="post" enctype="multipart/form-data" class="tm-edit-product-form">
            <div class="row tm-edit-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="form-group mb-3">
                  <label for="name">Name</label>
                  <input id="name" name="name" type="text" class="form-control validate" value="<?php echo $product['name'] ?>" required />
                </div>
                <div class="form-group mb-3">
                  <label for="price">Price</label>
                  <input id="price" name="price" type="number" class="form-control validate" value="<?php echo $product['price'] ?>" required />
                </div>
                <div class="form-group mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea class="form-control" id="description" name="description"><?php echo $product['description'] ?></textarea>
                </div>
                <div class="form-group mb-3">
                  <label for="image" class="form-label">Image</label>
                  <input type="text" class="form-control" id="image" name="image" value="<?php echo $product['image'] ?>">
                </div>
                <div class="form-group mb-3">
                  <label for="category">Category</label>
                  <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <?php
                    foreach ($categories as $category) :
                      $checked = (!empty($product['category_ids']) && in_array($category['id'], explode(',', $product['category_ids']))) ? 'checked' : '';
                    ?>
                      <input type="checkbox" class="btn-check" id="category-<?php echo $category['id'] ?>" autocomplete="off" value="<?php echo $category['id'] ?>" name="category-id[]" <?php echo $checked ?>>
                      <label class="btn btn-outline-primary" for="category-<?php echo $category['id'] ?>"><?php echo $category['name'] ?></label>
                    <?php
                    endforeach;
                    ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-2">
                  <button type="submit" class="btn btn-primary btn-block text-uppercase">UPDATE</button>
                </div>
                <div class="col-10">
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