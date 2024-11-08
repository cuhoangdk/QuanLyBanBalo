<?php
include_once __DIR__ . '/../Models/NhanVien.php';
class LoginController {
    protected $connection;

    // Khởi tạo với dependency của NhanVienModel
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Phương thức xử lý đăng nhập
    public function login($username, $password) {
        // Mã hóa mật khẩu bằng MD5
        $hashedPassword = md5($password);

        // Kiểm tra thông tin đăng nhập
        $nhanVien = $this->getNhanVienByUsernameAndPassword($username, $hashedPassword);

        if ($nhanVien) {
            // Đăng nhập thành công, lưu thông tin nhân viên vào session
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['nhanVien'] = [
                'ma_nhan_vien' => $nhanVien->getMaNhanVien(),
                'ten_nhan_vien' => $nhanVien->getTenNhanVien(),
                'chuc_vu' => $nhanVien->getChucVu(),
            ];
            return true;
        } else {
            // Đăng nhập thất bại
            return false;
        }
    }

    // Lấy thông tin nhân viên dựa trên username và password đã mã hóa
    public function getNhanVienByUsernameAndPassword($username, $hashedPassword) {
        // Tạo truy vấn SQL với tham số trực tiếp
        // Sử dụng prepared statements để tránh SQL injection
        $stmt = $this->connection->prepare("SELECT * FROM tnhanvien WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $hashedPassword);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if ($result) {
            // Trả về một đối tượng NhanVien nếu tìm thấy
            return new NhanVien(
                $result['ma_nhan_vien'],
                $result['username'],
                $result['password'],
                $result['ten_nhan_vien'],
                $result['ngay_sinh'],
                $result['so_dien_thoai'],
                $result['dia_chi'],
                $result['chuc_vu'],
                $result['ghi_chu']
            );
        } else {
            return null;
        }
    }
    
    /**
     * Phương thức đăng xuất
     * Hủy bỏ session hiện tại và đăng xuất người dùng
     * @return string Thông báo đăng xuất thành công
     */
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
