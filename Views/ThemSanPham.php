<?php
session_start(); // Bắt đầu phiên

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}
?>
<?php if (isset($_SESSION['nhanVien'])): ?>
    <div class="text-center mt-3">
        <a href="logout.php" class="btn btn-secondary">Đăng xuất</a>
    </div>
<?php endif; ?>
chừng nào viết trang thêm sửa xoá