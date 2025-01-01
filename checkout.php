<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: login.php");
    exit;
}
if (empty($_SESSION['cart'])) {
    header("Location: index.php"); 
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-place-order'])) {
    $customerName = $_POST['customer_name'] ?? '';
    $customerPhone = $_POST['customer_phone'] ?? '';
    $customerAddress = $_POST['customer_address'] ?? '';
    if ($customerName && $customerPhone && $customerAddress) {
        $orderTotal = 0;
        if (!empty($_SESSION['cart'])) {
            $orderTotal = array_reduce($_SESSION['cart'], function ($sum, $item) {
                return $sum + $item['price'] * $item['quantity'];
            }, 0);
        }
        $_SESSION['order'] = [
            'customerName' => $customerName,
            'customerPhone' => $customerPhone,
            'customerAddress' => $customerAddress,
            'orderTotal' => $orderTotal,
            'cart' => $_SESSION['cart'] 
        ];
    } else {
        $error = "Please fill in all the fields.";
    }
}
if (isset($_GET['action']) && $_GET['action'] === 'clear-cart') {
    unset($_SESSION['cart']);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center mb-4">Hóa Đơn Thanh Toán</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Nếu đơn hàng thành công, hiển thị hóa đơn -->
    <?php if (isset($_SESSION['order'])): ?>
        <div class="alert alert-success">
            <strong>Đặt hàng thành công!</strong><br>
            Tổng Tiền: <?php echo number_format($_SESSION['order']['orderTotal']); ?>đ
        </div>

        <h4>Thông Tin Khách Hàng</h4>
        <p><strong>Họ và Tên:</strong> <?php echo htmlspecialchars($_SESSION['order']['customerName']); ?></p>
        <p><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($_SESSION['order']['customerPhone']); ?></p>
        <p><strong>Địa Chỉ Giao Hàng:</strong> <?php echo htmlspecialchars($_SESSION['order']['customerAddress']); ?></p>

        <h4>Chi Tiết Đơn Hàng</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Hình Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Số Lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($_SESSION['order']['cart'])) {
                    foreach ($_SESSION['order']['cart'] as $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        ?>
                        <tr>
                            <td><img src="public/images/<?php echo htmlspecialchars($item['image']); ?>" class="img-thumbnail" width="80"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price']); ?>đ</td>
                            <td><?php echo number_format($itemTotal); ?>đ</td>
                        </tr>
                    <?php endforeach;
                }
                ?>
            </tbody>
        </table>

        <h4 class="text-end">Tổng Tiền: <span class="text-success"><?php echo number_format($_SESSION['order']['orderTotal']); ?>đ</span></h4>

        <div class="text-center mt-4">
            <a href="?action=clear-cart" class="btn btn-primary">Quay Về Trang Chủ</a>
            <a href="?action=clear-cart" class="btn btn-secondary">Quay Về Giỏ Hàng</a>
        </div>

    <?php else: ?>
        <!-- Nếu chưa đặt hàng, hiển thị form thanh toán -->
        <form action="checkout.php" method="post">
            <div class="mb-3">
                <label for="customer_name" class="form-label">Họ và Tên</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="mb-3">
                <label for="customer_phone" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
            </div>
            <div class="mb-3">
                <label for="customer_address" class="form-label">Địa Chỉ Giao Hàng</label>
                <input type="text" class="form-control" id="customer_address" name="customer_address" required>
            </div>

            <h4 class="mb-4">Giỏ Hàng</h4>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Hình Ảnh</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Số Lượng</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item):
                            ?>
                            <tr>
                                <td><img src="public/images/<?php echo htmlspecialchars($item['image']); ?>" class="img-thumbnail" width="80"></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price']); ?>đ</td>
                                <td><?php echo number_format($item['price'] * $item['quantity']); ?>đ</td>
                            </tr>
                        <?php endforeach;
                    }
                    ?>
                </tbody>
            </table>

            <h5 class="text-end">Tổng Tiền: 
                <span class="text-success">
                    <?php
                    $total = 0;
                    if (!empty($_SESSION['cart'])) {
                        $total = array_reduce($_SESSION['cart'], function ($sum, $item) {
                            return $sum + $item['price'] * $item['quantity'];
                        }, 0);
                    }
                    echo number_format($total);
                    ?>đ
                </span>
            </h5>

            <button type="submit" class="btn btn-success w-100" name="btn-place-order">Đặt Hàng</button>
        </form>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
