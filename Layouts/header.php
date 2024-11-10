<?php
include_once __DIR__ . '/../Config/config.php';
include_once __DIR__ . '/../Controllers/LoginController.php';
$loginController = new LoginController($connection);
if ($loginController->isSessionExpired()) {
    header("Location: login.php"); // Chuyển hướng về trang đăng nhập
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
    header("Location: ../Views/login.php");
    exit();
}
?>
<header class="bg-gray-300 text-gray-800 p-2 px-7 fixed right-0 top-0 w-4/5 z-10">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">Xin chào, <?= htmlspecialchars($hoVaTenNhanVien) ?>!</h1>
        <a href="?action=logout" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-4 rounded-full">Đăng xuất</a>
    </div>
</header>