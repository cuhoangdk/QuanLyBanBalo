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
        $maQuocGia = $this->taoMaQuocGia($tenQuocGia);

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

    // Hàm tạo mã quốc gia từ tên quốc gia
    private function taoMaQuocGia($tenQuocGia)
    {
        // Chuyển tên quốc gia thành dạng không dấu
        $tenQuocGiaKhongDau = $this->removeAccents($tenQuocGia);

        // Tách tên quốc gia thành từng từ và ghép lại
        $tu = explode(' ', $tenQuocGiaKhongDau);
        $maQG = implode('', $tu);

        return strtolower($maQG); // Đảm bảo mã quốc gia là chữ in thường
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