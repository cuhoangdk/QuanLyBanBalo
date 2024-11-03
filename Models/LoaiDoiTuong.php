<?php
class LoaiDoiTuong 
{
    protected $maDoiTuong;
    protected $doiTuong;
    public function __construct($maDoiTuong, $doiTuong) {
        $this->maDoiTuong = $maDoiTuong;
        $this->doiTuong = $doiTuong;
    }
    public function getMaDoiTuong() {
        return $this->maDoiTuong;
    }

    public function setMaDoiTuong($maDoiTuong) {
        $this->maDoiTuong = $maDoiTuong;
    }

    public function getDoiTuong() {
        return $this->doiTuong;
    }

    public function setDoiTuong($doiTuong) {
        $this->doiTuong = $doiTuong;
    }
}
?>