<?php
session_start(); // Bắt đầu phiên
// include các file cần thiết
include_once '../Config/Config.php';
include_once '../Controllers/SanPhamController.php';

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}
// Kiểm tra nếu nhân viên không phải quản trị viên
if ($_SESSION['quyen']!=1) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: DanhSachSanPham.php");
    exit();
}
// Lấy mã sản phẩm từ URL
$masp = isset($_GET['masp']) ? $_GET['masp'] : null;
// Khởi tạo đối tượng SanPhamController
$sanPhamController = new SanPhamController($connection);
$sanPham = $sanPhamController->laySanPhamTheoMa($masp);

// Xử lý khi form được submit để xóa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gọi phương thức xóa sản phẩm từ SanPhamController
    $sanPhamController->xoaSanPham($masp);
    // Chuyển hướng về trang danh sách sản phẩm sau khi xóa
    header("Location: DanhSachSanPham.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/caeacdbc15.js" crossorigin="anonymous"></script>
    <!-- Script xác nhận xóa sản phẩm -->
    <script>
        function confirmDelete(event) {
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')) {
                event.preventDefault();
            }
        }
    </script>
</head>
<!-- Header -->
<?php include '../Layouts/header.php'; ?>

<body class="bg-gray-100 mt-16 flex">
    <div class="container mx-auto w-1/5">
        <!-- Sidebar -->
        <?php include '../Layouts/sidebar.php'; ?>
    </div>
    <div class="container mx-auto w-4/5 px-7 ">
        <div class="bg-white shadow-md rounded-lg px-6 py-3">
        <p class="text-red-500 font-bold text-2xl">SẢN PHẨM SẼ BỊ XÓA VĨNH VIỄN</p>
            <div class="flex w-full">
                <img src="../Images/<?= htmlspecialchars($sanPham->getAnh()) ?>"
                    alt="<?php echo htmlspecialchars($sanPham->getTenSanPham()); ?>" class="w-5/12 h-auto rounded-lg">
                <div class="pl-6 w-full">
                    <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($sanPham->getTenSanPham()); ?></h2>
                    <table class="table-auto mt-4 w-full border-collapse">
                    <tbody>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2 rounded-l-lg w-1/4">Mã sản phẩm</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getMaSanPham()); ?></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 rounded-l-lg">Giá</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo number_format($sanPham->getGia(), 0, ',', '.'); ?> VND</td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2 rounded-l-lg">Cân nặng</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getCanNang()); ?> Kg</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 rounded-l-lg">Loại sản phẩm</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getLoaiSanPham($connection)); ?></td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2 rounded-l-lg">Đối tượng</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getDoiTuong($connection)); ?></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 rounded-l-lg">Chất liệu</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getChatLieu($connection)); ?></td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2 rounded-l-lg">Hãng sản xuất</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getHangSanXuat($connection)); ?></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 rounded-l-lg">Quốc gia sản xuất</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getNuocSanXuat($connection)); ?></td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2 rounded-l-lg">Thời gian bảo hành</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getThoiGianBaoHanh()); ?> năm</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 rounded-l-lg">Số lượng trong kho</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo htmlspecialchars($sanPham->getSoLuong()); ?></td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2 rounded-l-lg">Mô tả</td>
                            <td class="px-4 py-2 rounded-r-lg"><?php echo nl2br(htmlspecialchars($sanPham->getGioiThieuSanPham())); ?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="flex justify-between w-full">
                <!-- Nút Quay lại ở bên trái -->
                <a href="javascript:history.back()" class="text-gray-500 text-4xl ml-3 hover:text-gray-700"><i
                        class="fa-solid fa-rotate-left"></i></a>
                <!-- Nút Xóa ở bên phải -->
                <form action="XoaSanPham.php?masp=<?= htmlspecialchars($sanPham->getMaSanPham()) ?>" method="post">
                    <button type="submit" title="Xóa sản phẩm" class="text-red-500 text-4xl ml-3 hover:text-red-700" onclick="confirmDelete(event)"><i
                            class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
</body>

</html>