<?php
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

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 15;
$offset = ($page - 1) * $limit;
list($danhMucSanPham, $totalSanPham) = $sanPhamController->timKiemSanPham($tenSanPham, $giaMin, $giaMax, $hangSanXuat, $loai, $nuocSanXuat, $doiTuong, $chatLieu, $limit, $offset);
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
</head>
<body class="bg-gray-100">
<div class="container mx-auto">
    <h1 class="text-4xl font-bold mt-5 text-center">Danh Mục Sản Phẩm</h1>

    <!-- Form tìm kiếm -->
    <form method="GET" class="bg-white p-4 rounded shadow-md mt-5">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="text" name="tenSanPham" placeholder="Tên sản phẩm" value="<?= htmlspecialchars($tenSanPham) ?>" class="p-2 border rounded">
            <input type="number" name="giaMin" placeholder="Giá thấp nhất" value="<?= htmlspecialchars($giaMin) ?>" class="p-2 border rounded">
            <input type="number" name="giaMax" placeholder="Giá cao nhất" value="<?= htmlspecialchars($giaMax) ?>" class="p-2 border rounded">
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
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tìm kiếm</button>
            <a href="TimKiem.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-center">Làm mới</a>
        </div>
    </form>

    <!-- Danh sách sản phẩm -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-5">
        <?php foreach ($danhMucSanPham as $sanPham): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="../Images/<?= htmlspecialchars($sanPham->getAnh()) ?>" class="object-cover" alt="<?= htmlspecialchars($sanPham->getTenSanPham()) ?>">
                <div class="p-4">
                    <h5 class="text-lg font-semibold"><?= htmlspecialchars(ucwords($sanPham->getTenSanPham())) ?></h5>
                    <p class="text-gray-700">Giá: <?= htmlspecialchars($sanPham->getGia()) ?> VNĐ</p>
                    <p class="text-gray-700">Số lượng: <?= htmlspecialchars($sanPham->getSoLuong()) ?></p>
                    <a href="#" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Xem Chi Tiết</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Pagination Component -->
    <div class=" mb-5">
        <?php if ($totalPages > 1): ?>
            <nav class="flex justify-center mt-6">
                <ul class="flex space-x-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                            class="px-4 py-2 border rounded-md 
                            <?= $i == $page ? 'bg-blue-500 text-white border-blue-500 cursor-not-allowed' : 'bg-white text-blue-500 border-gray-300 hover:bg-blue-100 hover:text-blue-600' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

<?php
$connection->close();
?>