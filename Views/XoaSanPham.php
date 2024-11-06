<?php
session_start(); // Bắt đầu phiên

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/caeacdbc15.js" crossorigin="anonymous"></script>
</head>
<?php include '../Layouts/header.php'; ?>
<body>
<?php include '../Layouts/sidebar.php'; ?>
</body>
</html>