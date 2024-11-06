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

// Lấy danh sách cho các combo box
$danhSachChatLieu = $chatLieuController->layDanhSachChatLieu();
$danhSachHangSanXuat = $hangSanXuatController->layDanhSachHangSanXuat();
$danhSachQuocGia = $quocGiaController->layDanhSachQuocGia();
$danhSachLoaiDoiTuong = $loaiDoiTuongController->layDanhSachLoaiDoiTuong();
$danhSachLoaiSanPham = $loaiSanPhamController->layDanhSachLoaiSanPham();

$masp = isset($_GET['masp']) ? $_GET['masp'] : 0;
$sanPham = $sanPhamController->laySanPhamTheoMa($masp);

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenSanPham = $_POST['tenSanPham'];
    $gia = $_POST['gia'];
    $soLuong = $_POST['soLuong'];
    $canNang = $_POST['canNang'];
    $gioiThieuSanPham = $_POST['gioiThieuSanPham'];
    $loaiSanPham = $_POST['loaiSanPham'];
    $doiTuong = $_POST['doiTuong'];
    $chatLieu = $_POST['chatLieu'];
    $hangSanXuat = $_POST['hangSanXuat'];
    $nuocSanXuat = $_POST['nuocSanXuat'];
    $thoiGianBaoHanh = $_POST['thoiGianBaoHanh'];

   // Xử lý upload ảnh
    if (isset($_FILES['anh']) && $_FILES['anh']['error'] == 0) {
        $targetDir = "../Images/";
        $fileName = basename($_FILES["anh"]["name"]);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Kiểm tra loại file (chỉ chấp nhận jpg, jpeg, png và gif)
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Kiểm tra MIME type để đảm bảo đó là file ảnh
            $fileMimeType = mime_content_type($_FILES["anh"]["tmp_name"]);
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

            if (in_array($fileMimeType, $allowedMimeTypes)) {
                // Di chuyển file tới thư mục lưu trữ
                move_uploaded_file($_FILES["anh"]["tmp_name"], $targetFile);
                $anh = $fileName;
            } else {
                echo "Chỉ chấp nhận các file ảnh có định dạng JPG, JPEG, PNG, và GIF.";
                exit();
            }
        } else {
            echo "Chỉ chấp nhận các file ảnh có định dạng JPG, JPEG, PNG, và GIF.";
            exit();
        }
    } else {
        $anh = $sanPham->getAnh();
    }
    // Cập nhật sản phẩm
    $sanPhamController->chinhSuaSanPham($masp, $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieuSanPham, $loaiSanPham, $doiTuong, $anh, $gia, $soLuong);

    // Chuyển hướng về trang chi tiết sản phẩm sau khi cập nhật
    header("Location: ChiTietSanPham.php?masp=$masp");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/caeacdbc15.js" crossorigin="anonymous"></script>
</head>
<?php include '../Layouts/header.php'; ?>
<body class="bg-gray-100 mt-16 flex">
    <div class="container mx-auto w-1/5">
        <?php include '../Layouts/sidebar.php'; ?>
    </div>
    <div class="container mx-auto w-4/5 px-7">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Chỉnh Sửa Sản Phẩm</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">Tên sản phẩm:</label>
                    <input type="text" name="tenSanPham" value="<?= htmlspecialchars($sanPham->getTenSanPham()) ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Đơn giá:</label>
                    <input type="text" name="gia" value="<?= htmlspecialchars($sanPham->getGia()) ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Ảnh sản phẩm:</label>
                    <div class="flex">
                        <img src="../Images/<?= htmlspecialchars($sanPham->getAnh()) ?>" alt="Ảnh sản phẩm" class="w-1/4 mb-2">
                        <input type="file" name="anh" class="w-full px-3 py-2 rounded-lg">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Cân nặng (kg):</label>
                    <input type="text" name="canNang" value="<?= htmlspecialchars($sanPham->getCanNang()) ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Mô tả sản phẩm:</label>
                    <textarea name="gioiThieuSanPham" class="w-full h-52 px-3 py-2 border rounded-lg"><?= htmlspecialchars($sanPham->getGioiThieuSanPham()) ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Loại sản phẩm:</label>
                    <select name="loaiSanPham" class="w-full p-2 border rounded">
                        <?php foreach ($danhSachLoaiSanPham as $loai): ?>
                            <?php 
                                $maLoaiSanPham = htmlspecialchars($loai->getMaLoaiSanPham());
                                $tenLoaiSanPham = htmlspecialchars($loai->getLoaiSanPham());
                                $selected = $tenLoaiSanPham == $sanPham->getLoaiSanPham($connection) ? 'selected' : ''; 
                            ?>
                            <option value="<?= $maLoaiSanPham ?>" <?= $selected ?>><?= $tenLoaiSanPham ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Đối tượng khách hàng:</label>
                    <select name="doiTuong" class="w-full p-2 border rounded">
                        <?php foreach ($danhSachLoaiDoiTuong as $doiTuong): ?>
                            <?php 
                                $maDoiTuong = htmlspecialchars($doiTuong->getMaDoiTuong());
                                $tenDoiTuong = htmlspecialchars($doiTuong->getDoiTuong());
                                $selected = $tenDoiTuong == $sanPham->getDoiTuong($connection) ? 'selected' : ''; 
                            ?>
                            <option value="<?= $maDoiTuong ?>" <?= $selected ?>><?= $tenDoiTuong ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Chất liệu sản phẩm:</label>
                    <select name="chatLieu" class="w-full p-2 border rounded">
                        <?php foreach ($danhSachChatLieu as $chatLieu): ?>
                            <?php 
                                $maChatLieu = htmlspecialchars($chatLieu->getMaChatLieu());
                                $tenChatLieu = htmlspecialchars($chatLieu->getChatLieu());
                                $selected = $tenChatLieu == $sanPham->getChatLieu($connection) ? 'selected' : ''; 
                            ?>
                            <option value="<?= $maChatLieu ?>" <?= $selected ?>><?= $tenChatLieu ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Hãng sản xuất:</label>
                    <select name="hangSanXuat" class="w-full p-2 border rounded">
                        <?php foreach ($danhSachHangSanXuat as $hang): ?>
                            <?php 
                                $maHangSanXuat = htmlspecialchars($hang->getMaHangSanXuat());
                                $tenHangSanXuat = htmlspecialchars($hang->getHangSanXuat());
                                $selected = $tenHangSanXuat == $sanPham->getHangSanXuat($connection) ? 'selected' : ''; 
                            ?>
                            <option value="<?= $maHangSanXuat ?>" <?= $selected ?>><?= $tenHangSanXuat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Quốc gia sản xuất:</label>
                    <select name="nuocSanXuat" class="w-full p-2 border rounded">
                        <?php foreach ($danhSachQuocGia as $quocGia): ?>
                            <?php 
                                $maQuocGia = htmlspecialchars($quocGia->getMaQuocGia());
                                $tenQuocGia = htmlspecialchars($quocGia->getQuocGia());
                                $selected = $tenQuocGia == $sanPham->getNuocSanXuat($connection) ? 'selected' : ''; 
                            ?>
                            <option value="<?= $maQuocGia ?>" <?= $selected ?>><?= $tenQuocGia ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Thời gian bảo hành (năm):</label>
                    <input type="text" name="thoiGianBaoHanh" value="<?= htmlspecialchars($sanPham->getThoiGianBaoHanh()) ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Số lượng trong kho:</label>
                    <input type="text" name="soLuong" value="<?= htmlspecialchars($sanPham->getSoLuong()) ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="flex justify-between">
                    <a href="javascript:history.back()" class="text-gray-500 text-4xl ml-3 hover:text-gray-700"><i class="fa-solid fa-rotate-left"></i></a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>