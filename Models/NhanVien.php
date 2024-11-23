<?php 
class NhanVien {
    protected $maNhanVien;
    protected $hoNhanVien;
    protected $tenNhanVien;
    protected $ngaySinh;
    protected $gioiTinh;
    protected $soDienThoai;
    protected $diaChi;
    protected $chucVu;
    protected $anhDaiDien;
    protected $email;
    protected $cccd;
    protected $ghiChu;

    // Phương thức khởi tạo (constructor)
    public function __construct($maNhanVien, $hoNhanVien, $tenNhanVien, $ngaySinh, $gioiTinh, $soDienThoai, $diaChi, $chucVu, $anhDaiDien, $email, $cccd, $ghiChu) {
        $this->maNhanVien = $maNhanVien;
        $this->hoNhanVien = $hoNhanVien;
        $this->tenNhanVien = $tenNhanVien;
        $this->ngaySinh = $ngaySinh;
        $this->gioiTinh = $gioiTinh;
        $this->soDienThoai = $soDienThoai;
        $this->diaChi = $diaChi;
        $this->chucVu = $chucVu;
        $this->anhDaiDien = $anhDaiDien;
        $this->email = $email;
        $this->cccd = $cccd;
        $this->ghiChu = $ghiChu;
    }

    public function getMaNhanVien() {
        return $this->maNhanVien;
    }

    public function setMaNhanVien($maNhanVien) {
        $this->maNhanVien = $maNhanVien;
    }

    public function getHoNhanVien() {
        return $this->hoNhanVien;
    }

    public function setHoNhanVien($hoNhanVien) {
        $this->hoNhanVien = $hoNhanVien;
    }

    public function getTenNhanVien() {
        return $this->tenNhanVien;
    }

    public function setTenNhanVien($tenNhanVien) {
        $this->tenNhanVien = $tenNhanVien;
    }

    public function getNgaySinh() {
        return $this->ngaySinh;
    }

    public function setNgaySinh($ngaySinh) {
        $this->ngaySinh = $ngaySinh;
    }

    public function getGioiTinh() {
        return $this->gioiTinh;
    }

    public function setGioiTinh($gioiTinh) {
        $this->gioiTinh = $gioiTinh;
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

    public function getAnhDaiDien() {
        return $this->anhDaiDien;
    }

    public function setAnhDaiDien($anhDaiDien) {
        $this->anhDaiDien = $anhDaiDien;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getCccd() {
        return $this->cccd;
    }

    public function setCccd($cccd) {
        $this->cccd = $cccd;
    }

    public function getGhiChu() {
        return $this->ghiChu;
    }

    public function setGhiChu($ghiChu) {
        $this->ghiChu = $ghiChu;
    }
}
?>
