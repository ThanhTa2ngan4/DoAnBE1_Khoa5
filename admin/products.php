<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($className) {
  require_once "../app/model/$className.php";
});

$productModel = new Product();

//Xóa nhiều
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete-selected') {
  if (isset($_POST['id-delete'])) {
    $productIds = json_decode($_POST['id-delete']);

    foreach ($productIds as $productId) {
      $productModel->updateStatus($productId, 0);
    }
  }
}
//Xóa từng cái
if (isset($_POST['product-id'])) {
  $productModel->bin($_POST['product-id']);
}
$products = $productModel->all();

$categoryModel = new Category();
$categories = $categoryModel->all();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Trang sản phẩm</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
  <!-- https://fonts.google.com/specimen/Roboto -->
  <link rel="stylesheet" href="../css/fontawesome.min.css" />
  <!-- https://fontawesome.com/ -->
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <!-- https://getbootstrap.com/ -->
  <link rel="stylesheet" href="../css/templatemo-style.css">
  <!--
	Product Admin CSS Template
	https://templatemo.com/tm-524-product-admin
	-->
</head>
<body id="reportsPage">
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
            <a class="nav-link active" href="products.html">
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
  <div class="container mt-5">
    <div class="row tm-content-row">
      <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 tm-block-col">
        <div class="tm-bg-primary-dark tm-block tm-block-products">
          <h2 class="tm-block-title">Products</h2>
          <!-- table container -->
          <div class="tm-product-table-container">
            <table class="table table-hover tm-table-small tm-product-table">
              <thead>
                <tr>
                  <th><input type="checkbox" name="" id="" class="form-check-input" onclick="checkAll(this)"></th>
                  <th scope="col">ID</th>
                  <th scope="col">PRODUCT NAME</th>
                  <th scope="col">PRICE</th>
                  <th scope="col">CATEGORY</th>
                  <th scope="col" style="text-align: center;">ACTION</th>
                  <th scope="col">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($products as $product) :
                ?>
                  <tr>
                    <td><input type="checkbox" name="id-delete[]" class="form-check-input check-id" value="<?php echo $product['id'] ?>"></td>
                    <td><?php echo $product['id'] ?></td>
                    <td><?php echo $product['name'] ?></td>
                    <td><?php echo $product['price'] ?></td>
                    <td>
                      <h5>
                        <?php
                        echo (!empty($product['category_name'])) ? implode(array_map(function ($e) {
                          return "<span class='badge text-bg-warning'>$e</span>";
                        }, explode(',', $product['category_name']))) : '';
                        ?>
                      </h5>
                    </td>
                    <td>
<div class="row">
  <div class="col-6">
  <a href="edit.php?id=<?php echo $product['id'] ?>" class="btn btn-outline-primary">Edit</a>

  </div>
  <div class="col-6">
  <form action="products.php" method="post" onsubmit="return confirm('Xóa không?')">
                        <input type="hidden" name="product-id" value="<?php echo $product['id'] ?>">
                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                      </form>
  </div>
</div>
                    </td>
                  </tr>
                <?php
                endforeach;
                ?>
              </tbody>
            </table>
          </div>
          <!-- table container -->
            <div>
              <form action="products.php" method="post" id="delete-selected-form">
                <input type="hidden" name="action" value="delete-selected">
                <button type="submit" class="btn btn-primary btn-block text-uppercase mb-3">DELETE SELECTED</button>
              </form>
              <a
                href="add.php"
                class="btn btn-primary btn-block text-uppercase mb-3">Add new product</a>
              <a
                href="bin.php"
                class="btn btn-primary btn-block text-uppercase mb-3">Move to Bin</a>
            </div>        
        </div>
      </div>
<!-- Category -->
      <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 tm-block-col">
        <div class="tm-bg-primary-dark tm-block tm-block-product-categories">
          <h2 class="tm-block-title">Categories</h2>
          <div class="tm-product-table-container">
            <table class="table tm-table-small tm-product-table">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col" style="text-align: center;">PRODUCT NAME</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($categories as $category) : ?>
                  <tr >
                    <td><?php echo $category['id'] ?></td>
                    <td class="tm-product-name">
                      <a class="nav-link" href="category.php?id=<?php echo $category['id'] ?>"><?php echo $category['name'] ?></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <!-- table container -->
          <button class="btn btn-primary btn-block text-uppercase mb-3">
            Add new category
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <!-- https://jquery.com/download/ -->
  <script src="js/bootstrap.min.js"></script>
  <!-- https://getbootstrap.com/ -->
  <script>
    function checkAll(thisCheck) {
      const checkId = document.querySelectorAll('.check-id');
      checkId.forEach(element => {
        element.checked = thisCheck.checked;
      });
    }
    document.getElementById('delete-selected-form').addEventListener('submit', function(e) {
      const selectedIds = [];
      document.querySelectorAll('.check-id:checked').forEach(checkbox => {
        selectedIds.push(checkbox.value);
      });
      if (selectedIds.length > 0) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id-delete';
        input.value = JSON.stringify(selectedIds);
        this.appendChild(input);
      } else {
        e.preventDefault();
        alert('Please select at least one product.');
      }
    });
  </script>
</body>

</html>