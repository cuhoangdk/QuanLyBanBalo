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
        $maChatLieu = $this->taoMaChatLieu($tenChatLieu);

        // Kiểm tra mã chất liệu đã tồn tại hay chưa
        $maChatLieuMoi = $maChatLieu;
        $stt = 1;
        while ($this->kiemTraMaChatLieuTonTai($maChatLieuMoi)) {
            $maChatLieuMoi = $maChatLieu . $stt;
            $stt++;
        }

        // Kiểm tra tên chất liệu đã tồn tại hay chưa
        if ($this->kiemTraTenChatLieuTonTai($tenChatLieu)) {
            return false; // Trả về false nếu tên chất liệu đã tồn tại
        }

        // Chèn chất liệu mới vào cơ sở dữ liệu
        $sql = "INSERT INTO tchatlieu (ma_chat_lieu, ten_chat_lieu) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $maChatLieuMoi, $tenChatLieu);

        if ($stmt->execute()) {
            return true; // Trả về true nếu thêm thành công
        } else {
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }

    // Hàm tạo mã chất liệu từ tên chất liệu
    private function taoMaChatLieu($tenChatLieu)
    {
        // Chuyển tên chất liệu thành dạng không dấu
        $tenChatLieuKhongDau = $this->removeAccents($tenChatLieu);

        // Tách tên chất liệu thành từng từ
        $tu = explode(' ', $tenChatLieuKhongDau);

        // Lấy tối đa 2 ký tự đầu tiên của mỗi từ và ghép lại
        $maCL = '';
        foreach ($tu as $t) {
            $maCL .= substr($t, 0, min(2, strlen($t)));
        }

        return strtolower($maCL); // Đảm bảo mã chất liệu là chữ in thường
    }

    // Hàm kiểm tra mã chất liệu đã tồn tại
    private function kiemTraMaChatLieuTonTai($maChatLieu)
    {
        $sql = "SELECT COUNT(*) as count FROM tchatlieu WHERE ma_chat_lieu = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $maChatLieu);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0; // Trả về true nếu mã chất liệu đã tồn tại
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

    // Hàm chuyển tiếng Việt có dấu thành không dấu
    private function removeAccents($str)
    {
        $unwanted_array = [
            'á'=>'a','à'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a',
            'ă'=>'a','ắ'=>'a','ằ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
            'â'=>'a','ấ'=>'a','ầ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',
            'é'=>'e','è'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e',
            'ê'=>'e','ế'=>'e','ề'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
            'í'=>'i','ì'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',
            'ó'=>'o','ò'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o',
            'ô'=>'o','ố'=>'o','ồ'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
            'ơ'=>'o','ớ'=>'o','ờ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',
            'ú'=>'u','ù'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u',
            'ư'=>'u','ứ'=>'u','ừ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
            'ý'=>'y','ỳ'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
            'đ'=>'d'
        ];
        return strtr(mb_strtolower($str), $unwanted_array);
    }

}
