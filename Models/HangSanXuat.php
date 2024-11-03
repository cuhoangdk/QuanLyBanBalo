<?php
class HangSanXuat
{
    protected $maHangSanXuat;
    protected $hangSanXuat;
    public function __construct($maHangSanXuat, $hangSanXuat){
        $this->maHangSanXuat = $maHangSanXuat;
        $this->hangSanXuat = $hangSanXuat;
    }
    public function getMaHangSanXuat() {
        return $this->maHangSanXuat;
    }

    public function setMaHangSanXuat($maHangSanXuat) {
        $this->maHangSanXuat = $maHangSanXuat;
    }
    public function getHangSanXuat() {
        return $this->hangSanXuat;
    }

    public function setHangSanXuat($hangSanXuat) {
        $this->hangSanXuat = $hangSanXuat;
    }
}
?>