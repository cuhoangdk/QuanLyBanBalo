<?php
include_once 'Config/config.php'; // Kết nối tới cơ sở dữ liệu
include_once 'Controllers/LoginController.php';
include_once 'Layouts/header.php'; // Bao gồm header

session_start(); // Bắt đầu phiên

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: Views/login.php");
    exit();
}else{
    header("Location: Views/DanhSachSanPham.php");
    exit();
}
?>
