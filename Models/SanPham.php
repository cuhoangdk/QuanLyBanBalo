<?php
class SanPham {
    protected $maSanPham;
    protected $tenSanPham;
    protected $chatLieu;
    protected $canNang;
    protected $hangSanXuat;
    protected $nuocSanXuat;
    protected $thoiGianBaoHanh;
    protected $gioiThieuSanPham;
    protected $loaiSanPham;
    protected $doiTuong;
    protected $anh;
    protected $gia;
    protected $soLuong;
    public function __construct($maSanPham, $tenSanPham, $chatLieu, $canNang, $hangSanXuat, $nuocSanXuat, $thoiGianBaoHanh, $gioiThieuSanPham, $loaiSanPham, $doiTuong, $anh, $gia, $soLuong) {
        $this->maSanPham = $maSanPham;
        $this->tenSanPham = $tenSanPham;
        $this->chatLieu = $chatLieu;
        $this->canNang = $canNang;
        $this->hangSanXuat = $hangSanXuat;
        $this->nuocSanXuat = $nuocSanXuat;
        $this->thoiGianBaoHanh = $thoiGianBaoHanh;
        $this->gioiThieuSanPham = $gioiThieuSanPham;
        $this->loaiSanPham = $loaiSanPham;
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
        $sql = "SELECT * FROM tchatlieu WHERE ma_chat_lieu = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $this->chatLieu);
        $stmt->execute();
        $result = $stmt->get_result();
        $chatLieu = $result->fetch_assoc();
        return $chatLieu['ten_chat_lieu'];        
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

    public function getHangSanXuat($connection) {
        $sql = "SELECT * FROM thangsx WHERE ma_hang_san_xuat = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $this->hangSanXuat);
        $stmt->execute();
        $result = $stmt->get_result();
        $hangSanXuat = $result->fetch_assoc();
        return $hangSanXuat['ten_hang_san_xuat']; 
    }

    public function setHangSanXuat($hangSanXuat) {
        $this->hangSanXuat = $hangSanXuat;
    }

    public function getNuocSanXuat($connection) {
        $sql = "SELECT * FROM tquocgia WHERE ma_quoc_gia = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $this->nuocSanXuat);
        $stmt->execute();
        $result = $stmt->get_result();
        $nuocSanXuat = $result->fetch_assoc();
        return $nuocSanXuat['ten_quoc_gia']; 
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
        $sql = "SELECT * FROM tloaisp WHERE ma_loai_san_pham = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $this->loaiSanPham);
        $stmt->execute();
        $result = $stmt->get_result();
        $loaiSanPham = $result->fetch_assoc();
        return $loaiSanPham['ten_loai_san_pham'];        
    }
    public function setLoaiSanPham($loaiSanPham) {
        $this->loaiSanPham = $loaiSanPham;
    }

    public function getDoiTuong($connection) {
        $sql = "SELECT * FROM tloaidt WHERE ma_loai_doi_tuong = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $this->doiTuong);
        $stmt->execute();
        $result = $stmt->get_result();
        $doiTuong = $result->fetch_assoc();
        return $doiTuong['ten_loai_doi_tuong'];        
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
