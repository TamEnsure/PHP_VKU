<?php
session_start();

// Kiểm tra xem có Session hoặc Cookie không
$user = null;
if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
} elseif (isset($_COOKIE['username'])) {
    $user = $_COOKIE['username'];
}

// Nếu không có cả hai -> Đá đít về trang login ngay
if (!$user) {
    echo "<script>alert('Bạn chưa đăng nhập!'); location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trang Quản Trị</title>
</head>
<body>
    <h1>CHÀO MỪNG ĐẾN TRANG QUẢN TRỊ ADMIN 🗿</h1>
    <p>Xin chào sếp: <b><?php echo $user; ?></b></p>
    <p><a href="logout.php">Đăng xuất (Logout)</a></p>
</body>
</html>