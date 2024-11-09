<?php
include_once '../Models/HangSanXuat.php';

class HangSanXuatController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
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
        $maHangSanXuat = $this->taoMaHangSanXuat($tenHangSanXuat);

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

    // Hàm tạo mã hãng sản xuất từ tên hãng sản xuất
    private function taoMaHangSanXuat($tenHangSanXuat)
    {
        // Chuyển tên hãng sản xuất thành dạng không dấu
        $tenHangSanXuatKhongDau = $this->removeAccents($tenHangSanXuat);

        // Tách tên hãng sản xuất thành từng từ
        $tu = explode(' ', $tenHangSanXuatKhongDau);

        // Nếu tên hãng có một từ, lấy 3 ký tự đầu tiên
        if (count($tu) === 1) {
            $maHSX = substr($tu[0], 0, min(3, strlen($tu[0])));
        } else {
            // Nếu tên hãng có nhiều từ, lấy 1 ký tự đầu của mỗi từ
            $maHSX = '';
            foreach ($tu as $t) {
                $maHSX .= substr($t, 0, 1);
            }
        }

        return strtolower($maHSX); // Đảm bảo mã hãng sản xuất là chữ in thường
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

    // Hàm chuyển tiếng Việt có dấu thành không dấu
    private function removeAccents($str)
    {
        $unwanted_array = [
            'á'=>'a','à'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a',
            'ă'=>'a','ắ'=>'a','ằ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
            'â'=>'a','ấ'=>'a','ầ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',
            'é'=>'e','è'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e',
            'ê'=>'e','ế'=>'e','ề'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
            'í'=>'i','ì'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',
            'ó'=>'o','ò'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o',
            'ô'=>'o','ố'=>'o','ồ'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
            'ơ'=>'o','ớ'=>'o','ờ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',
            'ú'=>'u','ù'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u',
            'ư'=>'u','ứ'=>'u','ừ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
            'ý'=>'y','ỳ'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
            'đ'=>'d'
        ];
        return strtr(mb_strtolower($str), $unwanted_array);
    }

}
?>