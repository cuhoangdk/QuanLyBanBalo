<?php
// lấy tên file hiện tại
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside class="w-1/5 bg-gray-700 text-gray-200 h-screen fixed top-0 left-0">
    <p class="block w-full py-1.5 px-4 text-3xl font-bold bg-gray-700 text-center hover:bg-lime-400">BALO ADMIN</p>
    <div class="">
        <ul class="mt-4 space-y-2">
            <li>
                <!-- Nếu trang hiện tại là trang DanhSachSanPham.php thì hiển thị màu nền xanh -->
                <a href="../Views/DanhSachSanPham.php"
                    class="block w-full py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'DanhSachSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i>Quản lý sản phẩm
                </a>
            </li>
            <li>
                <!-- Nếu trang hiện tại là trang ThemSanPham.php thì hiển thị màu nền xanh -->
                <a href="../Views/ThemSanPham.php"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'ThemSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Thêm sản phẩm
                </a>
            </li>
        </ul>
    </div>
</aside>