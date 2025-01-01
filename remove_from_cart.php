<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = $_POST['key'];

    // Xóa sản phẩm trong giỏ hàng
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
    }

    // Điều chỉnh lại giỏ hàng
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header('Location: cart.php');
    exit;
}
?>
