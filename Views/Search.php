<?php
include_once '../Config/config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/SanPhamController.php';

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sanPhamController = new SanPhamController($connection);
$danhMucSanPham = $sanPhamController->layDanhMucSanPham();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Sản Phẩm</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Danh Mục Sản Phẩm</h1>
    <div class="row">
        <?php foreach ($danhMucSanPham as $sanPham): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="../Images/<?= htmlspecialchars($sanPham->getAnh()) ?>" class="card-img-top" alt="<?= htmlspecialchars($sanPham->getTenSanPham()) ?>">
                    <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars(ucwords($sanPham->getTenSanPham())) ?></h5>
                    <p class="card-text">Giá: <?= htmlspecialchars($sanPham->getGia()) ?> VNĐ</p>
                    <p class="card-text">Số lượng: <?= htmlspecialchars($sanPham->getSoLuong()) ?></p>
                    <a href="#" class="btn btn-primary">Xem Chi Tiết</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$connection->close();
?>
