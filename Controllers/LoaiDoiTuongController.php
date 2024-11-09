<?php
include_once '../Models/LoaiDoiTuong.php';
include_once '../Utils/utils.php';
class LoaiDoiTuongController {
    protected $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function layDanhSachLoaiDoiTuong() {
        $sql = "SELECT * FROM tloaidt";
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
        if ($this->kiemTraTenLoaiDoiTuongTonTai($tenLoaiDoiTuong)) {
            return false; // Trả về false nếu tên loại đối tượng đã tồn tại
        }

        // Chèn loại đối tượng mới vào cơ sở dữ liệu
        $sql = "INSERT INTO tloaidt (ma_loai_doi_tuong, ten_loai_doi_tuong) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $maLoaiDoiTuongMoi, $tenLoaiDoiTuong);

        if ($stmt->execute()) {
            return true; // Trả về true nếu thêm thành công
        } else {
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
        $sql = "SELECT COUNT(*) as count FROM tloaidt WHERE ten_loai_doi_tuong = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenLoaiDoiTuong);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0; // Trả về true nếu tên loại đối tượng đã tồn tại
    }
}