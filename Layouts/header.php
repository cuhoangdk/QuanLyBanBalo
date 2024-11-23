<?php
// include các file cần thiết
include_once __DIR__ . '/../Config/Config.php';
include_once __DIR__ . '/../Controllers/LoginController.php';

// Khởi tạo LoginController
$loginController = new LoginController($connection);
// Kiểm tra xem session có hợp lệ không
if ($loginController->isSessionExpired()) {
    header("Location: ../Pages/login.php"); // Chuyển hướng về trang đăng nhập
    exit();
}

// Lấy tên nhân viên từ session
$hoNhanVien = isset($_SESSION['nhanVien']['ho_nhan_vien']) ? $_SESSION['nhanVien']['ho_nhan_vien'] : '';
$tenNhanVien = isset($_SESSION['nhanVien']['ten_nhan_vien']) ? $_SESSION['nhanVien']['ten_nhan_vien'] : '';
$chucVu = isset($_SESSION['nhanVien']['chuc_vu']) ? $_SESSION['nhanVien']['chuc_vu'] : '';
$hoVaTenNhanVien = $hoNhanVien." ".$tenNhanVien." (".$chucVu.")";

// Thực hiện đăng xuất nếu có yêu cầu
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $loginController->logout();
    header("Location: ../Pages/login.php");
    exit();
}

// Hiển thị thông báo nếu có 
if (isset($_SESSION['success']))
{
    echo '<div id="notificationSuccess" class="fixed top-16 right-5 bg-green-500 text-white p-4 rounded shadow-lg">
    '.$_SESSION['success'].'
        <button onclick="document.getElementById(\'notificationSuccess\').style.display=\'none\'" class="ml-4 bg-red-500 px-2 rounded">X</button>
    </div>';
    echo '<script>setTimeout(function() {document.getElementById("notificationSuccess").style.display = "none";}, 5000);
    </script>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error']))
{
    echo '<div id="notificationError" class="fixed top-16 right-5 bg-green-500 text-white p-4 rounded shadow-lg">
    '.$_SESSION['error'].'
        <button onclick="document.getElementById(\'notificationError\').style.display=\'none\'" class="ml-4 bg-red-500 px-2 rounded">X</button>
    </div>';
    echo '<script>setTimeout(function() {document.getElementById("notificationError").style.display = "none";}, 5000);
    </script>';
    unset($_SESSION['error']);
}
?>
<!-- Header -->
<header class="bg-gray-300 text-gray-800 p-2 px-7 fixed right-0 top-0 w-4/5">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">Xin chào, <?= htmlspecialchars($hoVaTenNhanVien) ?>!</h1>
        <a href="?action=logout" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-4 rounded-full">Đăng xuất</a>
    </div>
</header>

