<?php
ob_start();
require_once("config.php");

if (isset($_GET["idPA"])) {
    $idPA = (int)$_GET["idPA"];

    // 1. Tăng số lần chọn ở bảng bình chọn tổng
    $qr1 = "UPDATE binhchon SET SoLanChon = SoLanChon + 1 WHERE idBC = 1";
    // 2. Tăng số lần chọn ở phương án tương ứng
    $qr2 = "UPDATE phuongan SET SoLanChon = SoLanChon + 1 WHERE idBC = 1 AND idPA = $idPA";

    if (mysqli_query($config, $qr1) && mysqli_query($config, $qr2)) {
        // Vote xong thì đá sang trang hiển thị kết quả
        header('Location: ketquabinhchon.php');
        exit();
    } else {
        echo "Lỗi khi cập nhật bình chọn: " . mysqli_error($config);
    }
} else {
    header('Location: binhchon.php');
}
?>