<?php 
class LoaiSanPham{
    protected $maLoaiSanPham;
    protected $loaiSanPham;
    public function __construct($maLoaiSanPham, $loaiSanPham){
        $this->maLoaiSanPham = $maLoaiSanPham;
        $this->loaiSanPham = $loaiSanPham;
    }
    public function getMaLoaiSanPham() {
        return $this->maLoaiSanPham;
    }

    public function setMaLoaiSanPham($maLoaiSanPham) {
        $this->maLoaiSanPham = $maLoaiSanPham;
    }
    public function getLoaiSanPham() {
        return $this->loaiSanPham;
    }
    public function setLoaiSanPham($loaiSanPham) {
        $this->loaiSanPham = $loaiSanPham;
    }

}
?>