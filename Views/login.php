<?php
include_once '../Config/config.php';
include_once '../Controllers/LoginController.php';

// Khởi tạo đối tượng LoginController
$loginController = new LoginController($connection);

// Khởi tạo biến để hiển thị thông báo nếu cần
$message = '';

// Kiểm tra nếu form đăng nhập được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gọi phương thức đăng nhập từ LoginController
    if ($loginController->login($username, $password)) {
        // Đăng nhập thành công, chuyển hướng đến trang sản phẩm
        header("Location: ../Views/DanhSachSanPham.php");
        exit();
    } else {
        // Đăng nhập thất bại, thiết lập thông báo lỗi
        $message = "Tên đăng nhập hoặc mật khẩu không đúng";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto flex justify-center items-center h-screen">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Đăng nhập</h2>
            <form method="POST" action="login.php" class="space-y-4">
                <hr class="my-6">
                <h4 class="font-bold text-left mt-3 mb-2">Username</h4>
                <input type="text" name="username" placeholder="Email address or username"
                    class="block border border-black rounded w-full pl-4 py-2.5" required>
                <h4 class="font-bold text-left mt-3 mb-2">Password</h4>
                <input type="password" name="password" placeholder="Password"
                    class="block border border-black rounded w-full pl-4 py-2.5" required>
                <div class="flex items-center mb-2">
                    <input type="checkbox" class="mx-2 w-4 h-4">
                    <label class="">Remember me</label>
                </div>
                <button type="submit" class="w-full py-2.5 bg-green-500 text-white rounded-full font-bold">LOG IN</button>
            </form>

            <?php if (!empty($message)): ?>
                <div class="alert alert-info mt-3 text-center text-red-500"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>
