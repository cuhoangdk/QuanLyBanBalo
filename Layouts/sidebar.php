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
                <a href="../Pages/DanhSachSanPham.php" title="Quản lý sản phẩm"
                    class="block w-full py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'DanhSachSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i><span class="hidden md:inline">Quản lý sản phẩm</span>
                </a>
            </li>            
            <li>
                <a href="../Pages/QuanLyChatLieu.php"title="Quản lý chất liệu"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyChatLieu.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i><span class="hidden md:inline">Quản lý chất liệu</span>
                </a>
            </li>
            <li>
                <a href="../Pages/QuanLyHangSanXuat.php" title="Quản lý hãng sản xuất"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyHangSanXuat.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i><span class="hidden md:inline">Quản lý hãng sản xuất</span>
                </a>
            </li>
            <li>
                <a href="../Pages/QuanLyLoaiDoiTuong.php" title="Quản lý loại đối tượng"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyLoaiDoiTuong.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i><span class="hidden md:inline">Quản lý loại đối tượng</span>
                </a>
            </li>
            <li>
                <a href="../Pages/QuanLyLoaiSanPham.php" title="Quản lý loại sản phẩm"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyLoaiSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i><span class="hidden md:inline">Quản lý loại sản phẩm</span>
                </a>
            </li>
            <li>
                <a href="../Pages/QuanLyQuocGia.php" title="Quản lý quốc gia"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyQuocGia.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i><span class="hidden md:inline">Quản lý quốc gia</span>
                </a>
            </li>
        </ul>
    </div>
</aside>