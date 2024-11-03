<?php
include_once '../Models/HangSanXuat.php';

class HangSanXuatController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function layDanhSachHangSanXuat()
    {
        $sql = "SELECT * FROM thangsanxuat";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhSachHangSanXuat = array();
        while ($row = $result->fetch_assoc()) {
            $hangSanXuat = new HangSanXuat(
                $row['MaHangSX'], $row['TenHangSX']
            );
            $danhSachHangSanXuat[] = $hangSanXuat;
        }
        $stmt->close();
        return $danhSachHangSanXuat;
    }
}
?>