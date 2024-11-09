<?php
include_once '../Models/LoaiDoiTuong.php';

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
        $maLoaiDoiTuong = $this->taoMaLoaiDoiTuong($tenLoaiDoiTuong);

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

    // Hàm tạo mã loại đối tượng từ tên loại đối tượng
    private function taoMaLoaiDoiTuong($tenLoaiDoiTuong)
    {
        // Chuyển tên loại đối tượng thành dạng không dấu
        $tenLoaiDoiTuongKhongDau = $this->removeAccents($tenLoaiDoiTuong);

        // Tách tên loại đối tượng thành từng từ
        $tu = explode(' ', $tenLoaiDoiTuongKhongDau);

        // Lấy 2 ký tự đầu tiên của mỗi từ và ghép lại
        $maLDT = '';
        foreach ($tu as $t) {
            $maLDT .= substr($t, 0, min(2, strlen($t)));
        }

        return strtolower($maLDT); // Đảm bảo mã loại đối tượng là chữ in thường
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