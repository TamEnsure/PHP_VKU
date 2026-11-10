<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Bài 8 - Tích hợp CKEditor</title>
    <!-- Nhúng thư viện CKEditor -->
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
</head>
<body>
    <form action="" method="post">
        <h3>Soạn thảo nội dung:</h3>
        <!-- Tạo textarea bình thường với name/id -->
        <textarea name="tomtat" id="tomtat" rows="10" cols="80"></textarea>

        <br />
        <input type="submit" name="ok" value="Gửi dữ liệu" />
    </form>

    <!-- Biến textarea thành CKEditor bằng JS -->
    <script type="text/javascript">
        CKEDITOR.replace('tomtat');
    </script>

    <!-- Xử lý nhận dữ liệu POST trong PHP -->
    <?php
    if (isset($_POST["ok"]) && isset($_POST["tomtat"])) {
        echo "<hr><h3>Dữ liệu nhận được:</h3>";
        // Dùng stripslashes để bỏ các ký tự escape nếu có
        echo stripslashes($_POST["tomtat"]);
    }
    ?>
</body>
</html>