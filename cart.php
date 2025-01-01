<?php
session_start();
// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: login.php");
    exit;
}
// Thêm sản phẩm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-add-to-cart'])) {
    $productId = $_POST['product_id'] ?? null;
    $productName = $_POST['product_name'] ?? '';
    $productPrice = $_POST['product_price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;
    $productImage = $_POST['product_image'] ?? 'default.jpg'; // Nếu không có ảnh, đặt ảnh mặc định
    $productDescription = $_POST['product_description'] ?? 'No description'; // Nếu không có mô tả, đặt mô tả mặc định

    // Kiểm tra nếu giỏ hàng chưa được tạo
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    if (isset($_SESSION['cart'][$productId])) {
        // Cập nhật số lượng nếu sản phẩm đã có trong giỏ hàng
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Thêm sản phẩm mới vào giỏ hàng
        $_SESSION['cart'][$productId] = [
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => $quantity,
            'image' => $productImage, // Lưu ảnh sản phẩm
            'description' => $productDescription, // Lưu mô tả sản phẩm
        ];
    }

    // Điều hướng trở lại giỏ hàng
    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<!-- Navbar start -->
<div class="container-fluid fixed-top">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="index.php" class="navbar-brand">
                    <h1 class="text-primary display-6">Fruitables</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="shop.php" class="nav-item nav-link">Shop</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <a href="cart.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"></span>
                        </a>
                        <!-- Đăng nhập -->
                        <?php if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true): ?>
                            <div class="d-flex align-items-center">
                                <span class="text-dark me-3"><?php echo $_SESSION['username']; ?></span>
                                <a href="logout.php" class="btn btn-danger">Đăng xuất</a>
                            </div>
                        <?php else: ?>
                            <a href="login.php" class="my-auto">
                                <i class="fas fa-user fa-2x"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

<div class="container my-5">
    <h1 class="text-center mb-4" style="margin-top: 100px;">Your Shopping Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-warning text-center" role="alert">
            Your cart is empty. <a href="index.php" class="alert-link">Continue Shopping</a>.
        </div>
    <?php else: ?>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                <tr>
                    <td>
                        <img src="public/images/<?php echo htmlspecialchars($item['image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             class="img-thumbnail" width="80">
                    </td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo number_format($item['price']); ?>đ</td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'] * $item['quantity']); ?>đ</td>
                    <td>
                        <form action="remove_from_cart.php" method="post">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end">
            <h4>Total: 
                <span class="text-success">
                    <?php 
                    $total = array_reduce($_SESSION['cart'], function ($sum, $item) {
                        return $sum + $item['price'] * $item['quantity'];
                    }, 0);
                    echo number_format($total); 
                    ?>đ
                </span>
            </h4>
            <a href="checkout.php" class="btn btn-success">Tiến hành thanh toán</a>
        </div>
    <?php endif; ?>
</div>
<div style="text-align: center;">

     <a href="index.php" class="btn btn-success">Quay về trang chủ</a>
     <a href="shop.php" class="btn btn-success">Quay về trang sản phẩm</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
