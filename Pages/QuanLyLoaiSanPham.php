<?php
session_start(); // Bắt đầu phiên

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['nhanVien'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

include_once '../Config/Config.php'; // Kết nối tới cơ sở dữ liệu
include_once '../Controllers/LoaiSanPhamController.php';
$loaiSanPhamController = new LoaiSanPhamController($connection);
// Lấy trang hiện tại và số lượng sản phẩm trên mỗi trang
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1; // Trang hiện tại
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : 15; // Số lượng sản phẩm trên mỗi trang
$offset = ($page - 1) * $limit; // Vị trí bắt đầu lấy sản phẩm

// Tìm kiếm sản phẩm trong cơ sở dữ liệu trả về mảng chứa danh sách sản phẩm và tổng số sản phẩm
[$danhSachLoaiSanPham, $totalLoaiSanPham] = $loaiSanPhamController->phanTrangLoaiSanPham( $limit, $offset);

// Tính tổng số trang dựa trên tổng số sản phẩm và số lượng sản phẩm trên mỗi trang
$totalPages = ceil($totalLoaiSanPham / $limit);
// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý khi form được submit để thêm loại sản phẩm mới
    if (isset($_POST['themLoaiSanPham']))
    {
        $tenLoaiSanPham = $_POST['tenLoaiSanPham'];
        // Thêm loại sản phẩm
        $loaiSanPhamController->themLoaiSanPham($tenLoaiSanPham);
        [$danhSachLoaiSanPham, $totalLoaiSanPham] = $loaiSanPhamController->phanTrangLoaiSanPham( $limit, $offset);
    }
    // Xử lý khi form được submit để chỉnh sửa loại sản phẩm
    if (isset($_POST['chinhSuaLoaiSanPham'])) {
        $maLoaiSanPham = $_POST['maLoaiSanPham'];
        $tenLoaiSanPham = $_POST['tenLoaiSanPham'];
        // Cập nhật loại sản phẩm
        $loaiSanPhamController->suaLoaiSanPham($maLoaiSanPham, $tenLoaiSanPham);
        [$danhSachLoaiSanPham, $totalLoaiSanPham] = $loaiSanPhamController->phanTrangLoaiSanPham( $limit, $offset);
    }
    // Xử lý khi form được submit để xóa loại sản phẩm
    if (isset($_POST['xoaLoaiSanPham'])) {
        $maLoaiSanPham = $_POST['maLoaiSanPham'];
        // Gọi phương thức xóa loại sản phẩm từ LoaiSanPhamController
        $loaiSanPhamController->xoaLoaiSanPham($maLoaiSanPham);
        [$danhSachLoaiSanPham, $totalLoaiSanPham] = $loaiSanPhamController->phanTrangLoaiSanPham( $limit, $offset);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Loại Sản Phẩm</title>
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
            <h1 class="text-2xl font-bold">Danh Mục Loại Sản Phẩm</h1>
            <?php if (isset($_SESSION['quyen']) && $_SESSION['quyen'] == 1): ?>
                <a onclick="showAddModal()" class="bg-blue-500 text-white font-bold px-4 py-2 rounded cursor-pointer">+</a>
            <?php endif; ?> 
        </div>
        <div class="overflow-x-auto mt-2">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-3 pl-6 py-3 border-b text-left text-sm font-semibold text-gray-700">STT</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Mã loại sản phẩm</th>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Tên loại sản phẩm</th>
                        <?php if (isset($_SESSION['quyen']) && $_SESSION['quyen'] == 1): ?>
                        <th class="px-3 py-3 border-b text-left text-sm font-semibold text-gray-700">Hành động</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($danhSachLoaiSanPham as $index => $loaiSanPham): ?>
                        <?php $stt = ($page - 1) * $limit + $index + 1; ?>
                        <tr class="<?= $stt % 2 == 0 ? 'bg-gray-100' : 'bg-white' ?> border-b hover:bg-gray-200">
                            <!-- Số thứ tự -->
                            <td class="px-3 pl-6 py-2 text-gray-700"><?= $stt ?></td>

                            <!-- Tên sản phẩm -->
                            <td class="px-3 py-2 text-gray-700"><?= htmlspecialchars($loaiSanPham->getMaLoaiSanPham()) ?></td>

                            <!-- Giá -->
                            <td class="px-3 py-2 text-gray-700"><?= htmlspecialchars(ucwords($loaiSanPham->getLoaiSanPham())) ?></td>
                            <?php if (isset($_SESSION['quyen']) && $_SESSION['quyen'] == 1): ?>

                            <!-- Hành động -->
                            <td class="px-3 py-2">
                                <!-- Nêu là quản trị viên thì hiển thị nút chỉnh sửa và xóa -->
                                <!-- Nút chỉnh sửa -->
                                <button 
                                    onclick="showEditModal('<?= $loaiSanPham->getMaLoaiSanPham() ?>', '<?= htmlspecialchars($loaiSanPham->getLoaiSanPham()) ?>')" 
                                    title="Chỉnh sửa loại sản phẩm" 
                                    class="text-blue-500 text-3xl ml-3 hover:text-blue-700">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <!-- Nút xóa -->
                                <button 
                                    onclick="showDeleteModal('<?= $loaiSanPham->getMaLoaiSanPham() ?>')" 
                                    title="Xóa loại sản phẩm" 
                                    class="text-red-500 text-3xl ml-3 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Phân trang -->
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

        <!-- Modal thêm sản phẩm -->
        <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg">
                <!-- Header -->
                <div class="bg-blue-500 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-white font-semibold text-lg">Thêm loại sản phẩm</h2>
                    <button onclick="hideModal()" class="bg-red-400 px-2 rounded text-white text-xl">&times;</button>
                </div>
                <!-- Body -->
                <div class="p-6">
                    <!-- Form thêm loại sản phẩm -->
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Tên loại sản phẩm:</label>
                            <input type="text" name="tenLoaiSanPham" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                 placeholder="Nhập tên loại sản phẩm" required>
                        </div>
                        <button name="themLoaiSanPham" type="submit" 
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-400">
                            Thêm loại sản phẩm
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
                    <p class="text-red-700 text-xl font-bold ">Bạn có chắc chắn muốn xóa loại sản phẩm này không?</p>
                </div>
                <!-- Footer -->
                <div class="p-6 flex justify-end">
                    <button onclick="hideModal()" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg mr-4 hover:bg-gray-400">
                        Hủy
                    </button>
                    <form id="deleteForm" method="POST">
                        <input type="hidden" name="maLoaiSanPham" id="deleteMaLoaiSanPham">
                        <button name="xoaLoaiSanPham" type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal chỉnh sửa loại sản phẩm -->
        <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-yellow-500 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-white font-semibold text-lg">Chỉnh sửa loại sản phẩm</h2>
                    <button onclick="hideModal()" class="bg-red-400 px-2 rounded text-white text-xl">&times;</button>
                </div>
                <!-- Body -->
                <div class="p-6">
                    <!-- Form chỉnh sửa loại sản phẩm -->
                    <form action="" method="POST">
                        <input type="hidden" name="maLoaiSanPham" id="editMaLoaiSanPham">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Tên loại sản phẩm:</label>
                            <input type="text" name="tenLoaiSanPham" id="editTenLoaiSanPham" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none" placeholder="Nhập tên loại sản phẩm" required>
                        </div>
                        <button name="chinhSuaLoaiSanPham" type="submit" class="w-full bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-400">
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
    function showDeleteModal(maLoaiSanPham) {
        document.getElementById('deleteModal').style.display = 'flex';
        // Gán mã loại sản phẩm vào input hidden
        document.getElementById('deleteMaLoaiSanPham').value = maLoaiSanPham;
    }

    // Hiển thị modal chỉnh sửa
    function showEditModal(maLoaiSanPham, tenLoaiSanPham) {
        document.getElementById('editModal').style.display = 'flex';
        // Gán dữ liệu vào các input trong form
        document.getElementById('editMaLoaiSanPham').value = maLoaiSanPham;
        document.getElementById('editTenLoaiSanPham').value = tenLoaiSanPham;
    }
</script>
