<?php
include_once '../Models/SanPham.php';

class SanPhamController
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function layDanhMucSanPham()
    {
        $sql = "SELECT * FROM tdanhmucsp";
        $result = $this->connection->query($sql);
        $danhMucSanPham = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sanPham = new SanPham($row['MaSP'], $row['TenSP'], $row['MaChatLieu'], $row['CanNang'], $row['MaHangSX'], $row['MaNuocSX'], $row['ThoiGianBaoHanh'], $row['GioiThieuSP'], $row['MaLoai'], $row['MaDT'], $row['Anh'], $row['Gia'], $row['SoLuong']);
                array_push($danhMucSanPham, $sanPham);
            }
        }
        return $danhMucSanPham;
    }

    public function timKiemSanPhamTheoTen($tenSanPham)
    {
        $tenSanPham = "%" . $this->connection->real_escape_string($tenSanPham) . "%";
        $sql = "SELECT * FROM tdanhmucsp WHERE TenSP LIKE '$tenSanPham'";
        $result = $this->connection->query($sql);
        
        $danhMucSanPham = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sanPham = new SanPham(
                    $row['MaSP'], $row['TenSP'], $row['MaChatLieu'], 
                    $row['CanNang'], $row['MaHangSX'], $row['MaNuocSX'], 
                    $row['ThoiGianBaoHanh'], $row['GioiThieuSP'], 
                    $row['MaLoai'], $row['MaDT'], $row['Anh'], 
                    $row['Gia'], $row['SoLuong']
                );
                array_push($danhMucSanPham, $sanPham);
            }
        }
        return $danhMucSanPham;
    }
    public function timKiemSanPham($tenSanPham = null, $giaMin = null, $giaMax = null, $hangSanXuat = null, $loai = null, $nuocSanXuat = null, $doiTuong = null)
    {
        $sql = "SELECT * FROM tdanhmucsp WHERE 1=1";
        
        if ($tenSanPham !== null) {
            $tenSanPham = $this->connection->real_escape_string($tenSanPham);
            $sql .= " AND TenSP LIKE '%$tenSanPham%'";
        }

        if ($giaMin !== null) {
            $giaMin = (float)$giaMin;
            $sql .= " AND Gia >= $giaMin";
        }

        if ($giaMax !== null) {
            $giaMax = (float)$giaMax;
            $sql .= " AND Gia <= $giaMax";
        }

        if ($hangSanXuat !== null) {
            $hangSanXuat = $this->connection->real_escape_string($hangSanXuat);
            $sql .= " AND MaHangSX = '$hangSanXuat'";
        }

        if ($loai !== null) {
            $loai = $this->connection->real_escape_string($loai);
            $sql .= " AND MaLoai = '$loai'";
        }

        if ($nuocSanXuat !== null) {
            $nuocSanXuat = $this->connection->real_escape_string($nuocSanXuat);
            $sql .= " AND MaNuocSX = '$nuocSanXuat'";
        }

        if ($doiTuong !== null) {
            $doiTuong = $this->connection->real_escape_string($doiTuong);
            $sql .= " AND MaDT = '$doiTuong'";
        }

        $result = $this->connection->query($sql);
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
        return $danhMucSanPham;
    }


}