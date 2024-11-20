<?php
include_once '../Models/QuocGia.php';
include_once '../Utils/utils.php';
class QuocGiaController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Hàm lấy danh sách quốc gia
     * @return QuocGia[]
     */
    public function layDanhSachQuocGia()
    {
        $sql = "SELECT * FROM tquocgia WHERE trang_thai = 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhSachQuocGia = array();
        while ($row = $result->fetch_assoc()) {
            $quocGia = new QuocGia(
                $row['ma_quoc_gia'], $row['ten_quoc_gia']
            );
            $danhSachQuocGia[] = $quocGia;
        }
        $stmt->close();
        return $danhSachQuocGia;
    }
    public function themQuocGia($tenQuocGia)
    {
        // Tạo mã quốc gia từ tên quốc gia
        $maQuocGia = taoMaDai($tenQuocGia);

        // Kiểm tra tên quốc gia đã tồn tại hay chưa
        $quocGia = $this->kiemTraTenQuocGiaTonTai($tenQuocGia);

        if ($quocGia) {
            if ($quocGia['trang_thai'] == 0) {
                // Nếu tên quốc gia đã tồn tại và trạng thái bằng 0 thì bật trạng thái thành 1
                $sqlUpdate = "UPDATE tquocgia SET trang_thai = 1 WHERE ten_quoc_gia = ?";
                $stmtUpdate = $this->connection->prepare($sqlUpdate);
                $stmtUpdate->bind_param("s", $tenQuocGia);
                if ($stmtUpdate->execute()) {
                    $_SESSION['success'] = 'Thêm quốc gia thành công';
                    return true;
                } else {
                    $_SESSION['error'] = "Lỗi khi thêm quốc gia";
                    return false;
                }
            } else {
                $_SESSION['error'] = "Tên quốc gia đã tồn tại!";
                return false;
            }
        } else {
            // Chèn quốc gia mới vào cơ sở dữ liệu
            $sql = "INSERT INTO tquocgia (ma_quoc_gia, ten_quoc_gia, trang_thai) VALUES (?, ?, 1)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("ss", $maQuocGia, $tenQuocGia);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Thêm quốc gia thành công';
                return true;
            } else {
                $_SESSION['error'] = "Lỗi khi thêm quốc gia";
                return false;
            }
        }
    }
    public function suaQuocGia($maQuocGia, $tenQuocGia)
    {
        // Kiểm tra tên quốc gia đã tồn tại hay chưa
        $quocGia = $this->kiemTraTenQuocGiaTonTai($tenQuocGia);

        if ($quocGia && $quocGia['ma_quoc_gia'] != $maQuocGia) {
            $_SESSION['error'] = "Tên quốc gia đã tồn tại!";
            return false; // Trả về false nếu tên quốc gia đã tồn tại với mã khác
        }

        // Cập nhật quốc gia
        $sql = "UPDATE tquocgia SET ten_quoc_gia = ? WHERE ma_quoc_gia = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $tenQuocGia, $maQuocGia);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Chỉnh sửa quốc gia thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi chỉnh sửa quốc gia";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }
    public function xoaQuocGia($maQuocGia)
    {
        // Cập nhật trạng thái quốc gia thành 0
        $sql = "UPDATE tquocgia SET trang_thai = 0 WHERE ma_quoc_gia = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maQuocGia);
    
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa quốc gia thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi xóa quốc gia";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }    
    // Hàm kiểm tra tên quốc gia đã tồn tại
    private function kiemTraTenQuocGiaTonTai($tenQuocGia)
    {
        $sql = "SELECT * FROM tquocgia WHERE ten_quoc_gia = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenQuocGia);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Trả về thông tin quốc gia nếu tồn tại
        } else {
            return false; // Trả về false nếu không tồn tại
        }
    }
}