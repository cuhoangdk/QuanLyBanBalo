<?php 
class NhanVien {
    protected $maNhanVien;
    protected $hoNhanVien;
    protected $tenNhanVien;
    protected $ngaySinh;
    protected $soDienThoai;
    protected $diaChi;
    protected $chucVu;
    protected $ghiChu;
     // Phương thức khởi tạo (constructor)
     public function __construct($maNhanVien,$hoNhanVien, $tenNhanVien, $ngaySinh, $soDienThoai, $diaChi, $chucVu, $ghiChu) {
        $this->maNhanVien = $maNhanVien;
        $this->hoNhanVien = $hoNhanVien;
        $this->tenNhanVien = $tenNhanVien;
        $this->ngaySinh = $ngaySinh;
        $this->soDienThoai = $soDienThoai;
        $this->diaChi = $diaChi;
        $this->chucVu = $chucVu;
        $this->ghiChu = $ghiChu;
    }
    public function getMaNhanVien() {
        return $this->maNhanVien;
    }
    public function setMaNhanVien($maNhanVien) {
        $this->maNhanVien = $maNhanVien;
    }
    public function setHoNhanVien($hoNhanVien) {
        $this->hoNhanVien = $hoNhanVien;
    }
    public function getHoNhanVien() {
        return $this->hoNhanVien;
    }
    public function setTenNhanVien($tenNhanVien) {
        $this->tenNhanVien = $tenNhanVien;
    }
    public function getTenNhanVien() {
        return $this->tenNhanVien;
    }
    public function getNgaySinh() {
        return $this->ngaySinh;
    }
    public function setNgaySinh($ngaySinh) {
        $this->ngaySinh = $ngaySinh;
    }
    public function getSoDienThoai() {
        return $this->soDienThoai;
    }
    public function setSoDienThoai($soDienThoai) {
        $this->soDienThoai = $soDienThoai;
    }
    public function getDiaChi() {
        return $this->diaChi;
    }
    public function setDiaChi($diaChi) {
        $this->diaChi = $diaChi;
    }
    public function getChucVu() {
        return $this->chucVu;
    }
    public function setChucVu($chucVu) {
        $this->chucVu = $chucVu;
    }
    public function getGhiChu() {
        return $this->ghiChu;
    }
    public function setGhiChu($ghiChu) {
        $this->ghiChu = $ghiChu;
    }
}
?>
