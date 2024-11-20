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
        $sql = "SELECT * FROM tchatlieu WHERE trang_thai = 1";
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
    public function kiemTraChatLieuTonTai($tenChatLieu)
    {
        // Kiểm tra tên chất liệu đã tồn tại hay chưa
        $sqlCheck = "SELECT * FROM tchatlieu WHERE ten_chat_lieu = ?";
        $stmtCheck = $this->connection->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $tenChatLieu);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            return $resultCheck->fetch_assoc(); // Trả về thông tin chất liệu nếu tồn tại
        } else {
            return false; // Trả về false nếu không tồn tại
        }
    }

    public function themChatLieu($tenChatLieu)
    {
        // Tạo mã chất liệu từ tên chất liệu
        $maChatLieu = taoMaDai($tenChatLieu);
        
        // Kiểm tra tên chất liệu đã tồn tại hay chưa
        $chatLieu = $this->kiemTraChatLieuTonTai($tenChatLieu);
        
        if ($chatLieu) {
            if ($chatLieu['trang_thai'] == 0) {
                // Nếu tên chất liệu đã tồn tại và trạng thái bằng 0 thì bật trạng thái thành 1
                $sqlUpdate = "UPDATE tchatlieu SET trang_thai = 1 WHERE ten_chat_lieu = ?";
                $stmtUpdate = $this->connection->prepare($sqlUpdate);
                $stmtUpdate->bind_param("s", $tenChatLieu);
                if ($stmtUpdate->execute()) {
                    $_SESSION['success'] = 'Thêm chất liệu thành công';
                    return true; // Trả về true nếu cập nhật thành công
                } else {
                    $_SESSION['error'] = "Lỗi khi thêm chất liệu";
                    return false; // Trả về false nếu có lỗi xảy ra khi cập nhật
                }
            } else {
                $_SESSION['error'] = "Tên chất liệu đã tồn tại!";
                return false; // Trả về false nếu tên chất liệu đã tồn tại và trạng thái không bằng 0
            }
        }else{
            // Chèn chất liệu mới vào cơ sở dữ liệu
            $sql = "INSERT INTO tchatlieu (ma_chat_lieu, ten_chat_lieu, trang_thai) VALUES (?, ?, 1)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("ss", $maChatLieu, $tenChatLieu);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Thêm chất liệu thành công';
                return true; // Trả về true nếu thêm thành công
            } else {
                $_SESSION['error'] = "Lỗi khi thêm chất liệu";
                return false; // Trả về false nếu có lỗi xảy ra
            }
        }
    }
    public function suaChatLieu($maChatLieu, $tenChatLieu)
    {
        // Kiểm tra tên chất liệu đã tồn tại hay chưa
        $chatLieu = $this->kiemTraChatLieuTonTai($tenChatLieu);
        
        if ($chatLieu && $chatLieu['ma_chat_lieu'] != $maChatLieu) {
            $_SESSION['error'] = "Tên chất liệu đã tồn tại!";
            return false; // Trả về false nếu tên chất liệu đã tồn tại với mã khác
        }

        // Cập nhật chất liệu
        $sql = "UPDATE tchatlieu SET ten_chat_lieu = ? WHERE ma_chat_lieu = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $tenChatLieu, $maChatLieu);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Chỉnh sửa chất liệu thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi chỉnh sửa chất liệu";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }
    public function xoaChatLieu($maChatLieu)
    {
        // Cập nhật trạng thái chất liệu thành 0
        $sql = "UPDATE tchatlieu SET trang_thai = 0 WHERE ma_chat_lieu = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maChatLieu);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa chất liệu thành công';
            return true; // Trả về true nếu cập nhật thành công
        } else {
            $_SESSION['error'] = "Lỗi khi xóa chất liệu";
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }
}
