<?php
class Order extends Database
{
    // Thêm đơn hàng vào bảng `orders`
    public function add($data)
    {
        $sql = parent::$connection->prepare("INSERT INTO `orders` (`user_id`, `total_amount`, `status`, `created_at`) 
                                             VALUES (?, ?, ?, ?)");
        $sql->bind_param('idss', $data['user_id'], $data['total_amount'], $data['status'], $data['created_at']);
        $sql->execute();
        return parent::$connection->insert_id; // Trả về ID của đơn hàng mới tạo
    }

    // Thêm sản phẩm vào bảng `order_items`
    public function addOrderItem($orderId, $productId, $quantity, $price)
    {
        $sql = parent::$connection->prepare("INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`) 
                                             VALUES (?, ?, ?, ?)");
        $sql->bind_param('iiid', $orderId, $productId, $quantity, $price);
        $sql->execute();
    }

    // Cập nhật số lượng tồn kho của sản phẩm
    public function updateStock($productId, $quantity)
    {
        $sql = parent::$connection->prepare("UPDATE `products` SET `stock` = `stock` - ? WHERE `id` = ?");
        $sql->bind_param('ii', $quantity, $productId);
        $sql->execute();
    }
}
