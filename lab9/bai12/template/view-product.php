<!DOCTYPE html>
<html>
<head>
    <title>Chi Tiết Sản Phẩm & Đánh Giá</title>
    <meta charset="utf-8">
    <link href="template/css.css" type="text/css" rel="stylesheet">
</head>
<body>
    <div id="container">
        <header>
            <h1><a href="index.php">COMPUTER ABC</a></h1>
        </header>
        <div id="main-wrapper">
            <div id="product-info">
                <?php echo $html; ?>
            </div>
            <div id="rating">
                <form action="" method="POST">
                    <h3>Đánh giá của bạn:</h3>
                    <label><input type="radio" name="rate" value="5" checked> 5 ⭐</label>
                    <label><input type="radio" name="rate" value="4"> 4 ⭐</label>
                    <label><input type="radio" name="rate" value="3"> 3 ⭐</label>
                    <label><input type="radio" name="rate" value="2"> 2 ⭐</label>
                    <label><input type="radio" name="rate" value="1"> 1 ⭐</label>
                    <br />
                    <input type="submit" name="rate_submit" value="Gửi đánh giá" id="submit-button">
                </form>
            </div>
            <p style="margin-top:15px;"><a href="index.php">⬅ Quay lại danh sách</a></p>
        </div>
    </div>
</body>
</html>