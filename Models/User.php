<?php 
class User {
    protected $maUser;
    protected $username;
    protected $password;
    protected $quyen;

    public function __construct($maUser, $username, $password, $quyen) {
        $this->maUser = $maUser;
        $this->username = $username;
        $this->password = $password;
        $this->quyen = $quyen;
    }

    public function getMaUser() {
        return $this->maUser;
    }

    public function setMaUser($maUser) {
        $this->maUser = $maUser;
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
    public function getQuyen() {
        return $this->quyen;
    }

    public function setQuyen($quyen) {
        $this->quyen = $quyen;
    }
}
?>
