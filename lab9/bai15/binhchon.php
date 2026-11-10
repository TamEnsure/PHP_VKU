<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 15 - Bình Chọn</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .poll-box { width: 450px; border: 1px solid #ccc; padding: 15px; border-radius: 5px; }
        .poll-box h3 { margin-top: 0; }
        .option { margin-bottom: 8px; }
    </style>
</head>
<body>

<div class="poll-box">
    <h3>BÌNH CHỌN</h3>
    <form method="GET" action="xulybinhchon.php">
        <p><b>Bạn nghĩ sao về chất lượng của trang web?</b></p>
        
        <?php
        require_once("config.php");
        // Lấy các phương án thuộc câu hỏi idBC = 1
        $s = "SELECT * FROM phuongan WHERE idBC = 1";
        $kq = mysqli_query($config, $s);
        $i = 0;
        while ($row = mysqli_fetch_array($kq)) {
        ?>
            <div class="option">
                <label>
                    <input type="radio" name="idPA" value="<?php echo $row['idPA']; ?>" <?php if ($i == 0) { echo "checked"; $i++; } ?> />
                    <?php echo $row['MoTa']; ?>
                </label>
            </div>
        <?php } ?>

        <br />
        <input type="submit" name="Submit" value="Xem kết quả & Bình chọn" />
    </form>
</div>

</body>
</html>