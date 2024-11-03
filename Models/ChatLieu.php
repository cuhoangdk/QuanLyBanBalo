<?php
class ChatLieu 
{
    protected $maChatLieu;
    protected $chatLieu;
    public function __construct($maChatLieu, $chatLieu)
    {
        $this->maChatLieu = $maChatLieu;
        $this->chatLieu = $chatLieu;
    }
    public function getMaChatLieu() {
        return $this->maChatLieu;
    }

    public function setMaChatLieu($maChatLieu) {
        $this->maChatLieu = $maChatLieu;
    }
    public function getChatLieu() {
        return $this->chatLieu;
    }

    public function setChatLieu($chatLieu) {
        $this->chatLieu = $chatLieu;
    }
}
?>