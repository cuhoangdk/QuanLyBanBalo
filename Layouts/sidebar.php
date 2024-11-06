<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-1/5 bg-gray-700 text-gray-200 h-screen fixed top-0 left-0">
    <p class="block w-full py-1.5 px-4 text-3xl font-bold bg-gray-700 text-center hover:bg-lime-400">BALO ADMIN</p>
    <div class="">
        <ul class="mt-4 space-y-2">
            <li>
                <a href="../Views/DanhSachSanPham.php" class="block w-full py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'DanhSachSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-table-list mr-2"></i>Tra cứu
                </a>
            </li>
            <li>
                <a href="../Views/ThemSanPham.php" class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'ThemSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Thêm sản phẩm
                </a>
            </li>
            <li>
                <a href="../Views/XoaSanPham.php" class="block py-2 px-4 bg-gray-700 hover:text-black hover:bg-green-300 <?= $current_page == 'XoaSanPham.php' ? 'bg-green-300 text-black' : '' ?>">
                    <i class="fa-solid fa-circle-xmark mr-2"></i>Xóa sản phẩm
                </a>
            </li>
        </ul>
    </div>
</aside>