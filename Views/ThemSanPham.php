<?php
session_start(); // Bắt đầu phiên

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}
// import file cấu hình db & các file model
include_once '../Config/Config.php'; 
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
    try {
        $anh = $sanPhamController->xuLyUploadAnh($_FILES['anh']);
    } catch (Exception $e) {
        $error['anh'] = $e->getMessage();
        $anh = null;
    }
    // Kiểm tra tên sản phẩm đã tồn tại chưa
    if($sanPhamController->kiemTraTenSanPhamTonTai($tenSanPham)) {
        $error['ten'] = "Tên sản phẩm đã tồn tại";
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
    // Nếu không có lỗi thì thêm sản phẩm mới
    if (empty($error)) {
        // Thêm sản phẩm mới
        $sanPhamController->themSanPham($tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieuSanPham, $loaiSanPham, $doiTuong, $anh, $gia, $soLuong);
        // Lưu thông báo
        $_SESSION['success'] = 'Thêm sản phẩm thành công';
        // Chuyển hướng về trang danh sách sản phẩm sau khi thêm mới
        header("Location: DanhSachSanPham.php");
        exit();
    }
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
<!-- Layouts -->
<?php include '../Layouts/header.php'; ?>

<body class="bg-gray-100 mt-16 flex">
    <div class="container mx-auto w-1/5">
        <!-- Sidebar -->
        <?php include '../Layouts/sidebar.php'; ?>
    </div>
    <div class="container mx-auto w-4/5 px-7">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Thêm Sản Phẩm</h2>
            <!-- Form thêm sản phẩm -->
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-2">
                    <label class="block text-gray-700 font-bold">Tên sản phẩm:</label>
                    <input type="text" name="tenSanPham" class="w-full px-3 py-2 border rounded-lg" value="<?= isset($tenSanPham) ? $tenSanPham : '' ?>"
                        placeholder="Tên sản phẩm" required>
                    <!-- Hiển thị thông báo lỗi nếu có -->
                    <?php if (!empty($error['ten'])): ?>
                        <div class="mb-4 text-red-500">
                            <?= htmlspecialchars($error['ten']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Đơn giá:</label>
                        <input type="number" min="1" name="gia" class="w-full px-3 py-2 border rounded-lg" value="<?= isset($gia) ? $gia : '' ?>" placeholder="Đơn giá"
                            required>
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (!empty($error['gia'])): ?>
                            <div class="mb-4 text-red-500">
                                <?= htmlspecialchars($error['gia']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Số lượng:</label>
                        <input type="number" min="1" name="soLuong" class="w-full px-3 py-2 border rounded-lg" value="<?= isset($soLuong) ? $soLuong : '' ?>"
                            placeholder="Số lượng" required>
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
                        <label class="block text-gray-700 font-bold">Cân nặng (kg):</label>
                        <input type="float" min="0.1" name="canNang" class="w-full px-3 py-2 border rounded-lg" value="<?= isset($canNang) ? $canNang : '' ?>"
                            placeholder="Cân nặng" required>
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (!empty($error['canNang'])): ?>
                            <div class="mb-4 text-red-500">
                                <?= htmlspecialchars($error['canNang']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-2 w-1/2">
                        <label class="block text-gray-700 font-bold">Loại sản phẩm:</label>
                        <select name="loaiSanPham" class="w-full p-2 border rounded" required>
                            <?php foreach ($danhSachLoaiSanPham as $loai): ?>
                                <?php
                                $maLoaiSanPham = htmlspecialchars($loai->getMaLoaiSanPham());
                                $tenLoaiSanPham = htmlspecialchars($loai->getLoaiSanPham());
                                $selected = isset($loaiSanPham) && $loaiSanPham == $maLoaiSanPham ? 'selected' : '';
                                ?>
                                <option value="<?= $maLoaiSanPham ?>" <?= $selected ?>><?= $tenLoaiSanPham ?></option>
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
                                $selected = isset($doiTuong) && $doiTuong == $maDoiTuong ? 'selected' : '';
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
                                $selected = isset($chatLieu) && $chatLieu == $maChatLieu ? 'selected' : '';
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
                                $selected = isset($hangSanXuat) && $hangSanXuat == $maHangSanXuat ? 'selected' : '';
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
                                $selected = isset($nuocSanXuat) && $nuocSanXuat == $maQuocGia ? 'selected' : '';
                                ?>
                                <option value="<?= $maQuocGia ?>"><?= $tenQuocGia ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex w-full gap-5">
                    <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Thời gian bảo hành (năm):</label>
                        <input type="float" min="0" name="thoiGianBaoHanh" class="w-full px-3 py-2 border rounded-lg" value="<?= isset($thoiGianBaoHanh) ? $thoiGianBaoHanh : '' ?>"
                            placeholder="Số năm bảo hành" required>
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (!empty($error['thoiGianBaoHanh'])): ?>
                            <div class="mb-4 text-red-500">
                                <?= htmlspecialchars($error['thoiGianBaoHanh']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-2 w-full">
                        <label class="block text-gray-700 font-bold">Ảnh sản phẩm:</label>
                        <input type="file" name="anh" class="w-full px-3 py-2 border rounded-lg" required>
                        <?php if (!empty($error['anh'])): ?>
                            <div class="mb-4 text-red-500">
                                <?= htmlspecialchars($error['anh']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mb-2 w-full">
                    <label class="block text-gray-700 font-bold">Mô tả:</label>
                    <textarea name="gioiThieuSanPham"  class="w-full h-44 px-3 py-2 border rounded-lg"><?= isset($gioiThieuSanPham) ? $gioiThieuSanPham : '' ?></textarea>
                </div>
                <div class="flex justify-between">
                    <a href="javascript:history.back()" class="text-gray-500 text-4xl ml-3 hover:text-gray-700"><i
                            class="fa-solid fa-rotate-left"></i></a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Thêm sản
                        phẩm</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>