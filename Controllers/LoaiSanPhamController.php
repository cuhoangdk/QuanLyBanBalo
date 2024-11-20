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
        $sql = "SELECT * FROM tloaisp WHERE trang_thai = 1";
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
        $loaiSanPham = $this->kiemTraTenLoaiSanPhamTonTai($tenLoaiSanPham);

        if ($loaiSanPham) {
            if ($loaiSanPham['trang_thai'] == 0) {
                // Nếu tên loại sản phẩm đã tồn tại và trạng thái bằng 0 thì bật trạng thái thành 1
                $sqlUpdate = "UPDATE tloaisp SET trang_thai = 1 WHERE ten_loai_san_pham = ?";
                $stmtUpdate = $this->connection->prepare($sqlUpdate);
                $stmtUpdate->bind_param("s", $tenLoaiSanPham);
                if ($stmtUpdate->execute()) {
                    $_SESSION['success'] = 'Thêm loại sản phẩm thành công';
                    return true;
                } else {
                    $_SESSION['error'] = "Lỗi khi thêm loại sản phẩm";
                    return false;
                }
            } else {
                $_SESSION['error'] = "Tên loại sản phẩm đã tồn tại!";
                return false;
            }
        } else {
            // Chèn loại sản phẩm mới vào cơ sở dữ liệu
            $sql = "INSERT INTO tloaisp (ma_loai_san_pham, ten_loai_san_pham, trang_thai) VALUES (?, ?, 1)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("ss",$maLoaiSanPham, $tenLoaiSanPham);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Thêm loại sản phẩm thành công';
                return true;
            } else {
                $_SESSION['error'] = "Lỗi khi thêm loại sản phẩm";
                return false;
            }
        }
    }
    public function suaLoaiSanPham($maLoaiSanPham, $tenLoaiSanPham)
    {
        // Kiểm tra tên loại sản phẩm đã tồn tại hay chưa
        $loaiSanPham = $this->kiemTraTenLoaiSanPhamTonTai($tenLoaiSanPham);

        if ($loaiSanPham && $loaiSanPham['ma_loai_san_pham'] != $maLoaiSanPham) {
            $_SESSION['error'] = "Tên loại sản phẩm đã tồn tại!";
            return false; // Trả về false nếu tên loại sản phẩm đã tồn tại với mã khác
        }

        // Cập nhật loại sản phẩm
        $sql = "UPDATE tloaisp SET ten_loai_san_pham = ? WHERE ma_loai_san_pham = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $tenLoaiSanPham, $maLoaiSanPham);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Chỉnh sửa loại sản phẩm thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi chỉnh sửa loại sản phẩm";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }
    public function xoaLoaiSanPham($maLoaiSanPham)
    {
        // Cập nhật trạng thái loại sản phẩm thành 0
        $sql = "UPDATE tloaisp SET trang_thai = 0 WHERE ma_loai_san_pham = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maLoaiSanPham);
    
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa loại sản phẩm thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi xóa loại sản phẩm";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }    
    // Hàm kiểm tra tên loại sản phẩm đã tồn tại
    public function kiemTraTenLoaiSanPhamTonTai($tenLoaiSanPham)
    {
        $sql = "SELECT * FROM tloaisp WHERE ten_loai_san_pham = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenLoaiSanPham);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Trả về thông tin loại sản phẩm nếu tồn tại
        } else {
            return false; // Trả về false nếu không tồn tại
        }
    }
}