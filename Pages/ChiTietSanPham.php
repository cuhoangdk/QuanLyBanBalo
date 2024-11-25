<?php
session_start(); // Bắt đầu phiên
include_once '../Config/Config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/SanPhamController.php';

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}
$masp = isset($_GET['masp']) ? $_GET['masp'] : null;
$sanPhamController = new SanPhamController($connection);
$sanPham = $sanPhamController->laySanPhamTheoMa($masp);


if (isset($_SESSION['success']))
{
    echo '<div id="notification" class="fixed top-16 right-5 bg-green-500 text-white p-4 rounded shadow-lg">
    '.$_SESSION['success'].'
        <button onclick="document.getElementById(\'notification\').style.display=\'none\'" class="ml-4 bg-red-500 px-2 rounded">X</button>
    </div>';
    echo '<script>setTimeout(function() {document.getElementById("notification").style.display = "none";}, 5000);
    </script>';
    unset($_SESSION['success']);
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
            <div class="flex w-full">
                <img src="../Images/<?= htmlspecialchars($sanPham->getAnh()) ?>"
                    alt="<?php echo htmlspecialchars($sanPham->getTenSanPham()); ?>" class="w-5/12 h-auto object-contain rounded-lg">
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

                <!-- Các nút Chỉnh sửa và Xóa ở bên phải -->
                <?php if (isset($_SESSION['quyen']) && $_SESSION['quyen'] == 1): ?>
                    <div class="flex space-x-2">
                        <a href="ChinhSuaSanPham.php?masp=<?= htmlspecialchars(string: $sanPham->getMaSanPham()) ?>"
                            title="Chỉnh sửa sản phẩm" class="text-blue-500 text-4xl ml-3 hover:text-blue-700"><i
                                class="fa-solid fa-pen-to-square"></i></a>
                        <a href="XoaSanPham.php?masp=<?= htmlspecialchars($sanPham->getMaSanPham()) ?>"
                            title="Xóa sản phẩm" class="text-red-500 text-4xl ml-3 hover:text-red-700"><i
                                class="fa-solid fa-trash"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</body>

</html>