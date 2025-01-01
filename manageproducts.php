<?php
session_start();
if(!isset($_SESSION['user']['username'])) {
    header('Location: index.php');
}

require_once './config/database.php';
spl_autoload_register(function ($className)
{
   require_once "./app/models/$className.php";
});

$productModel = new Product();


$productList = $productModel->all();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="http://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1>Quản lí</h1>
        <a href="addproduct.php">
            <button type="submit" class="btn btn-danger">Add</button>
        </a>
        <table class="table">
            <tr>
                <td>Id</td>
                <td>Product name</td>
                <td>Product price</td>
                <td>Product photo</td>
                <td>Action</td>
            </tr>
            <?php
            foreach ($productList as $item) {
            ?>
            <tr>
                <td><?php echo $item['id'] ?></td>
                <td><?php echo $item['product_name'] ?></td>
                <td><?php echo $item['product_price'] ?></td>
                <td>
                    <img src="public/images/<?php echo $item['product_photo'] ?>" alt="" class="img-fluid" style="width: 100px">
                </td>
                <td>
                    <a href="editproduct.php?id=<?php echo $item['id'] ?>" class="btn btn-primary">Edit</a>
                    <form action="manageproducts.php" method="post" onsubmit="return confirm('Xóa hông?')">
                        <input type="hidden" name="deleteId" value="<?php echo $item['id'] ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>
</html>