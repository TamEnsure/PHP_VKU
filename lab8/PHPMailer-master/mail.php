<?php
// Bài 2: Xử lý gửi email từ form index.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->SMTPDebug = 0; // Để 0 khi chạy thật, đổi thành 2 nếu muốn soi log chi tiết
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // THAY BẰNG THÔNG TIN GMAIL
        $nguoigui = 'YOUR_EMAIL@gmail.com';
        $matkhau = 'YOUR_APP_PASSWORD'; // Mật khẩu ứng dụng 16 ký tự
        $tennguoigui = 'Website Notification';

        $mail->Username = $nguoigui;
        $mail->Password = $matkhau;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // ssl
        $mail->Port = 465;

        // Người gửi & Người nhận từ Form
        $mail->setFrom($nguoigui, $tennguoigui);

        $to = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        if (!$to) {
            die("Email người nhận không hợp lệ!");
        }
        $to_name = "Quý Khách";
        $mail->addAddress($to, $to_name);

        // Tiêu đề & Nội dung
        $tieude = $_POST['tieude'] ?? 'Không có tiêu đề';
        $content = nl2br(htmlspecialchars($_POST['content'] ?? ''));

        $mail->isHTML(true);
        $mail->Subject = $tieude;

        // Định dạng thẻ card giống trong PDF hướng dẫn
        $mail->Body = '
            <div style="font-family: Arial, sans-serif; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; max-width: 500px; background-color: #fafafa;">
                <h3 style="color: #0d6efd; margin-top: 0;">Xin chào ' . htmlspecialchars($to_name) . ',</h3>
                <div style="font-size: 15px; line-height: 1.6; color: #333;">
                    ' . $content . '
                </div>
                <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
                <p style="font-size: 12px; color: #888; margin-bottom: 0;">Thư được gửi tự động qua hệ thống PHPMailer.</p>
            </div>
        ';
        $mail->AltBody = strip_tags($_POST['content'] ?? '');

        // Xử lý tệp đính kèm nếu có upload
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $tmp_path = $_FILES['file']['tmp_name'];
            $file_name = $_FILES['file']['name'];
            $uploadfile = tempnam(sys_get_temp_dir(), 'mail_attach_');

            if (move_uploaded_file($tmp_path, $uploadfile)) {
                $mail->addAttachment($uploadfile, $file_name);
            }
        }

        // Fix lỗi SSL verify trên môi trường localhost / XAMPP
        $mail->smtpConnect([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            ]
        ]);

        if ($mail->send()) {
            header("Location: index.php?status=success");
            exit;
        }
    } catch (Exception $e) {
        // Nếu lỗi thì dừng lại in lỗi ra luôn để dễ debug
        echo "<h3>Gửi mail thất bại:</h3> <p style='color:red'>" . $mail->ErrorInfo . "</p>";
        echo "<br><a href='index.php'>Quay lại</a>";
    }
} else {
    header("Location: index.php");
    exit;
}
