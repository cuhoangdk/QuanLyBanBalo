<?php
class SanPham {
    protected $maSanPham;
    protected $tenSanPham;
    protected $chatLieu;
    protected $canNang;
    protected $doNoi;
    protected $hangSanXuat;
    protected $nuocSanXuat;
    protected $thoiGianBaoHanh;
    protected $gioiThieuSanPham;
    protected $loaiSanPham;
    protected $doiTuong;
    protected $anh;
    protected $gia;
    protected $soLuong;
    public function __construct($maSanPham, $tenSanPham, $chatLieu, $canNang, $doNoi, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieuSanPham, $loaiSanPham, $doiTuong, $anh, $gia, $soLuong) {
        $this->maSanPham = $maSanPham;
        $this->tenSanPham = $tenSanPham;
        $this->chatLieu = $chatLieu;
        $this->canNang = $canNang;
        $this->doNoi = $doNoi;
        $this->hangSanXuat = $hangSanXuat;
        $this->nuocSanXuat = $nuocSanXuat;
        $this->thoiGianBaoHanh = $thoiGianBaoHanh;
        $this->gioiThieuSanPham = $gioiThieuSanPham;
        $this->loaiSanPham
 = $loaiSanPham
;
        $this->doiTuong = $doiTuong;
        $this->anh = $anh;
        $this->gia = $gia;
        $this->soLuong = $soLuong;
    }

    public function getMaSanPham() {
        return $this->maSanPham;
    }

    public function setMaSanPham($maSanPham) {
        $this->maSanPham = $maSanPham;
    }

    public function getTenSanPham() {
        return $this->tenSanPham;
    }

    public function setTenSanPham($tenSanPham) {
        $this->tenSanPham = $tenSanPham;
    }

    public function getChatLieu($connection) {
        $sql = "SELECT * FROM tchatlieu WHERE id = $this->chatLieu";
        $result = $connection->query($sql);
        $chatLieu = $result->fetch_assoc();
        return $chatLieu['ChatLieu'];
    }

    public function setChatLieu($chatLieu) {
        $this->chatLieu = $chatLieu;
    }

    public function getCanNang() {
        return $this->canNang;
    }

    public function setCanNang($canNang) {
        $this->canNang = $canNang;
    }

    public function getDoNoi() {
        return $this->doNoi;
    }

    public function setDoNoi($doNoi) {
        $this->doNoi = $doNoi;
    }

    public function getHangSanXuat($connection) {
        $sql = "SELECT * FROM thangsx WHERE id = $this->hangSanXuat";
        $result = $connection->query($sql);
        $hangSanXuat = $result->fetch_assoc();
        return $hangSanXuat['HangSX'];
    }

    public function setHangSanXuat($hangSanXuat) {
        $this->hangSanXuat = $hangSanXuat;
    }

    public function getNuocSanXuat($connection) {
        $sql = "SELECT * FROM quocgia WHERE id = $this->nuocSanXuat";
        $result = $connection->query($sql);
        $nuocSanXuat = $result->fetch_assoc();
        return $nuocSanXuat['TenNuoc'];    
    }

    public function setNuocSanXuat($nuocSanXuat) {
        $this->nuocSanXuat = $nuocSanXuat;
    }

    public function getThoiGianBaoHanh() {
        return $this->thoiGianBaoHanh;
    }

    public function setThoiGianBaoHanh($thoiGianBaoHanh) {
        $this->thoiGianBaoHanh = $thoiGianBaoHanh;
    }

    public function getGioiThieuSanPham() {
        return $this->gioiThieuSanPham;
    }

    public function setGioiThieuSanPham($gioiThieuSanPham) {
        $this->gioiThieuSanPham = $gioiThieuSanPham;
    }

    public function getLoaiSanPham($connection) {
        $sql = "SELECT * FROM tloaisp WHERE id = $this->loaiSanPham";
        $result = $connection->query($sql);
        $loaiSanPham= $result->fetch_assoc();
        return $loaiSanPham['TenLoaiSanPham'];        
    }

    public function setLoaiSanPham($loaiSanPham) {
        $this->loaiSanPham = $loaiSanPham;
    }

    public function getDoiTuong($connection) {
        $sql = "SELECT * FROM tloaidt WHERE id = $this->doiTuong";
        $result = $connection->query($sql);
        $doiTuong = $result->fetch_assoc();
        return $doiTuong['TenLoaiDoiTuong'];        
    }

    public function setDoiTuong($doiTuong) {
        $this->doiTuong = $doiTuong;
    }

    public function getAnh() {
        return $this->anh;
    }

    public function setAnh($anh) {
        $this->anh = $anh;
    }

    public function getGia() {
        return $this->gia;
    }

    public function setGia($gia) {
        $this->gia = $gia;
    }

    public function getSoLuong() {
        return $this->soLuong;
    }

    public function setSoLuong($soLuong) {
        $this->soLuong = $soLuong;
    }
}
?>
