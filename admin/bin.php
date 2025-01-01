<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($className) {
  require_once "../app/model/$className.php";
});

$productModel = new Product();

if (isset($_POST['btn-restore'])) {
  $productModel->restore($_POST['btn-restore']);
}
//Khôi phục nhiều 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'restore-selected') {
  if (isset($_POST['id-delete']) && !empty($_POST['id-delete'])) {
    $productIds = json_decode($_POST['id-delete']);
    $productModel->restoreAll($productIds);
  }
}

if (isset($_POST['btn-delete'])) {
  $productModel->delete($_POST['btn-delete']);
}
//Xóa nhiều
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete-selected') {
  if (isset($_POST['id-delete']) && !empty($_POST['id-delete'])) {
    $productIds = json_decode($_POST['id-delete']);
    $productModel->deleteAll($productIds);
  }
}


if (isset($_POST['btn-empty'])) {
  $productModel->deleteAll($_POST['id-delete']);
}

$products = $productModel->allBin();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Trang sản phẩm</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
  <link rel="stylesheet" href="../css/fontawesome.min.css" />
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/templatemo-style.css">
</head>

<body id="reportsPage">
  <nav class="navbar navbar-expand-xl">
    <div class="container h-100">
      <a class="navbar-brand" href="index.php">
        <h1 class="tm-site-title mb-0">Product Admin</h1>
      </a>
      <!-- các phần còn lại của menu -->
    </div>
  </nav>
  <div class="container mt-5">
    <div class="row tm-content-row">
      <div class="col-sm-12 col-md-12 col-lg-8 col-xl-12 tm-block-col">
        <div class="tm-bg-primary-dark tm-block tm-block-products">
          <h2 class="tm-block-title">Bin</h2>
          <!-- bảng hiển thị sản phẩm trong thùng rác -->
          <div class="tm-product-table-container">
            <table class="table table-hover tm-table-small tm-product-table">
              <thead>
                <tr>
                  <th><input type="checkbox" name="" id="" class="form-check-input" onclick="checkAll(this)"></th>
                  <th scope="col">ID</th>
                  <th scope="col">TÊN SẢN PHẨM</th>
                  <th scope="col">Ảnh</th>
                  <th scope="col">GIÁ</th>
                  <th scope="col">HÀNH ĐỘNG</th>
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
                      <!-- Form phục hồi sản phẩm -->
                      <form action="bin.php" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn phục hồi sản phẩm này?')">
                        <input type="hidden" name="btn-restore" value="<?php echo $product['id'] ?>">
                        <button type="submit" class="btn btn-outline-warning">RESTORE</button>
                      </form>
                      <br>
                      <!-- Form xóa sản phẩm -->
                      <form action="bin.php" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này?')">
                        <input type="hidden" name="btn-delete" value="<?php echo $product['id'] ?>">
                        <button type="submit" class="btn btn-outline-danger">DELETE</button>
                      </form>
                    </td>
                  </tr>
                <?php
                endforeach;
                ?>
              </tbody>
            </table>
          </div>
          <div class="row">
    <div class="col-12">
        <div class="d-flex gap-3">
            <form action="bin.php" method="post" id="delete-selected-form" class="flex-grow-2" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tất cả?')">
                <input type="hidden" name="action" value="delete-selected">
                <button type="submit" class="btn btn-primary btn-block text-uppercase">DELETE SELECTED</button>
            </form>
            
            <form action="bin.php" method="post" id="restore-selected-form" class="flex-grow-2" onsubmit="return confirm('Bạn có chắc chắn muốn phục hồi tất cả?')">
                <input type="hidden" name="action" value="restore-selected">
                <button type="submit" class="btn btn-primary btn-block text-uppercase">RESTORE SELECTED</button>
            </form>
            
            <a href="products.php" class="btn btn-primary btn-block text-uppercase flex-grow-1 text-center">BACK</a>
        </div>
    </div>
</div>

        </div>
      </div>
    </div>
  </div>
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script>
    function checkAll(thisCheck) {
      const checkId = document.querySelectorAll('.check-id');
      checkId.forEach(element => {
        element.checked = thisCheck.checked;
      });
    }
    // Xóa nhiều
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
    //Restore nhiều
    document.getElementById('restore-selected-form').addEventListener('submit', function(e) {
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