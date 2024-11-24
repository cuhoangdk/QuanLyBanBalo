<?php
session_start(); // Bắt đầu phiên

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

include_once '../Config/Config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/ChatLieuController.php';
$chatLieuController = new ChatLieuController($connection);

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if  (isset($_SESSION['quyen']) && $_SESSION['quyen'] != 1) {
        // Nếu không phải quản trị viên thì không thực hiện thêm, sửa, xóa chất liệu
        header("Location: ../index.php");
        exit();
    }
    // Xử lý khi form được submit để thêm chất liệu mới
    if (isset($_POST['themChatLieu']))
    {
        $tenChatLieu = $_POST['tenChatLieu'];
        // Thêm chất liệu
        $chatLieuController->themChatLieu($tenChatLieu);
    }
    // Xử lý khi form được submit để chỉnh sửa chất liệu
    if (isset($_POST['chinhSuaChatLieu'])) {
        $maChatLieu = $_POST['maChatLieu'];
        $tenChatLieu = $_POST['tenChatLieu'];
        // Cập nhật chất liệu
        $chatLieuController->suaChatLieu($maChatLieu, $tenChatLieu);
    }
    // Xử lý khi form được submit để xóa chất liệu
    if (isset($_POST['xoaChatLieu'])) {
        $maChatLieu = $_POST['maChatLieu'];
        // Gọi phương thức xóa chất liệu từ ChatLieuController
        $chatLieuController->xoaChatLieu($maChatLieu);
    }
}

// Lấy danh sách chất liệu
$dsChatLieu = $chatLieuController->layDanhSachChatLieu();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Chất Liệu</title>
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
            <h1 class="text-2xl font-bold">Danh Mục Chất Liệu</h1>
            <?php if (isset($_SESSION['quyen']) && $_SESSION['quyen'] == 1): ?>
                <a onclick="showAddModal()" class="bg-blue-500 text-white font-bold px-4 py-2 rounded cursor-pointer">+</a>
            <?php endif; ?>        
        </div>
        <div class="overflow-x-auto mt-2">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-3 pl-6 py-3 border-b text-left text-sm font-semibold text-gray-700">STT</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Mã chất liệu</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Tên chất liệu</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dsChatLieu as $index => $chatLieu): ?>
                        <?php $stt = $index + 1; ?>
                        <tr class="<?= $stt % 2 == 0 ? 'bg-gray-100' : 'bg-white' ?> border-b hover:bg-gray-200">
                            <!-- Số thứ tự -->
                            <td class="px-3 pl-6 py-2 text-gray-700"><?= $stt ?></td>

                            <!-- Tên sản phẩm -->
                            <td class="px-3 py-2 text-gray-700"><?= htmlspecialchars($chatLieu->getMaChatLieu()) ?></td>

                            <!-- Giá -->
                            <td class="px-3 py-2 text-gray-700"><?= htmlspecialchars(ucwords($chatLieu->getChatLieu())) ?></td>

                            <!-- Hành động -->
                            <td class="px-3 py-2">
                                <!-- Nêu là quản trị viên thì hiển thị nút chỉnh sửa và xóa -->
                                <?php if (isset($_SESSION['quyen']) && $_SESSION['quyen'] == 1): ?>
                                    <!-- Nút chỉnh sửa -->
                                    <button 
                                        onclick="showEditModal('<?= $chatLieu->getMaChatLieu() ?>', '<?= htmlspecialchars($chatLieu->getChatLieu()) ?>')" 
                                        title="Chỉnh sửa chất liệu" 
                                        class="text-blue-500 text-3xl ml-3 hover:text-blue-700">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <!-- Nút xóa -->
                                    <button 
                                        onclick="showDeleteModal('<?= $chatLieu->getMaChatLieu() ?>')" 
                                        title="Xóa chất liệu" 
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
                    <h2 class="text-white font-semibold text-lg">Thêm Chất Liệu</h2>
                    <button onclick="hideModal()" class="bg-red-400 px-2 rounded text-white text-xl">&times;</button>
                </div>
                <!-- Body -->
                <div class="p-6">
                    <!-- Form thêm chất liệu -->
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Tên chất liệu:</label>
                            <input type="text" name="tenChatLieu" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                 placeholder="Nhập tên chất liệu" required>
                        </div>
                        <button name="themChatLieu" type="submit" 
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-400">
                            Thêm Chất Liệu
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
                    <p class="text-red-700 text-xl font-bold ">Bạn có chắc chắn muốn xóa chất liệu này không?</p>
                </div>
                <!-- Footer -->
                <div class="p-6 flex justify-end">
                    <button onclick="hideModal()" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg mr-4 hover:bg-gray-400">
                        Hủy
                    </button>
                    <form id="deleteForm" method="POST">
                        <input type="hidden" name="maChatLieu" id="deleteMaChatLieu">
                        <button name="xoaChatLieu" type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal chỉnh sửa chất liệu -->
        <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-yellow-500 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-white font-semibold text-lg">Chỉnh sửa Chất Liệu</h2>
                    <button onclick="hideModal()" class="bg-red-400 px-2 rounded text-white text-xl">&times;</button>
                </div>
                <!-- Body -->
                <div class="p-6">
                    <!-- Form chỉnh sửa chất liệu -->
                    <form action="" method="POST">
                        <input type="hidden" name="maChatLieu" id="editMaChatLieu">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Tên chất liệu:</label>
                            <input type="text" name="tenChatLieu" id="editTenChatLieu" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none" placeholder="Nhập tên chất liệu" required>
                        </div>
                        <button name="chinhSuaChatLieu" type="submit" class="w-full bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-400">
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
    function showDeleteModal(maChatLieu) {
        document.getElementById('deleteModal').style.display = 'flex';
        // Gán mã chất liệu vào input hidden
        document.getElementById('deleteMaChatLieu').value = maChatLieu;
    }

    // Hiển thị modal chỉnh sửa
    function showEditModal(maChatLieu, tenChatLieu) {
        document.getElementById('editModal').style.display = 'flex';
        // Gán dữ liệu vào các input trong form
        document.getElementById('editMaChatLieu').value = maChatLieu;
        document.getElementById('editTenChatLieu').value = tenChatLieu;
    }
</script>
