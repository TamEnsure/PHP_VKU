<?php
// Bài 1: Gửi email cố định bằng PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Cấu hình Debug: 0 = tắt, 2 = bật chi tiết để soi lỗi
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->CharSet = "utf-8";
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // THAY BẰNG GMAIL VÀ APP PASSWORD (16 KÝ TỰ)
    $nguoigui = 'sender@gmail.com';
    $matkhau = 'kbce ecdn vtzi lben'; // Mật khẩu ứng dụng 16 ký tự
    $tennguoigui = 'Test PHPMailer';

    $mail->Username = $nguoigui;
    $mail->Password = $matkhau;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // ssl
    $mail->Port = 465;

    // Người gửi
    $mail->setFrom($nguoigui, $tennguoigui);

    // Người nhận
    $to = 'receiver@gmail.com'; // Thay bằng email người nhận
    $to_name = 'Người Nhận Test';
    $mail->addAddress($to, $to_name);

    // Nội dung
    $mail->isHTML(true);
    $mail->Subject = 'Gửi thư test Bài 1 từ PHP';
    $mail->Body    = '<b>Henlo!</b><br>Mail này được gửi tự động từ code PHP PHPMailer.';
    $mail->AltBody = 'Mail này được gửi tự động từ code PHP PHPMailer.';

    // Fix lỗi SSL trên localhost/XAMPP nếu có
    $mail->smtpConnect([
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
            "allow_self_signed" => true
        ]
    ]);

    $mail->send();
    echo "<h2 style='color:green'> Đã gửi mail test thành công!</h2>";
} catch (Exception $e) {
    echo "<h2 style='color:red'> Mail không gửi được. Lỗi: {$mail->ErrorInfo}</h2>";
}
