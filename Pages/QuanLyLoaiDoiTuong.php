<?php
session_start(); // Bắt đầu phiên

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

include_once '../Config/Config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/LoaiDoiTuongController.php';
$loaiDoiTuongController = new LoaiDoiTuongController($connection);

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý khi form được submit để thêm loại đối tượng mới
    if (isset($_POST['themLoaiDoiTuong']))
    {
        $tenLoaiDoiTuong = $_POST['tenLoaiDoiTuong'];
        // Thêm loại đối tượng
        $loaiDoiTuongController->themLoaiDoiTuong($tenLoaiDoiTuong);

    }
    // Xử lý khi form được submit để chỉnh sửa loại đối tượng
    if (isset($_POST['chinhSuaLoaiDoiTuong'])) {
        $maLoaiDoiTuong = $_POST['maLoaiDoiTuong'];
        $tenLoaiDoiTuong = $_POST['tenLoaiDoiTuong'];
        // Cập nhật loại đối tượng
        $loaiDoiTuongController->suaLoaiDoiTuong($maLoaiDoiTuong, $tenLoaiDoiTuong);
        
    }
    // Xử lý khi form được submit để xóa loại đối tượng
    if (isset($_POST['xoaLoaiDoiTuong'])) {
        $maLoaiDoiTuong = $_POST['maLoaiDoiTuong'];
        // Gọi phương thức xóa loại đối tượng từ LoaiDoiTuongController
        $loaiDoiTuongController->xoaLoaiDoiTuong($maLoaiDoiTuong);
    }
}

// Lấy danh sách loại đối tượng
$dsLoaiDoiTuong = $loaiDoiTuongController->layDanhSachLoaiDoiTuong();
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
<!-- Header -->
<?php include '../Layouts/header.php'; ?>

<body class="bg-gray-100 mt-12 flex">
    <div class="container mx-auto w-1/5">
        <?php include '../Layouts/sidebar.php'; ?>
    </div>
    <div class="container mx-auto w-4/5 px-7 ">
        <div class="flex justify-between items-center mt-2">
            <h1 class="text-2xl font-bold">Danh Mục Loại Đối Tượng</h1>
            <a onclick="showAddModal()" class="bg-blue-500 text-white font-bold px-4 py-2 rounded cursor-pointer">+</a>
        </div>
        <div class="overflow-x-auto mt-2">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-3 pl-6 py-3 border-b text-left text-sm font-semibold text-gray-700">STT</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Mã loại đối tượng</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Tên loại đối tượng</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dsLoaiDoiTuong as $index => $loaiDoiTuong): ?>
                        <?php $stt = $index + 1; ?>
                        <tr class="<?= $stt % 2 == 0 ? 'bg-gray-100' : 'bg-white' ?> border-b hover:bg-gray-200">
                            <!-- Số thứ tự -->
                            <td class="px-3 pl-6 py-2 text-gray-700"><?= $stt ?></td>

                            <!-- Tên sản phẩm -->
                            <td class="px-3 py-2 text-gray-700"><?= htmlspecialchars($loaiDoiTuong->getMaDoiTuong()) ?></td>

                            <!-- Giá -->
                            <td class="px-3 py-2 text-gray-700"><?= htmlspecialchars(ucwords($loaiDoiTuong->getDoiTuong())) ?></td>

                            <!-- Hành động -->
                            <td class="px-3 py-2">
                                <!-- Nêu là quản trị viên thì hiển thị nút chỉnh sửa và xóa -->
                                <?php if (isset($_SESSION['quyen']) && $_SESSION['quyen'] == 1): ?>
                                    <!-- Nút chỉnh sửa -->
                                    <button 
                                        onclick="showEditModal('<?= $loaiDoiTuong->getMaDoiTuong() ?>', '<?= htmlspecialchars($loaiDoiTuong->getDoiTuong()) ?>')" 
                                        title="Chỉnh sửa loại đối tượng" 
                                        class="text-blue-500 text-3xl ml-3 hover:text-blue-700">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <!-- Nút xóa -->
                                    <button 
                                        onclick="showDeleteModal('<?= $loaiDoiTuong->getMaDoiTuong() ?>')" 
                                        title="Xóa loại đối tượng" 
                                        class="text-red-500 text-3xl ml-3 hover:text-red-700">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal thêm sản phẩm -->
        <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg">
                <!-- Header -->
                <div class="bg-blue-500 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-white font-semibold text-lg">Thêm loại đối tượng</h2>
                    <button onclick="hideModal()" class="bg-red-400 px-2 rounded text-white text-xl">&times;</button>
                </div>
                <!-- Body -->
                <div class="p-6">
                    <!-- Form thêm loại đối tượng -->
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Tên loại đối tượng:</label>
                            <input type="text" name="tenLoaiDoiTuong" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                value="<?= isset($tenLoaiDoiTuong) ? $tenLoaiDoiTuong : '' ?>" placeholder="Nhập tên loại đối tượng" required>
                        </div>
                        <button name="themLoaiDoiTuong" type="submit" 
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-400">
                            Thêm loại đối tượng
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal xác nhận xóa -->
        <div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg">
                <!-- Header -->
                <div class="bg-red-500 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-white font-semibold text-lg">Xác nhận xóa</h2>
                    <button onclick="hideModal()" class="bg-red-400 px-2 rounded text-white text-xl">&times;</button>
                </div>
                <!-- Body -->
                <div class="p-6">
                    <p class="text-red-700 text-xl font-bold ">Bạn có chắc chắn muốn xóa loại đối tượng này không?</p>
                </div>
                <!-- Footer -->
                <div class="p-6 flex justify-end">
                    <button onclick="hideModal()" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg mr-4 hover:bg-gray-400">
                        Hủy
                    </button>
                    <form id="deleteForm" method="POST">
                        <input type="hidden" name="maLoaiDoiTuong" id="deleteMaLoaiDoiTuong">
                        <button name="xoaLoaiDoiTuong" type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal chỉnh sửa loại đối tượng -->
        <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-yellow-500 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-white font-semibold text-lg">Chỉnh sửa loại đối tượng</h2>
                    <button onclick="hideModal()" class="bg-red-400 px-2 rounded text-white text-xl">&times;</button>
                </div>
                <!-- Body -->
                <div class="p-6">
                    <!-- Form chỉnh sửa loại đối tượng -->
                    <form action="" method="POST">
                        <input type="hidden" name="maLoaiDoiTuong" id="editMaLoaiDoiTuong">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Tên loại đối tượng:</label>
                            <input type="text" name="tenLoaiDoiTuong" id="editTenLoaiDoiTuong" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none" placeholder="Nhập tên loại đối tượng" required>
                        </div>
                        <button name="chinhSuaLoaiDoiTuong" type="submit" class="w-full bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-400">
                            Lưu Chỉnh Sửa
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>
</html>

<script>
    // Ẩn modal
    function hideModal() {
        document.getElementById('addModal').style.display = 'none';
        document.getElementById('deleteModal').style.display = 'none';
        document.getElementById('editModal').style.display = 'none';
    }
    // Hiển thị modal thêm mới
    function showAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }

    // Hiển thị modal xác nhận
    function showDeleteModal(maLoaiDoiTuong) {
        document.getElementById('deleteModal').style.display = 'flex';
        // Gán mã loại đối tượng vào input hidden
        document.getElementById('deleteMaLoaiDoiTuong').value = maLoaiDoiTuong;
    }

    // Hiển thị modal chỉnh sửa
    function showEditModal(maLoaiDoiTuong, tenLoaiDoiTuong) {
        document.getElementById('editModal').style.display = 'flex';
        // Gán dữ liệu vào các input trong form
        document.getElementById('editMaLoaiDoiTuong').value = maLoaiDoiTuong;
        document.getElementById('editTenLoaiDoiTuong').value = tenLoaiDoiTuong;
    }
</script>
