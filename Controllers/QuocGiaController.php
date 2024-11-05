<?php
include_once '../Models/QuocGia.php';
class QuocGiaController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function layDanhSachQuocGia()
    {
        $sql = "SELECT * FROM tquocgia";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhSachQuocGia = array();
        while ($row = $result->fetch_assoc()) {
            $quocGia = new QuocGia(
                $row['ma_quoc_gia'], $row['ten_quoc_gia']
            );
            $danhSachQuocGia[] = $quocGia;
        }
        $stmt->close();
        return $danhSachQuocGia;
    }
}