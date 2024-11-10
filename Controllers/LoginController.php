<?php
include_once __DIR__ . '/../Models/NhanVien.php';
class LoginController {
    protected $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Phương thức xử lý đăng nhập
    public function login($username, $password) {
        // Mã hóa mật khẩu bằng MD5
        $hashedPassword = md5($password);

        // Kiểm tra thông tin đăng nhập
        $nhanVien = $this->getUserByUsernameAndPassword($username, $hashedPassword);

        if ($nhanVien) {
            // Đăng nhập thành công, lưu thông tin nhân viên vào session
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['nhanVien'] = [
                'ho_nhan_vien' => $nhanVien->getHoNhanVien(),
                'ma_nhan_vien' => $nhanVien->getMaNhanVien(),
                'ten_nhan_vien' => $nhanVien->getTenNhanVien(),
                'chuc_vu' => $nhanVien->getChucVu(),
            ];
                // Lưu thời gian bắt đầu và thời gian hết hạn session
            $_SESSION['login_time'] = time();
            $_SESSION['expire_time'] = time() + (20 * 60); // 20min
            return true;
        } else {
            // Đăng nhập thất bại
            return false;
        }
    }
    public function isSessionExpired() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra nếu session có thời gian hết hạn
        if (isset($_SESSION['expire_time'])) {
            if (time() > $_SESSION['expire_time']) {
                // Hết hạn, hủy session
                $this->logout();
                return true;
            }
        }
        return false;
    }
    // Lấy thông tin nhân viên dựa trên username và password đã mã hóa
    public function getUserByUsernameAndPassword($username, $hashedPassword) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Sử dụng prepared statements để tránh SQL injection
        $stmt = $this->connection->prepare("SELECT * FROM tuser WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $hashedPassword);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($result) {
            $maNhanVien = $result['ma_user'];
            $_SESSION['quyen'] = $result['quyen'];

            if($_SESSION['quyen'] == 1 || $_SESSION['quyen'] == 2){
                $nhanVien = $this->getNhanVienByMaNhanVien($maNhanVien);
                return $nhanVien;
            }else if($_SESSION['quyen'] == 3){
                $this->logout();
                echo "Chưa xây dựng phía khách hàng";
                exit();
            }
        } else {
            return null;
        }
    }
    public function getNhanVienByMaNhanVien($maNhanVien) {
        // Sử dụng prepared statements để tránh SQL injection
        $stmt = $this->connection->prepare("SELECT * FROM tnhanvien WHERE ma_nhan_vien = ?");
        $stmt->bind_param("s", $maNhanVien);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($result) {
            // Trả về một đối tượng NhanVien nếu tìm thấy
            return new NhanVien(
                $result['ma_nhan_vien'],
                $result['ho_nhan_vien'],
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
     */
    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }
}
