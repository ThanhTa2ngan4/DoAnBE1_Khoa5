<?php
require_once 'config/database.php';
spl_autoload_register(function ($className) {
    require_once "app/model/$className.php";
});

$userModel = new User(); 

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Gán role_id mặc định là 2 (user)
    $role_id = 2;

    if ($userModel->register($_POST['username'], $_POST['password'], $role_id)) {
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
    <title>Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
        body {
            background: linear-gradient(135deg, #34c0eb, #ff6a00);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .register-container h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 30px;
            color: #333;
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
            background-color: #ff6a00;
            border-color: #ff6a00;
            border-radius: 10px;
            font-size: 16px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #e55e00;
            border-color: #e55e00;
        }

        .form-text {
            font-size: 14px;
            text-align: center;
        }

        .form-text a {
            color: #ff6a00;
            text-decoration: none;
        }

        .form-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Đăng Ký</h1>
        <form action="register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng Ký</button>
            <p class="form-text mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </form>
    </div>
</body>
</html>