<?php
session_start(); // Bắt đầu phiên
include_once 'Config/config.php'; // Kết nối tới cơ sở dữ liệu
include_once 'Controllers/LoginController.php';
include_once 'Layouts/header.php'; // Bao gồm header
// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: Views/login.php");
    exit();
}else if(isset($_SESSION['nhanVien']) && ($_SESSION['quyen'] == '1'|| $_SESSION['quyen'] == '2')){
    header("Location: Views/DanhSachSanPham.php");
    exit();
}else if($_SESSION['quyen'] == '3'){
    echo "Chưa xây dựng phía khách hàng";
    exit();
}
?>
