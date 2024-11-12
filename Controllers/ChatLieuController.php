<?php
include_once '../Models/ChatLieu.php';
include_once '../Utils/utils.php';
class ChatLieuController{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    /**
     * Lấy danh sách chất liệu
     * @return ChatLieu[]
     */
    public function layDanhSachChatLieu()
    {
        // Câu lệnh sql lấy danh sách chất liệu
        $sql = "SELECT * FROM tchatlieu";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        // Lấy danh sách chất liệu
        $danhSachChatLieu = array();
        while ($row = $result->fetch_assoc()) {
            $chatLieu = new ChatLieu(
                $row['ma_chat_lieu'], $row['ten_chat_lieu']
            );
            $danhSachChatLieu[] = $chatLieu;
        }
        $stmt->close();
        return $danhSachChatLieu;
    }
    public function themChatLieu($tenChatLieu)
    {
        // Tạo mã chất liệu từ tên chất liệu
        $maChatLieu = taoMaDai($tenChatLieu);
        
        // Kiểm tra tên chất liệu đã tồn tại hay chưa
        if ($this->kiemTraTenChatLieuTonTai($tenChatLieu)) {
            return false; // Trả về false nếu tên chất liệu đã tồn tại
        }

        // Chèn chất liệu mới vào cơ sở dữ liệu
        $sql = "INSERT INTO tchatlieu (ma_chat_lieu, ten_chat_lieu) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $maChatLieu, $tenChatLieu);

        if ($stmt->execute()) {
            return true; // Trả về true nếu thêm thành công
        } else {
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }

    // Hàm kiểm tra tên chất liệu đã tồn tại
    public function kiemTraTenChatLieuTonTai($tenChatLieu)
    {
        $sql = "SELECT COUNT(*) as count FROM tchatlieu WHERE ten_chat_lieu = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tenChatLieu);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0; // Trả về true nếu tên chất liệu đã tồn tại
    }
}
