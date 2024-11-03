<?php
class QuocGia
{
    protected $maQuocGia;
    protected $quocGia;
    public function __construct($maQuocGia = null, $quocGia = null) {
        $this->maQuocGia = $maQuocGia;
        $this->quocGia = $quocGia;
    }
    public function getMaQuocGia() {
        return $this->maQuocGia;
    }
    public function setMaQuocGia($maQuocGia) {
        $this->maQuocGia = $maQuocGia;
    }
    public function getQuocGia() {
        return $this->quocGia;
    }
    public function setQuocGia($quocGia) {
        $this->quocGia = $quocGia;
    }
}
?>