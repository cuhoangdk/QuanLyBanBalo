<?php
include_once '../Models/QuocGia.php';
class QuocGiaController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function layDanhSachQuocGia()
    {
        $sql = "SELECT * FROM tquocgia";
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
        if ($this->kiemTraTenQuocGiaTonTai($tenQuocGia)) {
            return false; // Trả về false nếu tên quốc gia đã tồn tại
        }

        // Chèn quốc gia mới vào cơ sở dữ liệu
        $sql = "INSERT INTO tquocgia (ma_quoc_gia, ten_quoc_gia) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $maQuocGia, $tenQuocGia);

        if ($stmt->execute()) {
            return true; // Trả về true nếu thêm thành công
        } else {
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }

    // Hàm kiểm tra tên quốc gia đã tồn tại
    private function kiemTraTenQuocGiaTonTai($tenQuocGia)
    {
        $sql = "SELECT COUNT(*) as count FROM tquocgia WHERE ten_quoc_gia = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenQuocGia);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0; // Trả về true nếu tên quốc gia đã tồn tại
    }
}