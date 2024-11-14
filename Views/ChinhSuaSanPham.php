<?php
session_start(); // Bắt đầu phiên

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

// include các file cần thiết
include_once '../Config/Config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/SanPhamController.php';
include_once '../Controllers/ChatLieuController.php';
include_once '../Controllers/HangSanXuatController.php';
include_once '../Controllers/QuocGiaController.php';
include_once '../Controllers/LoaiDoiTuongController.php';
include_once '../Controllers/LoaiSanPhamController.php';

// Khởi tạo các controller
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

// Lấy thông tin sản phẩm cần chỉnh sửa
$masp = isset($_GET['masp']) ? $_GET['masp'] : null;
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
    $anh = $sanPham->getAnh();

    // Xử lý upload ảnh
    if (isset($_FILES['anh']) && $_FILES['anh']['error'] == 0) {
        try {
            $anh = $sanPhamController->xuLyUploadAnh($_FILES['anh']);
        } catch (Exception $e) {
            $error['anh'] = $e->getMessage();
        }
    }
    // Kiểm tra thời gian bảo hành
    if (!is_numeric($thoiGianBaoHanh) || $thoiGianBaoHanh < 0) {
        $error['thoiGianBaoHanh'] = 'Thời gian bảo hành phải là số và không được nhỏ hơn 0.';
    }

    // Kiểm tra số lượng
    if (!is_numeric($soLuong) || $soLuong < 1) {
        $error['soLuong'] = 'Số lượng phải là số và lớn hơn 0.';
    }

    // Kiểm tra cân nặng
    if (!is_numeric($canNang) || $canNang < 0.1) {
        $error['canNang'] = 'Cân nặng phải là số và lớn hơn hoặc bằng 0.1.';
    }

    // Kiểm tra đơn giá
    if (!is_numeric($gia) || $gia < 1) {
        $error['gia'] = 'Đơn giá phải là số và lớn hơn hoặc bằng 1.';
    }

    if (empty($error)) {
        // Cập nhật sản phẩm
        $sanPhamController->chinhSuaSanPham($masp, $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieuSanPham, $loaiSanPham, $doiTuong, $anh, $gia, $soLuong);
        // Lưu thông báo
        $_SESSION['success'] = 'Cập nhật sản phẩm thành công';
        // Chuyển hướng về trang chi tiết sản phẩm sau khi cập nhật
        header("Location: ChiTietSanPham.php?masp=$masp");
        exit();
    }
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
            <!-- Form chỉnh sửa sản phẩm -->
            <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-2">
                    <label class="block text-gray-700 font-bold">Mã sản phẩm:</label>
                    <input type="text" name="tenSanPham" value="<?= htmlspecialchars($sanPham->getMaSanPham()) ?>" disabled class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 font-bold">Tên sản phẩm:</label>
                    <input type="text" name="tenSanPham" value="<?= htmlspecialchars($sanPham->getTenSanPham()) ?>" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-2 w-full">
                    <label class="block text-gray-700 font-bold">Đơn giá:</label>
                    <input type="float" min="1" name="gia" value="<?= htmlspecialchars($sanPham->getGia()) ?>" class="w-full px-3 py-2 border rounded-lg">
                    <!-- Hiển thị thông báo lỗi nếu có -->
                    <?php if (!empty($error['gia'])): ?>
                        <div class="mb-4 text-red-500">
                            <?= htmlspecialchars($error['gia']) ?>
                        </div>
                    <?php endif; ?>  
                </div>

                <div class="flex w-full gap-5">
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Cân nặng (kg):</label>
                        <input type="float" min="0.1" name="canNang" value="<?= htmlspecialchars($sanPham->getCanNang()) ?>"
                            class="w-full px-3 py-2 border rounded-lg">
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (!empty($error['canNang'])): ?>
                            <div class="mb-4 text-red-500">
                                <?= htmlspecialchars($error['canNang']) ?>
                            </div>
                        <?php endif; ?>                   
                    </div>
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Loại sản phẩm:</label>
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
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Đối tượng khách hàng:</label>
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
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Chất liệu sản phẩm:</label>
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
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Hãng sản xuất:</label>
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
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Quốc gia sản xuất:</label>
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
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Thời gian bảo hành (năm):</label>
                        <input type="float" min="0" name="thoiGianBaoHanh" value="<?= htmlspecialchars($sanPham->getThoiGianBaoHanh()) ?>" class="w-full px-3 py-2 border rounded-lg">
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (!empty($error['thoiGianBaoHanh'])): ?>
                            <div class="mb-4 text-red-500">
                                <?= htmlspecialchars($error['thoiGianBaoHanh']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Số lượng trong kho:</label>
                        <input type="number" min="1" name="soLuong" value="<?= htmlspecialchars($sanPham->getSoLuong()) ?>"class="w-full px-3 py-2 border rounded-lg">
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (!empty($error['soLuong'])): ?>
                            <div class="mb-4 text-red-500">
                                <?= htmlspecialchars($error['soLuong']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Mô tả sản phẩm:</label>
                        <textarea name="gioiThieuSanPham"class="w-full h-52 px-3 py-2 border rounded-lg"><?= htmlspecialchars($sanPham->getGioiThieuSanPham()) ?></textarea>
                    </div>
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Ảnh sản phẩm:</label>
                        <img src="../Images/<?= htmlspecialchars($sanPham->getAnh()) ?>" alt="Ảnh sản phẩm"
                            class="h-36 mb-4 rounded border">
                        <div class="flex">
                            <input type="file" value="<?= htmlspecialchars($sanPham->getAnh()) ?>" name="anh" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (!empty($error['anh'])): ?>
                            <div class="mb-4 text-red-500">
                                <?= htmlspecialchars($error['anh']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Nút lưu thay đổi và quay lại -->
                <div class="flex justify-between">
                    <a href="javascript:history.back()" class="text-gray-500 text-4xl ml-3 hover:text-gray-700">
                        <i class="fa-solid fa-rotate-left"></i></a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Lưu thayđổi</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
