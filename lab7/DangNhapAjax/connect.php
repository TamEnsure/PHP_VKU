<?php
$server = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
$user = "root";
$pass = "";
$db = "udn";
$con = mysqli_connect($server, $user, $pass, $db);
mysqli_query($con, "set names 'utf8'");
?>
