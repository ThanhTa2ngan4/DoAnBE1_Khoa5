<?php
session_start();
require_once "config/database.php";

spl_autoload_register(function ($className) {
    require_once "app/model/$className.php";
});


$id = $_GET['id'];  // Lấy ID của sản phẩm từ URL
$productModel = new Product();
$product = $productModel->find($id);  // Lấy sản phẩm theo ID
$products = $productModel->all();

$categoryModel = new Category();
$category = $categoryModel->all();

$commentModel = new Comment();
if (!empty($_POST['commentContent'])) {
    $commentModel->add($_POST['commentContent'], $id, $_SESSION['user_id']);
    var_dump($_POST['commentContent']);
}

$comments = $commentModel->find($id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Fruitables - Vegetable Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">123 Street, New York</a></small>
                    <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">Email@Example.com</a></small>
                </div>
                <div class="top-link pe-2">
                    <a href="#" class="text-white"><small class="text-white mx-2">Privacy Policy</small>/</a>
                    <a href="#" class="text-white"><small class="text-white mx-2">Terms of Use</small>/</a>
                    <a href="#" class="text-white"><small class="text-white ms-2">Sales and Refunds</small></a>
                </div>
            </div>
        </div>
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
                        <a href="index.php" class="nav-item nav-link">Home</a>
                        <a href="shop.php" class="nav-item nav-link active">Shop</a>
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
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop Detail</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Shop Detail</li>
        </ol>
    </div>
    <!-- Single Product Start -->
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-12 col-xl-12">
                    <form action="cart.php" method="post">
                        <div class="row g-4">
                            <!-- Sản phẩm -->
                            <div class="col-lg-6">
                                <div class="border rounded">
                                    <a href="#">
                                        <img src="public/images/<?php echo $product['image']; ?>" class="img-fluid rounded" alt="Image" width="100%">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h3 class="fw-bold mb-3">Name: <?php echo $product['name']; ?></h3>
                                <h4 class="fw-bold mb-3">Giá: <?php echo number_format($product['price']); ?>đ</h4>

                                <div class="d-flex mb-4">
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="fw-bold mb-3">Description: <?php echo $product['description']; ?></div>
                                <!-- Input để điều chỉnh số lượng -->
                                <div class="input-group quantity mb-5" style="width: 150px;">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-minus rounded-circle bg-light border" onclick="updateQuantity(-1)">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="number" name="quantity" id="quantity" class="form-control form-control-sm text-center border-0" value="1" min="1">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-plus rounded-circle bg-light border" onclick="updateQuantity(1)">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Thêm thông tin ẩn của sản phẩm (image, description) -->
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                                <input type="hidden" name="product_description" value="<?php echo $product['description']; ?>">
                                <button type="submit" class="btn btn-outline-success" name="btn-add-to-cart">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <?php
                    if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) :
                    ?>
                        <form action="product.php?id=<?php echo $product['id'] ?>" method="post" class="mb-4">
                            <div class="mb-3">
                                <div class="position-relative">
                                    <input type="text" name="commentContent"
                                        class="form-control rounded-pill border-2 border-primary px-4 py-3 shadow-sm"
                                        placeholder="Write your comment here...">
                                    <i class="fas fa-comment-dots text-primary position-absolute top-50 end-0 translate-middle me-3"></i>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary text-white rounded-pill px-5 py-3 shadow">
                                <i class="fas fa-paper-plane me-2"></i> Post Comment
                            </button>
                        </form>
                    <?php
                    endif;
                    ?>

                    <?php if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) : ?>
                        <div class="alert alert-warning text-center mb-4" role="alert">
                            Bạn cần <a href="login.php" class="text-decoration-underline text-primary">đăng nhập</a> để có thể bình luận.
                        </div>
                    <?php endif; ?>

                    <ul class="list-group mb-4">
                        <?php if (!empty($comments)) : ?>
                            <?php foreach ($comments as $comment) : ?>
                                <li class="list-group-item bg-light rounded shadow-sm mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-primary"><?php echo $comment['user_name']; ?></span>
                                        <small class="text-muted"><?php echo date("d M Y, h:i A", strtotime($comment['created_at'])); ?></small>
                                    </div>
                                    <p class="mb-0 text-dark"><?php echo $comment['content']; ?></p>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="list-group-item bg-light rounded shadow-sm">
                                <p class="mb-0 text-dark text-center">Chưa có bình luận nào.</p>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <!-- Single Product End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <a href="#">
                            <h1 class="text-primary mb-0">Fruitables</h1>
                            <p class="text-secondary mb-0">Fresh products</p>
                        </a>
                    </div>
                    <div class="col-lg-6">

                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-end pt-3">
                            <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">Why People Like us!</h4>
                        <p class="mb-4">typesetting, remaining essentially unchanged. It was
                            popularised in the 1960s with the like Aldus PageMaker including of Lorem Ipsum.</p>
                        <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Read More</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">Shop Info</h4>
                        <a class="btn-link" href="">About Us</a>
                        <a class="btn-link" href="">Contact Us</a>
                        <a class="btn-link" href="">Privacy Policy</a>
                        <a class="btn-link" href="">Terms & Condition</a>
                        <a class="btn-link" href="">Return Policy</a>
                        <a class="btn-link" href="">FAQs & Help</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">Account</h4>
                        <a class="btn-link" href="">My Account</a>
                        <a class="btn-link" href="">Shop details</a>
                        <a class="btn-link" href="">Shopping Cart</a>
                        <a class="btn-link" href="">Wishlist</a>
                        <a class="btn-link" href="">Order History</a>
                        <a class="btn-link" href="">International Orders</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">Contact</h4>
                        <p>Address: 1429 Netus Rd, NY 48247</p>
                        <p>Email: Example@gmail.com</p>
                        <p>Phone: +0123 4567 8910</p>
                        <p>Payment Accepted</p>
                        <img src="img/payment.png" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->
    <!-- Copyright Start -->
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>, All right reserved.</span>
                </div>
                <div class="col-md-6 my-auto text-center text-md-end text-white">
                    Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
                </div>
            </div>
        </div>
    </div>
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            currentValue += change;
            if (currentValue < 1) currentValue = 1;
            quantityInput.value = currentValue;
        }
    </script>
</body>

</html>