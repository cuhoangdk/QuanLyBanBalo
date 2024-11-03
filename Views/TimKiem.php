<?php
include_once '../Config/config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/SanPhamController.php';
$sanPhamController = new SanPhamController($connection);

// Lấy dữ liệu tìm kiếm từ URL và kiểm tra giá trị null nếu là chuỗi rỗng
$fields = ['tenSanPham', 'giaMin', 'giaMax', 'hangSanXuat', 'loai', 'nuocSanXuat', 'doiTuong'];
foreach ($fields as $field) {
    $$field = isset($_GET[$field]) && $_GET[$field] !== '' ? $_GET[$field] : null;
}
// Lấy danh sách sản phẩm dựa trên tiêu chí tìm kiếm
$danhMucSanPham = $sanPhamController->timKiemSanPham($tenSanPham, $giaMin, $giaMax, $hangSanXuat, $loai, $nuocSanXuat, $doiTuong);
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="tenSanPham" placeholder="Tên sản phẩm" value="<?= htmlspecialchars($tenSanPham) ?>" class="p-2 border rounded">
            <input type="number" name="giaMin" placeholder="Giá thấp nhất" value="<?= htmlspecialchars($giaMin) ?>" class="p-2 border rounded">
            <input type="number" name="giaMax" placeholder="Giá cao nhất" value="<?= htmlspecialchars($giaMax) ?>" class="p-2 border rounded">
            <input type="text" name="hangSanXuat" placeholder="Hãng sản xuất" value="<?= htmlspecialchars($hangSanXuat) ?>" class="p-2 border rounded">
            <input type="text" name="loai" placeholder="Loại" value="<?= htmlspecialchars($loai) ?>" class="p-2 border rounded">
            <input type="text" name="nuocSanXuat" placeholder="Nước sản xuất" value="<?= htmlspecialchars($nuocSanXuat) ?>" class="p-2 border rounded">
            <input type="text" name="doiTuong" placeholder="Đối tượng" value="<?= htmlspecialchars($doiTuong) ?>" class="p-2 border rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tìm kiếm</button>
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
</div>
</body>
</html>

<?php
$connection->close();
?>
