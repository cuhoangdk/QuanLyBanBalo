<?php
include_once '../Models/LoaiSanPham.php';
include_once '../Utils/utils.php';
class LoaiSanPhamController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    /**
     * Hàm lấy danh sách loại sản phẩm
     * @return LoaiSanPham[]
     */
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
        $maLoaiSanPham = taoMaDai($tenLoaiSanPham);

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
}