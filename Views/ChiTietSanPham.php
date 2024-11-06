<?php
session_start(); // Bắt đầu phiên
include_once '../Config/config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/SanPhamController.php';

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}
$masp = isset($_GET['masp']) ? $_GET['masp'] : 0;
$sanPhamController = new SanPhamController($connection);
$sanPham = $sanPhamController->laySanPhamTheoMa($masp);
if ($sanPham === null) {
    // Nếu không tìm thấy sản phẩm, hiển thị thông báo lỗi
    echo "Không tìm thấy sản phẩm.";
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
<body class="bg-gray-100 mt-16 flex">
    <div class="container mx-auto w-1/5">
        <?php include '../Layouts/sidebar.php'; ?>
    </div>
    <div class="container mx-auto w-4/5 px-7 ">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex">
            <img src="../Images/<?= htmlspecialchars($sanPham->getAnh()) ?>" alt="<?php echo htmlspecialchars($sanPham->getTenSanPham()); ?>" class="w-5/12 h-auto rounded-lg">
            <div class="pl-6">
            <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($sanPham->getTenSanPham()); ?></h2>
            <div class="mb-4 text-xl">
                <p><strong>Mã sản phẩm:</strong> <?php echo htmlspecialchars($sanPham->getMaSanPham()); ?></p>
                <p><strong>Giá:</strong> <?php echo number_format($sanPham->getGia(), 0, ',', '.'); ?> VND</p>
                <p><strong>Số lượng:</strong> <?php echo htmlspecialchars($sanPham->getSoLuong()); ?></p>
                <p><strong>Mô tả:</strong> <?php echo nl2br(htmlspecialchars($sanPham->getGioiThieuSanPham())); ?></p>
                <p><strong>Loại sản phẩm:</strong> <?php echo htmlspecialchars($sanPham->getLoaiSanPham($connection)); ?></p>
                <p><strong>Đối tượng:</strong> <?php echo htmlspecialchars($sanPham->getDoiTuong($connection)); ?></p>
                <p><strong>Chất liệu:</strong> <?php echo htmlspecialchars($sanPham->getChatLieu($connection)); ?></p>
                <p><strong>Hãng sản xuất:</strong> <?php echo htmlspecialchars($sanPham->getHangSanXuat($connection)); ?></p>
                <p><strong>Quốc gia sản xuất:</strong> <?php echo htmlspecialchars($sanPham->getNuocSanXuat($connection)); ?></p>
                <p><strong>Thời gian bảo hành:</strong> <?php echo htmlspecialchars($sanPham->getThoiGianBaoHanh()); ?> tháng</p>
            </div>
        </div>
    </div>
    <div class="flex justify-end w-full">
        <a href="edit.php?masp=<?php echo $sanPham->getMaSanPham(); ?>" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Chỉnh sửa</a>
        <a href="delete.php?masp=<?php echo $sanPham->getMaSanPham(); ?>" class="bg-red-500 text-white px-4 py-2 rounded">Xóa</a>
    </div>
    </div>
    </div>
</body>
</html>