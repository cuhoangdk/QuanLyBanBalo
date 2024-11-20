<?php
include_once '../Models/LoaiDoiTuong.php';
include_once '../Utils/utils.php';
class LoaiDoiTuongController {
    protected $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }
    /**
     * Hàm lấy danh sách loại đối tượng
     * @return LoaiDoiTuong[]
     */
    public function layDanhSachLoaiDoiTuong() {
        $sql = "SELECT * FROM tloaidt WHERE trang_thai = 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhSachLoaiDoiTuong = array();
        while ($row = $result->fetch_assoc()) {
            $loaiDoiTuong = new LoaiDoiTuong(
                $row['ma_loai_doi_tuong'], $row['ten_loai_doi_tuong']
            );
            $danhSachLoaiDoiTuong[] = $loaiDoiTuong;
        }
        $stmt->close();
        return $danhSachLoaiDoiTuong;
    }
    public function themLoaiDoiTuong($tenLoaiDoiTuong)
    {
        // Tạo mã loại đối tượng từ tên loại đối tượng
        $maLoaiDoiTuong = taoMa($tenLoaiDoiTuong);

        // Kiểm tra mã loại đối tượng đã tồn tại hay chưa
        $maLoaiDoiTuongMoi = $maLoaiDoiTuong;
        $stt = 1;
        while ($this->kiemTraMaLoaiDoiTuongTonTai($maLoaiDoiTuongMoi)) {
            $maLoaiDoiTuongMoi = $maLoaiDoiTuong . $stt;
            $stt++;
        }

        // Kiểm tra tên loại đối tượng đã tồn tại hay chưa
        $loaiDoiTuong = $this->kiemTraTenLoaiDoiTuongTonTai($tenLoaiDoiTuong);

        if ($loaiDoiTuong) {
            if ($loaiDoiTuong['trang_thai'] == 0) {
                // Nếu tên loại đối tượng đã tồn tại và trạng thái bằng 0 thì bật trạng thái thành 1
                $sqlUpdate = "UPDATE tloaidt SET trang_thai = 1 WHERE ten_loai_doi_tuong = ?";
                $stmtUpdate = $this->connection->prepare($sqlUpdate);
                $stmtUpdate->bind_param("s", $tenLoaiDoiTuong);
                if ($stmtUpdate->execute()) {
                    $_SESSION['success'] = 'Thêm loại đối tượng thành công';
                    return true; // Trả về true nếu cập nhật thành công
                } else {
                    $_SESSION['error'] = "Lỗi khi thêm loại đối tượng";
                    return false; // Trả về false nếu có lỗi xảy ra khi cập nhật
                }
            } else {
                $_SESSION['error'] = "Tên loại đối tượng đã tồn tại!";
                return false; // Trả về false nếu tên loại đối tượng đã tồn tại và trạng thái không bằng 0
            }
        } else {
            // Chèn loại đối tượng mới vào cơ sở dữ liệu
            $sql = "INSERT INTO tloaidt (ma_loai_doi_tuong, ten_loai_doi_tuong, trang_thai) VALUES (?, ?, 1)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("ss", $maLoaiDoiTuongMoi, $tenLoaiDoiTuong);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Thêm loại đối tượng thành công';
                return true; // Trả về true nếu thêm thành công
            } else {
                $_SESSION['error'] = "Lỗi khi thêm loại đối tượng";
                return false; // Trả về false nếu có lỗi xảy ra
            }
        }
    }
    public function suaLoaiDoiTuong($maLoaiDoiTuong, $tenLoaiDoiTuong)
    {
        // Kiểm tra tên loại đối tượng đã tồn tại hay chưa
        $loaiDoiTuong = $this->kiemTraTenLoaiDoiTuongTonTai($tenLoaiDoiTuong);

        if ($loaiDoiTuong && $loaiDoiTuong['ma_loai_doi_tuong'] != $maLoaiDoiTuong) {
            $_SESSION['error'] = "Tên loại đối tượng đã tồn tại!";
            return false; // Trả về false nếu tên loại đối tượng đã tồn tại với mã khác
        }

        // Cập nhật loại đối tượng
        $sql = "UPDATE tloaidt SET ten_loai_doi_tuong = ? WHERE ma_loai_doi_tuong = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $tenLoaiDoiTuong, $maLoaiDoiTuong);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Chỉnh sửa loại đối tượng thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi chỉnh sửa loại đối tượng";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }
    public function xoaLoaiDoiTuong($maLoaiDoiTuong)
    {
        // Cập nhật trạng thái loại đối tượng thành 0
        $sql = "UPDATE tloaidt SET trang_thai = 0 WHERE ma_loai_doi_tuong = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maLoaiDoiTuong);
    
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa loại đối tượng thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi xóa loại đối tượng";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }
    
    // Hàm kiểm tra mã loại đối tượng đã tồn tại
    private function kiemTraMaLoaiDoiTuongTonTai($maLoaiDoiTuong)
    {
        $sql = "SELECT COUNT(*) as count FROM tloaidt WHERE ma_loai_doi_tuong = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maLoaiDoiTuong);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0; // Trả về true nếu mã loại đối tượng đã tồn tại
    }
    // Hàm kiểm tra tên loại đối tượng đã tồn tại
    public function kiemTraTenLoaiDoiTuongTonTai($tenLoaiDoiTuong)
    {
        $sql = "SELECT * FROM tloaidt WHERE ten_loai_doi_tuong = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenLoaiDoiTuong);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Trả về thông tin loại đối tượng nếu tồn tại
        } else {
            return false; // Trả về false nếu không tồn tại
        }
    }
}