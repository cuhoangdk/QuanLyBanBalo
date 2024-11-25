<?php
include_once '../Models/SanPham.php';
include_once '../Utils/utils.php';
class SanPhamController
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Lấy danh sách sản phẩm
     * @return SanPham[]
     */
    public function layDanhMucSanPham()
    {
        $sql = "SELECT * FROM tdanhmucsp WHERE trang_thai=1";
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

    /**
     * Tìm kiếm sản phẩm theo tên
     * @param string $tenSanPham
     * @return SanPham[]
     */
    public function timKiemSanPhamTheoTen($tenSanPham)
    {
        $sql = "SELECT * FROM tdanhmucsp WHERE TenSP LIKE ? AND trang_thai=1";
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
    /**
     * Tìm kiếm sản phẩm với các tiêu chí khác nhau
     * @param string|null $tenSanPham
     * @param float|null $giaMin
     * @param float|null $giaMax
     * @param string|null $hangSanXuat
     * @param string|null $loai
     * @param string|null $nuocSanXuat
     * @param string|null $doiTuong
     * @param string|null $chatLieu
     * @param int $limit
     * @param int $offset
     * @return array gồm danh sách sản phẩm và tổng số sản phẩm phù hợp với tiêu chí tìm kiếm
     */
    public function timKiemSanPham($tenSanPham = null, $giaMin = null, $giaMax = null, $hangSanXuat = null, $loai = null, $nuocSanXuat = null, $doiTuong = null, $chatLieu = null,$limit, $offset)
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM tdanhmucsp WHERE trang_thai=1";
        $params = [];
        $types = '';

        if ($tenSanPham !== null) {
            $sql .= " AND ten_san_pham LIKE ?";
            $params[] = "%" . $tenSanPham . "%";
            $types .= 's';
        }
        if ($giaMin !== null) {
            $sql .= " AND gia >= ?";
            $params[] = $giaMin;
            $types .= 'd';
        }
        if ($giaMax !== null) {
            $sql .= " AND gia <= ?";
            $params[] = $giaMax;
            $types .= 'd';
        }
        if ($hangSanXuat !== null) {
            $sql .= " AND ma_hang_san_xuat = ?";
            $params[] = $hangSanXuat;
            $types .= 's';
        }
        if ($loai !== null) {
            $sql .= " AND ma_loai_san_pham = ?";
            $params[] = $loai;
            $types .= 's';
        }
        if ($nuocSanXuat !== null) {
            $sql .= " AND ma_quoc_gia_san_xuat = ?";
            $params[] = $nuocSanXuat;
            $types .= 's';
        }
        if ($doiTuong !== null) {
            $sql .= " AND ma_loai_doi_tuong = ?";
            $params[] = $doiTuong;
            $types .= 's';
        }
        if ($chatLieu !== null) {
            $sql .= " AND ma_chat_lieu = ?";
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
    /**
     * Tìm kiếm sản phẩm theo mã sản phẩm
     * @param string $maSanPham
     * @return bool
     */
    private function kiemTraMaSanPhamTonTai($maSP)
    {
        $sql = "SELECT ma_san_pham FROM tdanhmucsp WHERE ma_san_pham = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maSP);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0; // Trả về true nếu mã sản phẩm đã tồn tại
    }

    /**
     * Kiểm tra tên sản phẩm đã tồn tại với trạng thái trang_thai = 1
     * @param string $tenSanPham
     * @return bool
     */
    public function kiemTraTenSanPhamTonTai($tenSanPham)
    {
        $sql = "SELECT ten_san_pham FROM tdanhmucsp WHERE ten_san_pham = ? AND trang_thai = 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenSanPham);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0; // Trả về true nếu tên sản phẩm đã tồn tại và đang kích hoạt
    }

    /**
     * Thêm sản phẩm mới vào cơ sở dữ liệu
     * @param string $tenSanPham
     * @param string $chatLieu
     * @param float $canNang
     * @param string $hangSanXuat
     * @param string $nuocSanXuat
     * @param int $thoiGianBaoHanh
     * @param string $gioiThieu
     * @param string $loai
     * @param string $doiTuong
     * @param string $anh
     * @param float $gia
     * @param int $soLuong
     * @return bool
     */
    public function themSanPham($tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong)
    {
        $maSP = taoMa($tenSanPham);  
        $trangthai = 1;
        $originalMaSP = $maSP;  // Lưu lại mã gốc để thêm số thứ tự nếu cần
        $i = 1;
        // Kiểm tra xem tên sản phẩm đã tồn tại với trang_thai = 1 chưa
        if ($this->kiemTraTenSanPhamTonTai($tenSanPham)) {
            return false;
        }
        // Kiểm tra xem mã sản phẩm đã tồn tại chưa và tạo mã mới nếu cần
        while ($this->kiemTraMaSanPhamTonTai($maSP)) {
            $maSP = $originalMaSP . $i;
            $i++;
        }
        $sql = "INSERT INTO tdanhmucsp (ma_san_pham, ten_san_pham, ma_chat_lieu, can_nang, ma_hang_san_xuat, ma_quoc_gia_san_xuat, thoi_gian_bao_hanh, gioi_thieu_san_pham, ma_loai_san_pham, ma_loai_doi_tuong, anh, gia, so_luong, trang_thai)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("sssdssdssssiii", $maSP , $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong, $trangthai);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Thêm sản phẩm thành công';
            return true;
        } else {
            $_SESSION['error'] = 'Lỗi khi thêm sản phẩm';
            return false;
        }
    }
    /**
     * Lấy thông tin sản phẩm theo mã sản phẩm
     * @param string $maSanPham
     * @return SanPham|null
     */
    public function laySanPhamTheoMa($maSanPham)
    {
        $sql = "SELECT * FROM tdanhmucsp WHERE ma_san_pham = ? AND trang_thai= 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maSanPham);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sanPham = new SanPham(
                $row['ma_san_pham'], $row['ten_san_pham'], $row['ma_chat_lieu'],
                $row['can_nang'], $row['ma_hang_san_xuat'], $row['ma_quoc_gia_san_xuat'],
                $row['thoi_gian_bao_hanh'], $row['gioi_thieu_san_pham'],
                $row['ma_loai_san_pham'], $row['ma_loai_doi_tuong'], $row['anh'],
                $row['gia'], $row['so_luong']
            );
            return $sanPham;
        } else {
            return null; // Không tìm thấy sản phẩm
        }
    }

    /**
     * Xóa sản phẩm theo mã sản phẩm
     * @param string $maSanPham
     * @return bool
     */
    public function xoaSanPham($maSanPham)
    {
        $sql = "UPDATE tdanhmucsp SET trang_thai = 0 WHERE ma_san_pham = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maSanPham);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa sản phẩm thành công';
            return true;
        } else {
            $_SESSION['error'] = 'Lỗi khi xóa sản phẩm';
            return false;
        }
    }

    /**
     * Chỉnh sửa thông tin sản phẩm
     * @param string $maSanPham
     * @param string $tenSanPham
     * @param string $chatLieu
     * @param float $canNang
     * @param string $hangSanXuat
     * @param string $nuocSanXuat
     * @param float $thoiGianBaoHanh
     * @param string $gioiThieuSanPham
     * @param string $loaiSanPham
     * @param string $doiTuong
     * @param string $anh
     * @param float $gia
     * @param int $soLuong
     * @return bool
     */
    public function chinhSuaSanPham($maSanPham, $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieuSanPham, $loaiSanPham, $doiTuong, $anh, $gia, $soLuong)
    {
        $sql = "UPDATE tdanhmucsp 
                SET ten_san_pham = ?, ma_chat_lieu = ?, can_nang = ?, ma_hang_san_xuat = ?, ma_quoc_gia_san_xuat = ?, thoi_gian_bao_hanh = ?, gioi_thieu_san_pham = ?, ma_loai_san_pham = ?, ma_loai_doi_tuong = ?, anh = ?, gia = ?, so_luong = ? 
                WHERE ma_san_pham = ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssdssdssssiis", $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieuSanPham, $loaiSanPham, $doiTuong, $anh, $gia, $soLuong, $maSanPham);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Chỉnh sửa sản phẩm thành công';
            return true; // Trả về true nếu câu lệnh thành công
        } else {
            $_SESSION['error'] = 'Lỗi khi chỉnh sửa sản phẩm';
            return false; // Trả về false nếu câu lệnh thất bại
        }
    }
    /**
     * Xử lý upload ảnh
     * @param array $file
     * @return string
     * @throws Exception
     */
    public function xuLyUploadAnh($file)
    {
        if (isset($file) && $file['error'] == 0) {
            $targetDir = "../Images/"; // Thư mục lưu trữ file ảnh
            $fileName = basename($file["name"]); // Lấy tên file
            $targetFile = $targetDir . $fileName; // Đường dẫn file
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); // Lấy đuôi file

            // Kiểm tra kích thước file
            $maxFileSize = 5 * 1024 * 1024; // 10MB
            if ($file["size"] > $maxFileSize) {
                throw new Exception("Kích thước file phải nhỏ hơn 10MB.");
            }

            // Kiểm tra định dạng file
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']; // Các loại file ảnh cho phép
            if (in_array($fileType, $allowedTypes)) {
                // Kiểm tra MIME type để đảm bảo đó là file ảnh
                $fileMimeType = mime_content_type($file["tmp_name"]); // Lấy MIME type của file
                $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']; // Các loại MIME type cho phép

                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    // Di chuyển file tới thư mục lưu trữ
                    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                        return $fileName;
                    } else {
                        throw new Exception("Không thể di chuyển tệp đã tải lên.");
                    }
                } else {
                    throw new Exception("Chỉ chấp nhận các file ảnh có định dạng JPG, JPEG, PNG, và GIF.");
                }
            } else {
                throw new Exception("Chỉ chấp nhận các file ảnh có định dạng JPG, JPEG, PNG, và GIF.");
            }
        } else {
            throw new Exception("Không có tệp nào được tải lên hoặc có lỗi trong quá trình tải lên.");
        }
    }
}