<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trang làm việc</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .box { background: #e0f2fe; padding: 15px; border-radius: 8px; display: inline-block; }
    </style>
</head>
<body>
    <div class="box">
        <?php
        session_start();
        if (isset($_SESSION["info"])) {
            echo "<h2>Xin chào: " . $_SESSION["info"] . "</h2>";
            echo "<p>Đăng nhập thành công qua Ajax!</p>";
            echo "<a href='login.html'>Đăng xuất</a>";
        } else {
            echo "<h2>Bạn chưa đăng nhập!</h2>";
            echo "<a href='login.html'>Quay lại trang login</a>";
        }
        ?>
    </div>
</body>
</html>
