<?php
class Product extends Database
{
    public function all()
    {
        // 2. Tạo câu query
        // $sql = parent::$connection->prepare('SELECT * from `products`');
        $sql = parent::$connection->prepare('SELECT `products`.*, GROUP_CONCAT(`categories`.`name`) AS category_name
                                            FROM `products`
                                            LEFT JOIN `category_product`
                                            ON `products`.`id` = `category_product`.`product_id`
                                            LEFT JOIN `categories`
                                            ON `categories`.`id` = `category_product`.`category_id`
                                            WHERE `products`.`status`=1
                                            GROUP BY `products`.`id`');
        // 3 & 4
        return parent::select($sql);
    }
    public function allBin()
    {
        // 2. Tạo câu query
        // $sql = parent::$connection->prepare('SELECT * from `products`');
        $sql = parent::$connection->prepare('SELECT `products`.*
                                            FROM `products`
                                            WHERE `products`.`status`=0');
        // 3 & 4
        return parent::select($sql);
    }

    public function find($id)
    {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("SELECT `products`.*, GROUP_CONCAT(`category_product`.`category_id`) AS 'category_ids'
                                            FROM `products`
                                            LEFT JOIN `category_product`
                                            ON `products`.`id` = `category_product`.`product_id`
                                            WHERE `id`=?
                                            GROUP BY `products`.`id`");
        $sql->bind_param('i', $id);
        // 3 & 4
        return parent::select($sql)[0];
    }

    public function findIds($productIds)
    {
        // Tạo chuỗi kiểu ?,?,?
        $insertPlace = str_repeat("?,", count($productIds) - 1) . "?";
        // Tạo chuỗi iiiiiiii
        $insertType = str_repeat('i', count($productIds) * 2);

        // 2. Tạo câu query
        $sql = parent::$connection->prepare("SELECT * FROM `products` WHERE `id` IN ( $insertPlace ) ORDER BY FIELD(id, $insertPlace) DESC");
        $sql->bind_param($insertType, ...$productIds, ...$productIds);
        // 3 & 4
        return parent::select($sql);
    }



    public function findByCategory($id, $limit = '')
    {
        $limit = ($limit != '') ? "LIMIT $limit" : '';
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("SELECT *
                                            FROM `category_product`
                                            INNER JOIN `products`
                                            ON `category_product`.`product_id` = `products`.`id`
                                            WHERE `category_id`=?
                                            $limit");
        $sql->bind_param('i', $id);
        // 3 & 4
        return parent::select($sql);
    }

    public function findByCategory2($id, $page = 1, $perPage = 5)
{
    $offset = ($page - 1) * $perPage;
    $sql = parent::$connection->prepare("SELECT `products`.*, GROUP_CONCAT(`categories`.`name`) AS category_name
                                         FROM `category_product`
                                         INNER JOIN `products`
                                         ON `category_product`.`product_id` = `products`.`id`
                                         LEFT JOIN `categories`
                                         ON `categories`.`id` = `category_product`.`category_id`
                                         WHERE `category_product`.`category_id` = ?
                                         GROUP BY `products`.`id`
                                         LIMIT ?, ?");
    $sql->bind_param('iii', $id, $offset, $perPage);
    return parent::select($sql);
}

// Hàm tính tổng số sản phẩm trong danh mục để tính số trang
public function findTotalByCategory($id)
{
    $sql = parent::$connection->prepare("SELECT COUNT(*) AS total
                                         FROM `category_product`
                                         WHERE `category_id` = ?");
    $sql->bind_param('i', $id);
    $result = parent::select($sql);
    return $result[0]['total'];
}
    public function findByKeyWord($keyword, $page, $perPage)
    {
        $start = ($page - 1) * $perPage;
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("SELECT * FROM `products` WHERE `name` LIKE ? LIMIT ?,?");
        $keyword = "%{$keyword}%";
        $sql->bind_param('sii', $keyword, $start, $perPage);
        // 3 & 4
        return parent::select($sql);
    }

    public function findTotalByKeyWord($keyword)
    {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("SELECT COUNT(*) AS 'total' FROM `products` WHERE `name` LIKE ?");
        $keyword = "%{$keyword}%";
        $sql->bind_param('s', $keyword);
        // 3 & 4
        return parent::select($sql)[0]['total'];
    }

    public function add($name, $price, $description, $image, $categoryIds)
    {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("INSERT INTO `products`(`name`, `price`, `description`, `image`) VALUES (?, ?, ?, ?)");
        $sql->bind_param('siss', $name, $price, $description, $image);
        // 3 & 4
        $sql->execute();
        // 2. Tạo câu query
        $productId = parent::$connection->insert_id;
        // Tạo chuỗi kiểu (?, id), (?, id), (?, id)
        $insertPlace = str_repeat("(?, $productId),", count($categoryIds) - 1) . "(?, $productId)";
        // Tạo chuỗi iiiiiiii
        $insertType = str_repeat('i', count($categoryIds));

        $sql = parent::$connection->prepare("INSERT INTO `category_product`(`category_id`, `product_id`) VALUES $insertPlace");

        $sql->bind_param($insertType, ...$categoryIds);
        return $sql->execute();
    }

    public function update($name, $price, $description, $image, $productId, $categoryIds)
    {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("UPDATE `products` SET `name`=?,`price`=?,`description`=?,`image`=? WHERE `id`=?");
        $sql->bind_param('sissi', $name, $price, $description, $image, $productId);
        // 3 & 4
        $sql->execute();
        // Xóa categories cũ
        $sql = parent::$connection->prepare("DELETE FROM `category_product` WHERE `product_id`=?");
        $sql->bind_param('i', $productId);
        // 3 & 4
        $sql->execute();


        // Thêm categories mới
        // 2. Tạo câu query

        // Tạo chuỗi kiểu (?, id), (?, id), (?, id)
        $insertPlace = str_repeat("(?, $productId),", count($categoryIds) - 1) . "(?, $productId)";
        // Tạo chuỗi iiiiiiii
        $insertType = str_repeat('i', count($categoryIds));

        $sql = parent::$connection->prepare("INSERT INTO `category_product`(`category_id`, `product_id`) VALUES $insertPlace");

        $sql->bind_param($insertType, ...$categoryIds);
        return $sql->execute();
    }

    public function delete($productId)
    {
        // 2. Tạo câu query
        // Xóa categories cũ
        $sql = parent::$connection->prepare("DELETE FROM `category_product` WHERE `product_id`=?");
        $sql->bind_param('i', $productId);
        // 3 & 4
        $sql->execute();


        $sql = parent::$connection->prepare("DELETE FROM `products` WHERE `id`=?");
        $sql->bind_param('i', $productId);
        // 3 & 4
        return $sql->execute();
    }
    public function deleteAll($productIds)
    {
        // Tạo chuỗi kiểu ?,?,?
        $insertPlace = str_repeat("?,", count($productIds) - 1) . "?";
        // Tạo chuỗi iiiiiiii
        $insertType = str_repeat('i', count($productIds));


        // 2. Tạo câu query
        // Xóa categories cũ
        $sql = parent::$connection->prepare("DELETE FROM `category_product` WHERE `product_id` IN ($insertPlace)");
        $sql->bind_param($insertType, ...$productIds);
        // 3 & 4
        $sql->execute();


        $sql = parent::$connection->prepare("DELETE FROM `products` WHERE `id` IN ($insertPlace)");
        $sql->bind_param($insertType, ...$productIds);

        // 3 & 4
        return $sql->execute();
    }

    public function bin($productId)
    {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("UPDATE `products` SET `status`=0 WHERE `id`=?");
        $sql->bind_param('i', $productId);
        // 3 & 4
        return $sql->execute();
    }
    public function updateStatus($productId, $status) {
        $sql = parent::$connection->prepare("UPDATE `products` SET status = ? WHERE id = ?");
        $sql->bind_param('ii',$status, $productId);
        // 3 & 4
        return $sql->execute();
    }
    public function restore($productId)
    {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("UPDATE `products` SET `status`=1 WHERE `id`=?");
        $sql->bind_param('i', $productId);
        // 3 & 4
        return $sql->execute();
    }
    public function restoreAll($productIds)
{
    // Tạo chuỗi ID từ mảng
    $ids = implode(',', $productIds);  // Nối các ID thành chuỗi
    $sql = parent::$connection->prepare("UPDATE `products` SET `status`=1 WHERE `id` IN ($ids)");
    
    // Thực thi câu lệnh SQL
    return $sql->execute();
}


    public function like($productId)
    {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare("UPDATE `products` SET `likes`=`likes` + 1 WHERE `id`=?");
        $sql->bind_param('i', $productId);
        // 3 & 4
        return $sql->execute();
    }

    public function findTotal() {
        $sql = parent::$connection->prepare("SELECT COUNT(DISTINCT `products`.`id`) AS total 
                                             FROM `products` 
                                             INNER JOIN `category_product` 
                                             ON `products`.`id` = `category_product`.`product_id`");
        return parent::select($sql)[0]['total'];
    }
    

    // Hàm phân trang
    public function paginate($page, $perPage) {
        $offset = ($page - 1) * $perPage;
        $sql = parent::$connection->prepare("SELECT `products`.*, GROUP_CONCAT(`categories`.`name`) AS category_name
                                             FROM `products`
                                             INNER JOIN `category_product`
                                             ON `products`.`id` = `category_product`.`product_id`
                                             LEFT JOIN `categories`
                                             ON `categories`.`id` = `category_product`.`category_id`
                                             GROUP BY `products`.`id`
                                             LIMIT ?, ?");
        $sql->bind_param('ii', $offset, $perPage);
        return parent::select($sql);
    }
    
}
