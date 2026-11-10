<?php
session_start();
$message = "";

// Xử lý khi bấm nút Submit
if (isset($_POST['submit'])) {
    $input = isset($_POST['input']) ? trim($_POST['input']) : "";
    
    // So sánh chuỗi user nhập với chuỗi lưu trong Session
    if (isset($_SESSION['captcha']) && $input === $_SESSION['captcha']) {
        $message = "<span style='color: green; font-weight: bold;'>✔ Chính xác! Captcha hợp lệ.</span>";
    } else {
        $message = "<span style='color: red; font-weight: bold;'>✖ Sai mã Captcha!</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Bài 14 - CAPTCHA PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ecf0f1;
            padding-top: 50px;
        }
        .captcha-box {
            width: 450px;
            margin: 0 auto;
            border: 1px solid #bdc3c7;
            border-radius: 8px;
            background: #fff;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .input-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 0;
        }
        .input-row input[type="text"] {
            height: 40px;
            width: 180px;
            padding: 0 10px;
            font-size: 16px;
            border: 1px solid #95a5a6;
            border-radius: 4px;
        }
        .input-row img {
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"] {
            padding: 10px 25px;
            background: #2980b9;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #3498db;
        }
        #message {
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="captcha-box">
        <h2>CAPTCHA TEST</h2>
        <p>Vui lòng nhập mã bảo vệ (phân biệt hoa/thường):</p>
        
        <form action="" method="POST">
            <div class="input-row">
                <input type="text" name="input" autocomplete="off" required placeholder="Nhập 6 ký tự..." />
                <!-- Nhúng trực tiếp file captcha.php vào thẻ img -->
                <img src="captcha.php" alt="Mã Captcha" title="Bấm vào để đổi mã khác" onclick="this.src='captcha.php?'+Math.random();" />
            </div>
            
            <input type="submit" name="submit" value="Kiểm tra" />
            
            <div id="message">
                <?php echo $message; ?>
            </div>
        </form>
    </div>

</body>
</html>