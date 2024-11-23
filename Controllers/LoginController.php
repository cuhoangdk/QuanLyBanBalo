<?php
include_once __DIR__ . '/../Models/NhanVien.php';
class LoginController {
    protected $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    /**
     * Phương thức đăng nhập
     * @param string $username Tên đăng nhập
     * @param string $password Mật khẩu
     * @return bool Trả về true nếu đăng nhập thành công
     */
    public function login($username, $password) {
        // Mã hóa mật khẩu đê so sánh với mật khẩu trong cơ sở dữ liệu
        $hashedPassword = md5($password);

        // Kiểm tra thông tin đăng nhập trả về mã nhân viên và quyền
        [$maUser, $quyen] = $this->getUserByUsernameAndPassword($username, $hashedPassword);

        // Nếu không tìm thấy user, trả về false
        if (!$maUser) {
            return false;
        }
        // Lưu thông tin quyền vào session
        $_SESSION['quyen'] = $quyen;

        if($quyen == 1 || $quyen == 2){ // Nếu quyền là 1 hoặc 2 thì chuyển đến trang quản lý
            $nhanVien = $this->getNhanVienByMaNhanVien($maUser);
                // Đăng nhập thành công, lưu thông tin nhân viên vào session
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['nhanVien'] = [
                    'ma_nhan_vien' => $nhanVien->getMaNhanVien(),
                    'ho_nhan_vien' => $nhanVien->getHoNhanVien(),
                    'ten_nhan_vien' => $nhanVien->getTenNhanVien(),
                    'chuc_vu' => $nhanVien->getChucVu(),
                ];
                    // Lưu thời gian bắt đầu và thời gian hết hạn session
                $_SESSION['login_time'] = time();
                $_SESSION['expire_time'] = time() + (20 * 60); // 20min
                //$_SESSION['expire_time'] = time() + (5); // 5sec
                return true;
        }else if($quyen == 3){ // Nếu quyền là 3 thì chuyển hướng đến trang khách hàng
            $this->getKhachHangByMaKhachHang();
            return false;
        }else{ // Nếu quyền không phải 1, 2 hoặc 3 thì trả về false
            return false;
        }
    }
    /**
     * Kiểm tra xem session có hết hạn chưa
     * @return bool Trả về true nếu session hết hạn
     */
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
    /**
     * Lấy thông tin user dựa trên username và password
     * @param string $username Tên đăng nhập
     * @param string $hashedPassword Mật khẩu đã mã hóa
     * @return array|null Mảng chứa mã user và quyền hoặc null nếu không tìm thấy
     */
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
            return [$result['ma_user'], $result['quyen']];
        } else {
            return null;
        }
    }
    public function getKhachHangByMaKhachHang(){
        $this->logout();
        echo "Chưa xây dựng phía khách hàng";
        exit();
    }
    /**
     * Lấy thông tin nhân viên dựa trên mã nhân viên
     * @param string $maNhanVien Mã nhân viên
     * @return NhanVien|null Đối tượng NhanVien hoặc null nếu không tìm thấy
     */
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
                $result['gioi_tinh'],
                $result['so_dien_thoai'],
                $result['dia_chi'],
                $result['chuc_vu'],
                $result['anh_dai_dien'],
                $result['email'],
                $result['cccd'],
                $result['ghi_chu']
            );
        } else {
            return null;
        }
    }
    /**
     * Phương thức đăng xuất
     * Hủy bỏ session hiện tại và đăng xuất người dùng
     * @return bool Trả về true nếu đăng xuất thành công
     */
    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }
}
