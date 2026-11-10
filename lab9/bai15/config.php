<?php
$config = mysqli_connect('localhost', 'root', '', 'review');
if (mysqli_connect_errno()) {
    die("Lỗi kết nối CSDL: " . mysqli_connect_error());
}
mysqli_set_charset($config, 'utf8');
?>