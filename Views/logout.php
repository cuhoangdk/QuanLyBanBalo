<?php 
include_once '../Config/config.php'; 
include_once '../Controllers/LoginController.php';

// Khởi tạo LoginController
$LoginController = new LoginController(null);

// Thực hiện đăng xuất
$logoutMessage = $LoginController->logout();

// Chuyển hướng về trang đăng nhập với thông báo
header("Location: login.php");
exit();
?>
