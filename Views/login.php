<?php 
include_once '../Config/config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/LoginController.php';

// Khởi tạo đối tượng NhanVien và LoginController
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
$loginController = new LoginController($connection);

// Kiểm tra nếu form đăng nhập được gửi
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Gọi phương thức đăng nhập từ LoginController
    $message = $loginController->login($username, $password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form method="POST" action="login.php">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" name="username" required><br><br>

        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Đăng nhập</button>
    </form>
    <p><?php echo $message; ?></p>

    <?php if (isset($_SESSION['nhanVien'])): ?>
        <a href="logout.php">Đăng xuất</a>
    <?php endif; ?>
</body>
</html>
