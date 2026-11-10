<?php
session_start();

// 1. Sinh chuỗi ngẫu nhiên 6 ký tự
$random_str = substr(md5(time()), 0, 6);

// 2. Lưu vào Session để tí nữa trang index.php đối chiếu
$_SESSION['captcha'] = $random_str;

// 3. Tạo bức ảnh có kích thước 150x45 px
$img = imagecreate(150, 45);

// 4. Đổ màu: màu đầu tiên khai báo sẽ là màu nền (Đen), màu chữ (Trắng)
$bg_color = imagecolorallocate($img, 0, 0, 0);       // Nền đen
$text_color = imagecolorallocate($img, 255, 255, 255); // Chữ trắng

// 5. Vẽ chữ lên ảnh: font cỡ 5, tọa độ x=45, y=15
imagestring($img, 5, 45, 15, $random_str, $text_color);

// 6. Báo cho trình duyệt biết đây là file ẢNH PNG chứ không phải file HTML
header("Content-Type: image/png");

// Xuất ảnh và giải phóng bộ nhớ
imagepng($img);
imagedestroy($img);
?>