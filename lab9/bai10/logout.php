<?php
session_start();
// Hủy toàn bộ Session
session_unset();
session_destroy();

// Xóa Cookie bằng cách set thời gian quá khứ
setcookie("username", "", time() - 3600);
setcookie("password", "", time() - 3600);

echo "<script>alert('Đã đăng xuất thành công!'); location.href='index.php';</script>";
?>