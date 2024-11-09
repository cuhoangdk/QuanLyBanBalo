<?php
session_start(); // Bắt đầu phiên

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

include_once '../Config/config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/SanPhamController.php';
include_once '../Controllers/ChatLieuController.php';
include_once '../Controllers/HangSanXuatController.php';
include_once '../Controllers/QuocGiaController.php';
include_once '../Controllers/LoaiDoiTuongController.php';
include_once '../Controllers/LoaiSanPhamController.php';

$sanPhamController = new SanPhamController($connection);
$chatLieuController = new ChatLieuController($connection);
$hangSanXuatController = new HangSanXuatController($connection);
$quocGiaController = new QuocGiaController($connection);
$loaiDoiTuongController = new LoaiDoiTuongController($connection);
$loaiSanPhamController = new LoaiSanPhamController($connection);

// Lấy dữ liệu tìm kiếm từ URL và kiểm tra giá trị null nếu là chuỗi rỗng
$fields = ['tenSanPham', 'giaMin', 'giaMax', 'hangSanXuat', 'loai', 'nuocSanXuat', 'doiTuong', 'chatLieu'];
foreach ($fields as $field) {
    $$field = isset($_GET[$field]) && $_GET[$field] !== '' ? $_GET[$field] : null;
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : 15;
$offset = ($page - 1) * $limit;

[$danhMucSanPham, $totalSanPham] = $sanPhamController->timKiemSanPham($tenSanPham, $giaMin, $giaMax, $hangSanXuat, $loai, $nuocSanXuat, $doiTuong, $chatLieu, $limit, $offset);

$totalPages = ceil($totalSanPham / $limit);

// Lấy danh sách cho các combo box
$danhSachChatLieu = $chatLieuController->layDanhSachChatLieu();
$danhSachHangSanXuat = $hangSanXuatController->layDanhSachHangSanXuat();
$danhSachQuocGia = $quocGiaController->layDanhSachQuocGia();
$danhSachLoaiDoiTuong = $loaiDoiTuongController->layDanhSachLoaiDoiTuong();
$danhSachLoaiSanPham = $loaiSanPhamController->layDanhSachLoaiSanPham();

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

<body class="bg-gray-100 mt-12 flex">
    <div class="container mx-auto w-1/5">
        <?php include '../Layouts/sidebar.php'; ?>
    </div>
    <div class="container mx-auto w-4/5 px-7 ">
        <!-- Form tìm kiếm -->
        <form method="GET" class="bg-white p-4 rounded shadow-md mt-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="tenSanPham" placeholder="Tên sản phẩm"
                    value="<?= htmlspecialchars($tenSanPham) ?>" class="p-2 border rounded">
                <input type="number" name="giaMin" placeholder="Giá thấp nhất" value="<?= htmlspecialchars($giaMin) ?>"
                    class="p-2 border rounded">
                <input type="number" name="giaMax" placeholder="Giá cao nhất" value="<?= htmlspecialchars($giaMax) ?>"
                    class="p-2 border rounded">
                <select name="chatLieu" class="p-2 border rounded">
                    <option value="">Chọn chất liệu</option>
                    <?php foreach ($danhSachChatLieu as $chatLieuObj): ?>
                        <?php
                        $maChatLieu = htmlspecialchars($chatLieuObj->getMaChatLieu());
                        $tenChatLieu = htmlspecialchars($chatLieuObj->getChatLieu());
                        $selected = $maChatLieu == $chatLieu ? 'selected' : '';
                        ?>
                        <option value="<?= $maChatLieu ?>" <?= $selected ?>><?= $tenChatLieu ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
                <select name="hangSanXuat" class="p-2 border rounded">
                    <option value="">Chọn hãng sản xuất</option>
                    <?php foreach ($danhSachHangSanXuat as $hang): ?>
                        <?php
                        $maHangSanXuat = htmlspecialchars($hang->getMaHangSanXuat());
                        $tenHangSanXuat = htmlspecialchars($hang->getHangSanXuat());
                        $selected = $maHangSanXuat == $hangSanXuat ? 'selected' : '';
                        ?>
                        <option value="<?= $maHangSanXuat ?>" <?= $selected ?>><?= $tenHangSanXuat ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="loai" class="p-2 border rounded">
                    <option value="">Chọn loại sản phẩm</option>
                    <?php foreach ($danhSachLoaiSanPham as $loaiSanPham): ?>
                        <?php
                        $maLoaiSanPham = htmlspecialchars($loaiSanPham->getMaLoaiSanPham());
                        $tenLoaiSanPham = htmlspecialchars($loaiSanPham->getLoaiSanPham());
                        $selected = $maLoaiSanPham == $loai ? 'selected' : '';
                        ?>
                        <option value="<?= $maLoaiSanPham ?>" <?= $selected ?>><?= $tenLoaiSanPham ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="nuocSanXuat" class="p-2 border rounded">
                    <option value="">Chọn nước sản xuất</option>
                    <?php foreach ($danhSachQuocGia as $quocGia): ?>
                        <?php
                        $maQuocGia = htmlspecialchars($quocGia->getMaQuocGia());
                        $tenQuocGia = htmlspecialchars($quocGia->getQuocGia());
                        $selected = $maQuocGia == $nuocSanXuat ? 'selected' : '';
                        ?>
                        <option value="<?= $maQuocGia ?>" <?= $selected ?>><?= $tenQuocGia ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="doiTuong" class="p-2 border rounded">
                    <option value="">Chọn đối tượng</option>
                    <?php foreach ($danhSachLoaiDoiTuong as $loaiDoiTuong): ?>
                        <?php
                        $maDoiTuong = htmlspecialchars($loaiDoiTuong->getMaDoiTuong());
                        $tenDoiTuong = htmlspecialchars($loaiDoiTuong->getDoiTuong());
                        $selected = $maDoiTuong == $doiTuong ? 'selected' : '';
                        ?>
                        <option value="<?= $maDoiTuong ?>" <?= $selected ?>><?= $tenDoiTuong ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                <a href="DanhSachSanPham.php" title="Làm mới"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-center">Làm mới</a>
                <button type="submit" title="Tìm kiếm"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tìm kiếm</button>
                <a href="ThemSanPham.php" title="Thêm sản phẩm"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-center">Thêm mới</a>
            </div>

        </form>

        <!-- Danh sách sản phẩm -->
        <div class="overflow-x-auto mt-2">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-3 pl-6 py-3 border-b text-left text-sm font-semibold text-gray-700">STT</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Tên sản phẩm</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Giá (VNĐ)</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Số lượng</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Hình ảnh</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($danhMucSanPham as $index => $sanPham): ?>
                        <?php $stt = ($page - 1) * $limit + $index + 1; ?>
                        <tr class="<?= $stt % 2 == 0 ? 'bg-gray-100' : 'bg-white' ?> border-b hover:bg-gray-200">
                            <!-- Số thứ tự -->
                            <td class="px-3 pl-6 py-2 text-gray-700"><?= $stt ?></td>

                            <!-- Tên sản phẩm -->
                            <td class="px-3 py-2 text-gray-700"><?= htmlspecialchars(ucwords($sanPham->getTenSanPham())) ?>
                            </td>

                            <!-- Giá -->
                            <td class="px-3 py-2 text-gray-700"><?= number_format($sanPham->getGia(), 0) ?></td>

                            <!-- Số lượng -->
                            <td class="px-3 py-2 text-gray-700"><?= htmlspecialchars($sanPham->getSoLuong()) ?></td>

                            <!-- Hình ảnh -->
                            <td class="px-3 py-2">
                                <img src="../Images/<?= htmlspecialchars($sanPham->getAnh()) ?>"
                                    alt="<?= htmlspecialchars($sanPham->getTenSanPham()) ?>"
                                    class="w-16 h-16 object-cover rounded">
                            </td>

                            <!-- Hành động -->
                            <td class="px-3 py-2">
                                <a href="ChiTietSanPham.php?masp=<?= htmlspecialchars($sanPham->getMaSanPham()) ?>"
                                    title="Chi tiết sản phẩm" class="text-green-500 text-3xl hover:text-green-700"><i
                                        class="fa-solid fa-circle-info"></i></a>
                                <a href="ChinhSuaSanPham.php?masp=<?= htmlspecialchars($sanPham->getMaSanPham()) ?>"
                                    title="Chỉnh sửa sản phẩm" class="text-blue-500 text-3xl ml-3 hover:text-blue-700"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <a href="XoaSanPham.php?masp=<?= htmlspecialchars($sanPham->getMaSanPham()) ?>"
                                    title="Xóa sản phẩm" class="text-red-500 text-3xl ml-3 hover:text-red-700"><i
                                        class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- Pagination Component -->
        <div class="mb-5">
            <?php if ($totalPages > 1): ?>
                <nav class="flex justify-center mt-6">
                    <ul class="flex space-x-2">
                        <!-- Trang đầu -->
                        <li>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>"
                                class="px-4 py-2 border rounded-md <?= $page == 1 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-white text-green-500 border-green-300 hover:bg-green-100 hover:text-green-600' ?>">
                                << </a>
                        </li>

                        <!-- Về trước -->
                        <li>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])) ?>"
                                class="px-4 py-2 border rounded-md <?= $page == 1 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-white text-green-500 border-green-300 hover:bg-green-100 hover:text-green-600' ?>">
                                < </a>
                        </li>

                        <!-- Số trang -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                                    class="px-4 py-2 border rounded-md <?= $i == $page ? 'bg-green-500 text-white border-green-500 cursor-not-allowed' : 'bg-white text-green-500 border-green-300 hover:bg-green-100 hover:text-green-600' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Về sau -->
                        <li>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)])) ?>"
                                class="px-4 py-2 border rounded-md <?= $page == $totalPages ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-white text-green-500 border-green-300 hover:bg-green-100 hover:text-green-600' ?>">
                                >
                            </a>
                        </li>

                        <!-- Trang cuối -->
                        <li>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $totalPages])) ?>"
                                class="px-4 py-2 border rounded-md <?= $page == $totalPages ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-white text-green-500 border-green-300 hover:bg-green-100 hover:text-green-600' ?>">
                                >>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>

<?php
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

if ($connection) {
    $connection->close();
}
?>