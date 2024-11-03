<?php
include_once '../Models/NhanVien.php';
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
    // Lấy thông tin nhân viên dựa trên username và password đã mã hóa
    public function getNhanVienByUsernameAndPassword($username, $hashedPassword) {
        // Tạo truy vấn SQL với tham số trực tiếp
        $query = "SELECT * FROM tnhanvien WHERE username = '$username' AND password = '$hashedPassword'";
        
        // Thực hiện truy vấn
        $result = $this->connection->query($query)->fetch_assoc();
        
        if ($result) {
            // Trả về một đối tượng NhanVien nếu tìm thấy
            return new NhanVien(
                $result['MaNhanVien'],
                $result['username'],
                $result['password'],
                $result['TenNhanVien'],
                $result['NgaySinh'],
                $result['SoDienThoai'],
                $result['DiaChi'],
                $result['ChucVu'],
                $result['GhiChu']
            );
        } else {
            return null;
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
