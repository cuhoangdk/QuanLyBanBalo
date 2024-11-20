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
        $sql = "SELECT * FROM thangsx WHERE trang_thai = 1";
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
        $hangSanXuat = $this->kiemTraTenHangSanXuatTonTai($tenHangSanXuat);

        if ($hangSanXuat) {
            if ($hangSanXuat['trang_thai'] == 0) {
                // Nếu tên hãng sản xuất đã tồn tại và trạng thái bằng 0 thì bật trạng thái thành 1
                $sqlUpdate = "UPDATE thangsx SET trang_thai = 1 WHERE ten_hang_san_xuat = ?";
                $stmtUpdate = $this->connection->prepare($sqlUpdate);
                $stmtUpdate->bind_param("s", $tenHangSanXuat);
                if ($stmtUpdate->execute()) {
                    $_SESSION['success'] = 'Thêm hãng sản xuất thành công';
                    return true; // Trả về true nếu cập nhật thành công
                } else {
                    $_SESSION['error'] = "Lỗi khi thêm hãng sản xuất";
                    return false; // Trả về false nếu có lỗi xảy ra khi cập nhật
                }
            } else {
                $_SESSION['error'] = "Tên hãng sản xuất đã tồn tại!";
                return false; // Trả về false nếu tên hãng sản xuất đã tồn tại và trạng thái không bằng 0
            }
        } else {
            // Chèn hãng sản xuất mới vào cơ sở dữ liệu
            $sql = "INSERT INTO thangsx (ma_hang_san_xuat, ten_hang_san_xuat, trang_thai) VALUES (?, ?, 1)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("ss", $maHangSanXuatMoi, $tenHangSanXuat);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Thêm hãng sản xuất thành công';
                return true; // Trả về true nếu thêm thành công
            } else {
                $_SESSION['error'] = "Lỗi khi thêm hãng sản xuất";
                return false; // Trả về false nếu có lỗi xảy ra
            }
        }
    }
    public function suaHangSanXuat($maHangSanXuat, $tenHangSanXuat)
    {
        // Kiểm tra tên hãng sản xuất đã tồn tại hay chưa
        $hangSanXuat = $this->kiemTraTenHangSanXuatTonTai($tenHangSanXuat);

        if ($hangSanXuat && $hangSanXuat['ma_hang_san_xuat'] != $maHangSanXuat) {
            $_SESSION['error'] = "Tên hãng sản xuất đã tồn tại!";
            return false; // Trả về false nếu tên hãng sản xuất đã tồn tại với mã khác
        }

        // Cập nhật hãng sản xuất
        $sql = "UPDATE thangsx SET ten_hang_san_xuat = ? WHERE ma_hang_san_xuat = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $tenHangSanXuat, $maHangSanXuat);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Chỉnh sửa hãng sản xuất thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi chỉnh sửa hãng sản xuất";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }

    public function xoaHangSanXuat($maHangSanXuat)
    {
        // Cập nhật trạng thái hãng sản xuất thành 0
        $sql = "UPDATE thangsx SET trang_thai = 0 WHERE ma_hang_san_xuat = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maHangSanXuat);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa hãng sản xuất thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi xóa hãng sản xuất";
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
        $sql = "SELECT * FROM thangsx WHERE ten_hang_san_xuat = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenHangSanXuat);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Trả về thông tin hãng sản xuất nếu tồn tại
        } else {
            return false; // Trả về false nếu không tồn tại
        }
    }
}
?>