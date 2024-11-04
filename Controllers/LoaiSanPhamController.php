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
                $row['MaLoai'], $row['TenLoaiSanPham']
            );
            $danhSachLoaiSanPham[] = $loaiSanPham;
        }
        $stmt->close();
        return $danhSachLoaiSanPham;
    }
}