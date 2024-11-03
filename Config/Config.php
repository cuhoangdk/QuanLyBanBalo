<?php
// 1. Thông tin cấu hình cơ sở dữ liệu
$servername = 'localhost'; // Địa chỉ máy chủ cơ sở dữ liệu
$username = 'root';         // Tên người dùng cơ sở dữ liệu
$password = '';             // Mật khẩu cho tài khoản người dùng
$dbname = 'quanlybanhang';      // Tên cơ sở dữ liệu

// 2. Kết nối tới cơ sở dữ liệu
$connection = mysqli_connect($servername, $username, $password, $dbname);

// 3. Kiểm tra kết nối
if (!$connection) {
    die('Không thể kết nối tới cơ sở dữ liệu: ' . mysqli_connect_error());
}

// 4. Đặt charset thành UTF-8
if (!mysqli_set_charset($connection, 'utf8')) {
    die('Không thể thiết lập charset UTF-8: ' . mysqli_error($connection));
}

// 5. Đóng kết nối (nếu cần)
// mysqli_close($connection); // Bỏ dòng này nếu bạn muốn giữ kết nối mở

?>
