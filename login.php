<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($className) {
    require_once "app/model/$className.php";
});

$userModel = new User(); 

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $userModel->login($_POST['username'], $_POST['password']);
    
    if ($user) {
        // Lưu thông tin vào session
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];

        // Kiểm tra role_id để chuyển hướng
        if ($user['role_id'] == 1) {
            header('Location: admin/index.php');
        } else if ($user['role_id'] == 2) {
            header('Location: index.php');
        }
        exit();
    } else {
        $_SESSION['notification'] = 'Sai username hoặc password';
        header('Location: login.php');  
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
        body {
            background: linear-gradient(135deg, #1d2b64, #f8a8b9);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4c4c6c;
        }

        .alert {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #5c6bc0;
            border-color: #5c6bc0;
            border-radius: 10px;
            font-size: 16px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #4e5ba4;
            border-color: #4e5ba4;
        }

        .form-text {
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php
            // Hiển thị thông báo lỗi nếu có
            if (!empty($_SESSION['notification'])) :
        ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['notification']; $_SESSION['notification'] = ''; ?>
        </div>
        <?php
        endif;
        ?>

        <h1>Đăng nhập</h1>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p class="form-text mt-3">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
        </form>
    </div>
</body>
</html>
1