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
                $row['MaSP'], $row['TenSP'], $row['MaChatLieu'],
                $row['CanNang'], $row['MaHangSX'], $row['MaNuocSX'],
                $row['ThoiGianBaoHanh'], $row['GioiThieuSP'],
                $row['MaLoai'], $row['MaDT'], $row['Anh'],
                $row['Gia'], $row['SoLuong']
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
                $row['MaSP'], $row['TenSP'], $row['MaChatLieu'],
                $row['CanNang'], $row['MaHangSX'], $row['MaNuocSX'],
                $row['ThoiGianBaoHanh'], $row['GioiThieuSP'],
                $row['MaLoai'], $row['MaDT'], $row['Anh'],
                $row['Gia'], $row['SoLuong']
            );
            $danhMucSanPham[] = $sanPham;
        }
        $stmt->close();
        return $danhMucSanPham;
    }

    // Tìm kiếm sản phẩm với các tiêu chí
    public function timKiemSanPham($tenSanPham = null, $giaMin = null, $giaMax = null, $hangSanXuat = null, $loai = null, $nuocSanXuat = null, $doiTuong = null)
    {
        $sql = "SELECT * FROM tdanhmucsp WHERE 1=1";
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

        $stmt = $this->connection->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $danhMucSanPham = [];
        while ($row = $result->fetch_assoc()) {
            $sanPham = new SanPham(
                $row['MaSP'], $row['TenSP'], $row['MaChatLieu'],
                $row['CanNang'], $row['MaHangSX'], $row['MaNuocSX'],
                $row['ThoiGianBaoHanh'], $row['GioiThieuSP'],
                $row['MaLoai'], $row['MaDT'], $row['Anh'],
                $row['Gia'], $row['SoLuong']
            );
            $danhMucSanPham[] = $sanPham;
        }
        $stmt->close();
        return $danhMucSanPham;
    }
    // Thêm sản phẩm
    public function themSanPham($tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong)
    {
        $sql = "INSERT INTO tdanhmucsp (TenSP, MaChatLieu, CanNang, MaHangSX, MaNuocSX, ThoiGianBaoHanh, GioiThieuSP, MaLoai, MaDT, Anh, Gia, SoLuong)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssisssisssii", $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong);

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
        $stmt->bind_param("i", $maSanPham);

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
                    TenSP = ?, MaChatLieu = ?, CanNang = ?, MaHangSX = ?, MaNuocSX = ?, 
                    ThoiGianBaoHanh = ?, GioiThieuSP = ?, MaLoai = ?, MaDT = ?, Anh = ?, Gia = ?, SoLuong = ? 
                WHERE MaSP = ?";
                
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssisssisssiii", $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieu, $loai, $doiTuong, $anh, $gia, $soLuong, $maSanPham);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}