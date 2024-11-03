<?php
class NhanVienController {
    protected $connection;
    public function __construct($connection) {
        $this->connection = $connection;
    }
    // Phương thức xử lý đăng nhập
    public function login($username, $password) {
        // Mã hóa mật khẩu bằng MD5
        $hashedPassword = md5($password);
        // Kiểm tra thông tin đăng nhập
        $nhanVien = $this->connection->getNhanVienByUsernameAndPassword($username, $hashedPassword);
        if ($nhanVien) {
            // Đăng nhập thành công, lưu thông tin nhân viên vào session
            session_start();
            $_SESSION['nhanVien'] = [
                'maNhanVien' => $nhanVien->getMaNhanVien(),
                'tenNhanVien' => $nhanVien->getTenNhanVien(),
                'chucVu' => $nhanVien->getChucVu(),
            ];
            return "Đăng nhập thành công";
        } else {
            // Đăng nhập thất bại
            return "Tên đăng nhập hoặc mật khẩu không đúng";
        }
    }
    // Phương thức đăng xuất
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        return "Đã đăng xuất thành công";
    }
}
