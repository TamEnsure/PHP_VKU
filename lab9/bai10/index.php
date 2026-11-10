<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Đăng Nhập</title>
</head>
<body>
    <h2>ĐĂNG NHẬP HỆ THỐNG</h2>
    <form action="dangnhap_xuly.php" method="post">
        <table>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="checkbox" name="remember" value="on"> Nhớ mật khẩu (Remember me)
                </td>
            </tr>
            <tr>
                <td><input type="submit" name="login" value="Đăng nhập"></td>
                <td><input type="reset" value="Hủy"></td>
            </tr>
        </table>
    </form>
</body>
</html>