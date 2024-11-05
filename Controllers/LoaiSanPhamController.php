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
}