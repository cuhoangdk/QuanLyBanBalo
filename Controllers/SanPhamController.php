<?php
include_once '../Models/SanPham.php';

class SanPhamController
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Lấy danh mục sản phẩm
    public function layDanhMucSanPham()
    {
        $sql = "SELECT * FROM tdanhmucsp";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhMucSanPham = array();
        while ($row = $result->fetch_assoc()) {
            $sanPham = new SanPham(
                $row['ma_san_pham'], $row['ten_san_pham'], $row['ma_chat_lieu'],
                $row['can_nang'], $row['ma_hang_san_xuat'], $row['ma_quoc_gia_san_xuat'],
                $row['thoi_gian_bao_hanh'], $row['gioi_thieu_san_pham'],
                $row['ma_loai_san_pham'], $row['ma_loai_doi_tuong'], $row['anh'],
                $row['gia'], $row['so_luong']
            );
            $danhMucSanPham[] = $sanPham;
        }
        $stmt->close();
        return $danhMucSanPham;
    }

    // Tìm kiếm sản phẩm theo tên
    public function timKiemSanPhamTheoTen($tenSanPham)
    {
        $sql = "SELECT * FROM tdanhmucsp WHERE TenSP LIKE ?";
        $stmt = $this->connection->prepare($sql);
        $likeTenSanPham = "%" . $tenSanPham . "%";
        $stmt->bind_param("s", $likeTenSanPham);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhMucSanPham = array();
        while ($row = $result->fetch_assoc()) {
            $sanPham = new SanPham(
                $row['ma_san_pham'], $row['ten_san_pham'], $row['ma_chat_lieu'],
                $row['can_nang'], $row['ma_hang_san_xuat'], $row['ma_quoc_gia_san_xuat'],
                $row['thoi_gian_bao_hanh'], $row['gioi_thieu_san_pham'],
                $row['ma_loai_san_pham'], $row['ma_loai_doi_tuong'], $row['anh'],
                $row['gia'], $row['so_luong']
            );
            $danhMucSanPham[] = $sanPham;
        }
        $stmt->close();
        return $danhMucSanPham;
    }

    // Tìm kiếm sản phẩm với các tiêu chí
    public function timKiemSanPham($tenSanPham = null, $giaMin = null, $giaMax = null, $hangSanXuat = null, $loai = null, $nuocSanXuat = null, $doiTuong = null, $chatLieu = null,$limit, $offset)
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM tdanhmucsp WHERE 1=1";
        $params = [];
        $types = '';

        if ($tenSanPham !== null) {
            $sql .= " AND TenSP LIKE ?";
            $params[] = "%" . $tenSanPham . "%";
            $types .= 's';
        }
        if ($giaMin !== null) {
            $sql .= " AND Gia >= ?";
            $params[] = $giaMin;
            $types .= 'd';
        }
        if ($giaMax !== null) {
            $sql .= " AND Gia <= ?";
            $params[] = $giaMax;
            $types .= 'd';
        }
        if ($hangSanXuat !== null) {
            $sql .= " AND MaHangSX = ?";
            $params[] = $hangSanXuat;
            $types .= 's';
        }
        if ($loai !== null) {
            $sql .= " AND MaLoai = ?";
            $params[] = $loai;
            $types .= 's';
        }
        if ($nuocSanXuat !== null) {
            $sql .= " AND MaNuocSX = ?";
            $params[] = $nuocSanXuat;
            $types .= 's';
        }
        if ($doiTuong !== null) {
            $sql .= " AND MaDT = ?";
            $params[] = $doiTuong;
            $types .= 's';
        }
        if ($chatLieu !== null) {
            $sql .= " AND MaChatLieu = ?";
            $params[] = $chatLieu;
            $types .= 's';
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->connection->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $danhMucSanPham = [];
        while ($row = $result->fetch_assoc()) {
            $sanPham = new SanPham(
                $row['ma_san_pham'], $row['ten_san_pham'], $row['ma_chat_lieu'],
                $row['can_nang'], $row['ma_hang_san_xuat'], $row['ma_quoc_gia_san_xuat'],
                $row['thoi_gian_bao_hanh'], $row['gioi_thieu_san_pham'],
                $row['ma_loai_san_pham'], $row['ma_loai_doi_tuong'], $row['anh'],
                $row['gia'], $row['so_luong']
            );
            $danhMucSanPham[] = $sanPham;
        }
        $stmt->close();
        // Lấy tổng số sản phẩm phù hợp với tiêu chí tìm kiếm
        $result = $this->connection->query("SELECT FOUND_ROWS() as total");
        $totalSanPham = $result->fetch_assoc()['total'];
    return [$danhMucSanPham, $totalSanPham];
    }
    protected function taoMaSanPham($tenSanPham)
    {
        // Chuyển tên sản phẩm thành dạng không dấu
        $tenSanPhamKhongDau = $this->removeAccents($tenSanPham);

        // Tách tên sản phẩm thành từng từ
        $tu = explode(' ', $tenSanPhamKhongDau);

        // Lấy tối đa 2 ký tự đầu tiên của mỗi từ và ghép lại
        $maSP = '';
        foreach ($tu as $t) {
            // Kiểm tra độ dài của từ, nếu từ có ít hơn 2 ký tự thì lấy tất cả ký tự của từ đó
            $maSP .= substr($t, 0, min(2, strlen($t)));
        }

        return strtolower($maSP); // Đảm bảo mã sản phẩm là chữ in thường
    }

    // Hàm chuyển tiếng Việt có dấu thành không dấu
    protected function removeAccents($str)
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

    // Thêm sản phẩm
    public function themSanPham($tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong)
    {
        $sql = "INSERT INTO tdanhmucsp (ma_san_pham, ten_san_pham, ma_chat_lieu, can_nang, ma_hang_san_xuat, ma_quoc_gia_san_xuat, thoi_gian_bao_hanh, gioi_thieu_san_pham, ma_loai_san_pham, ma_loai_doi_tuong, anh, gia, so_luong)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $maSP = $this->taoMaSanPham($tenSanPham);        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("sssdssdssssii", $maSP , $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Xóa sản phẩm
    public function xoaSanPham($maSanPham)
    {
        $sql = "DELETE FROM tdanhmucsp WHERE MaSP = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maSanPham);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Chỉnh sửa sản phẩm
    public function chinhSuaSanPham($maSanPham, $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong)
    {
        $sql = "UPDATE tdanhmucsp SET 
                    ten_san_pham = ?, ma_chat_lieu = ?, can_nang = ?, ma_hang_san_xuat = ?, ma_quoc_gia_san_xuat = ?, 
                    thoi_gian_bao_hanh = ?, gioi_thieu_san_pham = ?, ma_loai_san_pham = ?, ma_loai_doi_tuong = ?, anh = ?, gia = ?, so_luong = ? 
                WHERE ma_san_pham = ?";
                
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssdssdsssiis", $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong, $maSanPham);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}