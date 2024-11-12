<?php
/**
 * Hàm này dùng để chuyển đổi chuỗi có dấu thành chuỗi không dấu
 * @param string $str chuỗi có dấu cần chuyển đổi
 * @return string chuỗi không dấu
 */
function removeAccents($str)
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
/**
 * Summary of taoMa
 * @param mixed $tenSanPham
 * @return string
 */
function taoMa($tenSanPham)
{
    // Chuyển tên sản phẩm thành dạng không dấu
    $tenSanPhamKhongDau = removeAccents($tenSanPham);

    // Tách tên sản phẩm thành từng từ
    $tu = explode(' ', $tenSanPhamKhongDau);

    // Lấy tối đa 2 ký tự đầu tiên của mỗi từ và ghép lại
    $maSP = '';
    foreach ($tu as $t) {
        // Kiểm tra độ dài của từ, nếu từ có ít hơn 2 ký tự thì lấy tất cả ký tự của từ đó
        $maSP .= substr($t, 0, min(2, strlen($t)));
    }

    return strtolower($maSP); // Đảm bảo mã sản phẩm là chữ in thường
}
/**
 * Summary of taoMaDai
 * @param mixed $tenLoaiSanPham
 * @return string
 */
function taoMaDai($tenLoaiSanPham)
{
    // Chuyển tên loại sản phẩm thành dạng không dấu
    $tenLoaiSanPhamKhongDau = removeAccents($tenLoaiSanPham);

    // Tách tên loại sản phẩm thành từng từ và ghép lại
    $tu = explode(' ', $tenLoaiSanPhamKhongDau);
    $maLSP = implode('', $tu);

    return strtolower($maLSP); // Đảm bảo mã loại sản phẩm là chữ in thường
}
?>