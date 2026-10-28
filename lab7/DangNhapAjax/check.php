<meta charset=utf-8>
<?php
session_start();
include("connect.php");

$us = isset($_GET["user"]) ? $_GET["user"] : "";
$pw = isset($_GET["pass"]) ? $_GET["pass"] : "";

$sql = "SELECT * FROM sinhvien WHERE masv='{$us}' AND matkhau='{$pw}'";
mysqli_query($con, "SET NAMES 'utf8'");
$rs = mysqli_query($con, $sql);

if ($rs && mysqli_num_rows($rs) > 0) {
    $row = mysqli_fetch_array($rs);

    $_SESSION["code"] = $row['masv'];
    $_SESSION["info"] = $us . " - " . $row["hoten"] . " - " . $row["lop"];
    echo $us . " - " . $row['hoten'] . "&nbsp;|&nbsp;<a href='work.php'>Next</a>";
    mysqli_free_result($rs);
} else {
    echo "Bạn nhập thông tin sai!";
}

mysqli_close($con);
?>
