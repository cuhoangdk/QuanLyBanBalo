<?php
include_once '../Models/HangSanXuat.php';
include_once '../Utils/utils.php';
class HangSanXuatController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    /**
     * Summary of layDanhSachHangSanXuat
     * @return HangSanXuat[]
     */
    public function layDanhSachHangSanXuat()
    {
        $sql = "SELECT * FROM thangsx";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhSachHangSanXuat = array();
        while ($row = $result->fetch_assoc()) {
            $hangSanXuat = new HangSanXuat(
                $row['ma_hang_san_xuat'], $row['ten_hang_san_xuat']
            );
            $danhSachHangSanXuat[] = $hangSanXuat;
        }
        $stmt->close();
        return $danhSachHangSanXuat;
    }
    public function themHangSanXuat($tenHangSanXuat)
    {
        // Tạo mã hãng sản xuất từ tên hãng sản xuất
        $maHangSanXuat = taoMa($tenHangSanXuat);

        // Kiểm tra mã hãng sản xuất đã tồn tại hay chưa
        $maHangSanXuatMoi = $maHangSanXuat;
        $stt = 1;
        while ($this->kiemTraMaHangSanXuatTonTai($maHangSanXuatMoi)) {
            $maHangSanXuatMoi = $maHangSanXuat . $stt;
            $stt++;
        }

        // Kiểm tra tên hãng sản xuất đã tồn tại hay chưa
        if ($this->kiemTraTenHangSanXuatTonTai($tenHangSanXuat)) {
            return false; // Trả về false nếu tên hãng sản xuất đã tồn tại
        }

        // Chèn hãng sản xuất mới vào cơ sở dữ liệu
        $sql = "INSERT INTO thangsx (ma_hang_san_xuat, ten_hang_san_xuat) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $maHangSanXuatMoi, $tenHangSanXuat);

        if ($stmt->execute()) {
            return true; // Trả về true nếu thêm thành công
        } else {
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }
    // Hàm kiểm tra mã hãng sản xuất đã tồn tại
    private function kiemTraMaHangSanXuatTonTai($maHangSanXuat)
    {
        $sql = "SELECT COUNT(*) as count FROM thangsx WHERE ma_hang_san_xuat = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maHangSanXuat);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0; // Trả về true nếu mã hãng sản xuất đã tồn tại
    }
    // Hàm kiểm tra tên hãng sản xuất đã tồn tại
    public function kiemTraTenHangSanXuatTonTai($tenHangSanXuat)
    {
        $sql = "SELECT COUNT(*) as count FROM thangsx WHERE ten_hang_san_xuat = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenHangSanXuat);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0; // Trả về true nếu tên hãng sản xuất đã tồn tại
    }
}
?>