<?php
require_once 'config/database.php';
spl_autoload_register(function ($className) {
     require_once "app/model/$className.php";
});

$id = '';
$page = 1;
$perPage = 6;

if (isset($_GET['id'])) {
     $id = $_GET['id'];
}

if (isset($_GET['page'])) {
     $page = (int)$_GET['page'];
}

$productModel = new Product();
$products = $productModel->findByCategory2($id, $page, $perPage);

$categoryModel = new Category();
$categories = $categoryModel->all();

$totalProducts = $productModel->findTotalByCategory($id);

$totalPages = ceil($totalProducts / $perPage);

$q = '';
if (isset($_GET['q'])) {
    $q = $_GET['q'];
}

if (isset($_POST['btn-like'])) {
     $productId = $_POST['btn-like'];
     $productsLiked = isset($_COOKIE['productsLiked']) ? json_decode($_COOKIE['productsLiked'], true) : [];
     if (!in_array($productId, $productsLiked)) {
         $productModel->like($productId);
         $productsLiked[] = $productId;
         setcookie('productsLiked', json_encode($productsLiked), time() + 3600 * 24, "/");
     }
 }
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
                              <a href="#" class="position-relative me-4 my-auto">
                                   <i class="fa fa-shopping-bag fa-2x"></i>
                                   <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"></span>
                              </a>
                              <a href="#" class="my-auto">
                                   <i class="fas fa-user fa-2x"></i>
                              </a>
                         </div>
                    </div>
               </nav>
          </div>
     </div>
     <!-- Navbar End -->


     <!-- Modal Search Start -->
     <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-fullscreen">
               <div class="modal-content rounded-0">
                    <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                         <div class="input-group w-75 mx-auto d-flex">
                              <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                              <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                         </div>
                    </div>
               </div>
          </div>
     </div>
     <!-- Modal Search End -->


     <!-- Single Page Header start -->
     <div class="container-fluid page-header py-5">
          <h1 class="text-center text-white display-6">Shop</h1>
          <ol class="breadcrumb justify-content-center mb-0">
               <li class="breadcrumb-item"><a href="index.php">Home</a></li>
               <li class="breadcrumb-item"><a href="#">Pages</a></li>
               <li class="breadcrumb-item active text-white">Shop</li>
          </ol>
     </div>
     <!-- Single Page Header End -->

     <!-- Danh sách sản phẩm -->

     <!-- Fruits Shop Start-->
     <div class="container-fluid fruite py-5">
          <div class="container py-5">
               <h1 class="mb-4">Fresh fruits shop</h1>
               <div class="row g-4">
                    <div class="col-lg-12">
                         <div class="row g-4">
                              <div class="col-xl-3">
                                   <form action="search.php" method="get">
                                        <div class="input-group w-100 mx-auto">
                                             <input
                                                  type="search"
                                                  class="form-control p-3"
                                                  placeholder="Tìm kiếm..."
                                                  name="q"
                                                  value="<?php echo $q ?>"
                                                  style="border: 1.5px solid #4CAF50; border-radius: 5px 0 0 5px; font-size: 1rem;">
                                             <button
                                                  type="submit"
                                                  class="btn"
                                                  style="background-color: #4CAF50; color: white; border-radius: 0 5px 5px 0; padding: 0 15px;">
                                                  <i class="fa fa-search"></i>
                                             </button>
                                        </div>
                                   </form>

                              </div>
                              <div class="col-6"></div>
                         </div>
                         <div class="row g-4">
                              <div class="col-lg-3">
                                   <!-- Cột trái -->
                                   <div class="row g-4">
                                        <!-- Category -->
                                        <div class="col-lg-12">
                                             <div class="mb-3">
                                                  <!-- Category -->
                                                  <ul class="list-unstyled fruite-categorie">
                                                       <h4>Categories</h4>
                                                       <li>
                                                            <a class="nav-link" href="shop.php">ALL</a>
                                                       </li>
                                                       <?php foreach ($categories as $category) : ?>
                                                            <li>
                                                                 <a class="nav-link" href="category.php?id=<?php echo $category['id'] ?>"><?php echo $category['name'] ?></a>
                                                            </li>
                                                       <?php endforeach; ?>

                                                  </ul>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <!-- Cột phải -->
                              <div class="col-lg-9">
                                   <div class="row g-4 justify-content">
                                        <?php
                                        foreach ($products as $product) :
                                        ?>
                                             <div class="col-md-6 col-lg-6 col-xl-4">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <a href="product.php?id=<?php echo $product['id'] ?>">
                                                    <img src="public/images/<?php echo $product['image'] ?>" class="img-fluid w-100 rounded-top" alt="">
                                                </a>
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Fruits</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>
                                                    <a href="product.php?id=<?php echo $product['id'] ?>">
                                                        <?php
                                                        // Cắt tên sản phẩm nếu dài hơn 20 ký tự
                                                        echo (strlen($product['name']) > 20) ? substr($product['name'], 0, 20) . '...' : $product['name'];
                                                        ?>
                                                    </a>
                                                </h4>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="text-dark fs-5 fw-bold mb-0"><?php echo $product['price'] ?>đ</p>
                                                    <div class="d-flex">
                                                        <a href="#" class="btn btn-sm btn-outline-primary d-flex align-items-center me-2">
                                                            <i class="bi bi-bag-fill me-1"></i>
                                                            <span>Add To Cart</span>
                                                        </a>
                                                        <form action="shop.php" method="post">
                                                            <button type="submit" class="btn btn-outline-danger" name="btn-like" value="<?= $product['id'] ?>">
                                                                &hearts; <?= $product['likes'] ?>
                                                            </button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        <?php
                                        endforeach;
                                        ?>

                                        <div class="col-12">
                                             <nav aria-label="Page navigation example" class="mt-4">
                                                  <ul class="pagination d-flex justify-content-center mt-5">
                                                       <?php if ($page > 1) : ?>
                                                            <li class="page-item">
                                                                 <a class="page-link" href="?id=<?php echo $id; ?>&page=<?php echo $page - 1 ?>">Previous</a>
                                                            </li>
                                                       <?php endif; ?>

                                                       <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                                            <li class="page-item <?php echo $i === $page ? 'active' : '' ?>">
                                                                 <a class="page-link" href="?id=<?php echo $id; ?>&page=<?php echo $i ?>"><?= $i ?></a>
                                                            </li>
                                                       <?php endfor; ?>

                                                       <?php if ($page < $totalPages) : ?>
                                                            <li class="page-item">
                                                                 <a class="page-link" href="?id=<?php echo $id; ?>&page=<?php echo $page + 1 ?>">Next</a>
                                                            </li>
                                                       <?php endif; ?>
                                                  </ul>
                                             </nav>
                                        </div>


                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
     <!-- Fruits Shop End-->


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
                         <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                         <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                         <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                         Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
                    </div>
               </div>
          </div>
     </div>
     <!-- Copyright End -->



     <!-- Back to Top -->
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
</body>

</html>