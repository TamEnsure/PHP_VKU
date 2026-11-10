<?php
session_start();
include('connect.php');

if (isset($_POST['login'])) {
    $name = $_POST['username'];
    $pass = $_POST['password'];

    // Truy vấn kiểm tra tài khoản
    $sql = "SELECT * FROM users WHERE Username='$name' AND Password='$pass'";
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Lưu thông tin vào SESSION
        $_SESSION['username'] = $row['Username'];
        $_SESSION['hoten'] = $row['HoTen'];

        // Nếu tích vào checkbox Remember me thì lưu Cookie sống trong 1 giờ (3600s)
        if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
            setcookie("username", $name, time() + 3600);
            setcookie("password", $pass, time() + 3600);
        }

        echo "<script>alert('Đăng nhập thành công!'); location.href='trangquantriadmin.php';</script>";
    } else {
        echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>