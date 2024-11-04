<?php
include_once '../Models/ChatLieu.php';
class ChatLieuController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function layDanhSachChatLieu()
    {
        $sql = "SELECT * FROM tchatlieu";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $danhSachChatLieu = array();
        while ($row = $result->fetch_assoc()) {
            $chatLieu = new ChatLieu(
                $row['MaChatLieu'], $row['ChatLieu']
            );
            $danhSachChatLieu[] = $chatLieu;
        }
        $stmt->close();
        return $danhSachChatLieu;
    }
}
