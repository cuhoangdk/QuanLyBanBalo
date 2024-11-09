<?php
include_once '../Models/LoaiSanPham.php';
class LoaiSanPhamController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function layDanhSachLoaiSanPham()
    {
        $sql = "SELECT * FROM tloaisp";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhSachLoaiSanPham = array();
        while ($row = $result->fetch_assoc()) {
            $loaiSanPham = new LoaiSanPham(
                $row['ma_loai_san_pham'], $row['ten_loai_san_pham']
            );
            $danhSachLoaiSanPham[] = $loaiSanPham;
        }
        $stmt->close();
        return $danhSachLoaiSanPham;
    }
    public function themLoaiSanPham($tenLoaiSanPham)
    {
        // Tạo mã loại sản phẩm từ tên loại sản phẩm
        $maLoaiSanPham = $this->taoMaLoaiSanPham($tenLoaiSanPham);

        // Kiểm tra tên loại sản phẩm đã tồn tại hay chưa
        if ($this->kiemTraTenLoaiSanPhamTonTai($tenLoaiSanPham)) {
            return false; // Trả về false nếu tên loại sản phẩm đã tồn tại
        }

        // Chèn loại sản phẩm mới vào cơ sở dữ liệu
        $sql = "INSERT INTO tloaisp (ma_loai_san_pham, ten_loai_san_pham) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $maLoaiSanPham, $tenLoaiSanPham);

        if ($stmt->execute()) {
            return true; // Trả về true nếu thêm thành công
        } else {
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }

    // Hàm tạo mã loại sản phẩm từ tên loại sản phẩm
    private function taoMaLoaiSanPham($tenLoaiSanPham)
    {
        // Chuyển tên loại sản phẩm thành dạng không dấu
        $tenLoaiSanPhamKhongDau = $this->removeAccents($tenLoaiSanPham);

        // Tách tên loại sản phẩm thành từng từ và ghép lại
        $tu = explode(' ', $tenLoaiSanPhamKhongDau);
        $maLSP = implode('', $tu);

        return strtolower($maLSP); // Đảm bảo mã loại sản phẩm là chữ in thường
    }
    // Hàm kiểm tra tên loại sản phẩm đã tồn tại
    public function kiemTraTenLoaiSanPhamTonTai($tenLoaiSanPham)
    {
        $sql = "SELECT COUNT(*) as count FROM tloaisp WHERE ten_loai_san_pham = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenLoaiSanPham);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0; // Trả về true nếu tên loại sản phẩm đã tồn tại
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