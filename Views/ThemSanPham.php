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
        $anh = null;
    }

    // Thêm sản phẩm mới
    $sanPhamController->themSanPham($tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieuSanPham, $loaiSanPham, $doiTuong, $anh, $gia, $soLuong);

    // Chuyển hướng về trang danh sách sản phẩm sau khi thêm mới
    header("Location: DanhSachSanPham.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
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
            <h2 class="text-2xl font-bold mb-4">Thêm Sản Phẩm</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-2">
                    <label class="block text-gray-700 font-bold">Tên sản phẩm:</label>
                    <input type="text" name="tenSanPham" class="w-full px-3 py-2 border rounded-lg" placeholder="Tên sản phẩm" required>
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Đơn giá:</label>
                        <input type="text" name="gia" class="w-full px-3 py-2 border rounded-lg" placeholder="Đơn giá" required>
                    </div> 
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Số lượng:</label>
                        <input type="text" name="soLuong" class="w-full px-3 py-2 border rounded-lg" placeholder="Số lượng" required>
                    </div>
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Cân nặng (kg):</label>
                        <input type="text" name="canNang" class="w-full px-3 py-2 border rounded-lg" placeholder="Cân nặng" required>
                    </div>
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Loại sản phẩm:</label>
                        <select name="loaiSanPham" class="w-full p-2 border rounded" required>
                            <?php foreach ($danhSachLoaiSanPham as $loai): ?>
                                <?php 
                                    $maLoaiSanPham = htmlspecialchars($loai->getMaLoaiSanPham());
                                    $tenLoaiSanPham = htmlspecialchars($loai->getLoaiSanPham());
                                ?>
                                <option value="<?= $maLoaiSanPham ?>"><?= $tenLoaiSanPham ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Đối tượng:</label>
                        <select name="doiTuong" class="w-full p-2 border rounded">
                            <?php foreach ($danhSachLoaiDoiTuong as $doiTuong): ?>
                                <?php 
                                    $maDoiTuong = htmlspecialchars($doiTuong->getMaDoiTuong());
                                    $tenDoiTuong = htmlspecialchars($doiTuong->getDoiTuong());
                                ?>
                                <option value="<?= $maDoiTuong ?>"><?= $tenDoiTuong ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Chất liệu:</label>
                        <select name="chatLieu" class="w-full p-2 border rounded">
                            <?php foreach ($danhSachChatLieu as $chatLieu): ?>
                                <?php 
                                    $maChatLieu = htmlspecialchars($chatLieu->getMaChatLieu());
                                    $tenChatLieu = htmlspecialchars($chatLieu->getChatLieu());
                                ?>
                                <option value="<?= $maChatLieu ?>"><?= $tenChatLieu ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Hãng sản xuất:</label>
                        <select name="hangSanXuat" class="w-full p-2 border rounded">
                            <?php foreach ($danhSachHangSanXuat as $hang): ?>
                                <?php 
                                    $maHangSanXuat = htmlspecialchars($hang->getMaHangSanXuat());
                                    $tenHangSanXuat = htmlspecialchars($hang->getHangSanXuat());
                                ?>
                                <option value="<?= $maHangSanXuat ?>"><?= $tenHangSanXuat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Quốc gia sản xuất:</label>
                        <select name="nuocSanXuat" class="w-full p-2 border rounded">
                            <?php foreach ($danhSachQuocGia as $quocGia): ?>
                                <?php 
                                    $maQuocGia = htmlspecialchars($quocGia->getMaQuocGia());
                                    $tenQuocGia = htmlspecialchars($quocGia->getQuocGia());
                                ?>
                                <option value="<?= $maQuocGia ?>"><?= $tenQuocGia ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Thời gian bảo hành (năm):</label>
                        <input type="number" name="thoiGianBaoHanh" class="w-full px-3 py-2 border rounded-lg" placeholder="Số năm bảo hành" required>
                    </div>
                    <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Ảnh sản phẩm:</label>
                        <input type="file" name="anh" class="w-full px-3 py-2 border rounded-lg" required>
                    </div>
                </div>
                <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Mô tả:</label>
                        <textarea name="gioiThieuSanPham" class="w-full h-44 px-3 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-between">
                    <a href="javascript:history.back()" class="text-gray-500 text-4xl ml-3 hover:text-gray-700"><i class="fa-solid fa-rotate-left"></i></a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>