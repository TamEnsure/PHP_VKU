<?php
require_once("config.php");

// 1. Lấy tên câu hỏi
$s = "SELECT MoTa FROM binhchon WHERE idBC = 1";
$kq = mysqli_query($config, $s);
$d = mysqli_fetch_assoc($kq);
$mota = $d ? $d["MoTa"] : "Câu hỏi bình chọn";

// 2. Tính tổng số lượt bình chọn từ bảng phuongan
$s2 = "SELECT SUM(SoLanChon) AS tongsolanchon FROM phuongan WHERE idBC = 1";
$kq2 = mysqli_query($config, $s2);
$d2 = mysqli_fetch_assoc($kq2);
$tongsobinhchon = ($d2 && $d2["tongsolanchon"] > 0) ? $d2["tongsolanchon"] : 1;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả bình chọn</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 8px 12px; }
        th { background-color: #66CCFF; text-align: center; font-size: 16px; }
        .bar-container { width: 150px; background: #eee; height: 16px; display: inline-block; }
        .bar-red { background: red; height: 16px; display: inline-block; float: left; }
    </style>
</head>
<body>

    <table width="600">
        <tr>
            <th colspan="3"><?php echo $mota; ?></th>
        </tr>
        <?php
        // 3. Lấy từng phương án để tính % và vẽ thanh màu đỏ
        $s3 = "SELECT * FROM phuongan WHERE idBC = 1";
        $kq3 = mysqli_query($config, $s3);
        while ($d3 = mysqli_fetch_assoc($kq3)) {
            $solan = $d3["SoLanChon"];
            $phantram = ($solan / $tongsobinhchon) * 100;
            // Độ rộng thanh đỏ tối đa 150px
            $rong = ($phantram / 100) * 150;
        ?>
        <tr>
            <td width="250"><?php echo $d3["MoTa"]; ?></td>
            <td width="200">
                <div class="bar-container">
                    <div class="bar-red" style="width: <?php echo $rong; ?>px;"></div>
                </div>
                <span style="font-size: 13px; margin-left: 5px;"><?php echo round($phantram, 2); ?>%</span>
            </td>
            <td width="150" align="center">Số lần chọn: <b><?php echo $solan; ?></b></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="3" align="right"><b>Tổng số lần bình chọn: <?php echo $tongsobinhchon; ?></b></td>
        </tr>
    </table>

    <p><a href="binhchon.php">⬅ Tiếp tục bình chọn</a></p>

</body>
</html>