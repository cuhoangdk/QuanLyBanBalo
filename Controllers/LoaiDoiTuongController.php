<?php
include_once '../Models/LoaiDoiTuong.php';

class LoaiDoiTuongController {
    protected $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function layDanhSachLoaiDoiTuong() {
        $sql = "SELECT * FROM tloaidt";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhSachLoaiDoiTuong = array();
        while ($row = $result->fetch_assoc()) {
            $loaiDoiTuong = new LoaiDoiTuong(
                $row['MaDT'], $row['TenLoaiDoiTuong']
            );
            $danhSachLoaiDoiTuong[] = $loaiDoiTuong;
        }
        $stmt->close();
        return $danhSachLoaiDoiTuong;
    }
}