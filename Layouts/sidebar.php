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
                <a href="../Pages/DanhSachSanPham.php"
                    class="block w-full py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'DanhSachSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i>Quản lý sản phẩm
                </a>
            </li>            
            <li>
                <a href="../Pages/QuanLyChatLieu.php"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyChatLieu.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i>Quản lý chất liệu
                </a>
            </li>
            <li>
                <a href="../Pages/QuanLyHangSanXuat.php"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyHangSanXuat.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i>Quản lý hãng sản xuất
                </a>
            </li>
            <li>
                <a href="../Pages/QuanLyLoaiDoiTuong.php"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyLoaiDoiTuong.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i>Quản lý loại đối tượng
                </a>
            </li>
            <li>
                <a href="../Pages/QuanLyLoaiSanPham.php"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyLoaiSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i>Quản lý loại sản phẩm
                </a>
            </li>
            <li>
                <a href="../Pages/QuanLyQuocGia.php"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'QuanLyQuocGia.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i>Quản lý quốc gia
                </a>
            </li>
            <li>                
                <a href="../Pages/ThemSanPham.php"
                    class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'ThemSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Thêm sản phẩm
                </a>
            </li>
        </ul>
    </div>
</aside>