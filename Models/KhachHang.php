<?php 
class KhachHang {
    protected $maKhachHang;
    protected $username;
    protected $password;
    protected $tenKhachHang;
    protected $ngaySinh;
    protected $soDienThoai;
    protected $diaChi;
    protected $anhDaiDien;
    protected $ghiChu;
    public function __construct($maKhachHang, $username, $password, $tenKhachHang, $ngaySinh, $soDienThoai, $diaChi, $anhDaiDien, $ghiChu) 
    {
        $this->maKhachHang = $maKhachHang;
        $this->username = $username;
        $this->password = $password;
        $this->tenKhachHang = $tenKhachHang;
        $this->ngaySinh = $ngaySinh;
        $this->soDienThoai = $soDienThoai;
        $this->diaChi = $diaChi;
        $this->anhDaiDien = $anhDaiDien;
        $this->ghiChu = $ghiChu;
    }
    public function getMaKhachHang() {
        return $this->maKhachHang;
    }

    public function setMaKhachHang($maKhachHang) {
        $this->maKhachHang = $maKhachHang;
    }
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    public function getTenKhachHang() {
        return $this->tenKhachHang;
    }
    public function setTenKhachHang($tenKhachHang) {
        $this->tenKhachHang = $tenKhachHang;
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
    public function getAnhDaiDien() {
        return $this->anhDaiDien;
    }
    public function setAnhDaiDien($anhDaiDien) {
        $this->anhDaiDien = $anhDaiDien;
    }
    public function getGhiChu() {
        return $this->ghiChu;
    }
    public function setGhiChu($ghiChu) {
        $this->ghiChu = $ghiChu;
    }
}
?>
