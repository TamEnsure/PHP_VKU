<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Bài 9 - Upload hình ảnh trong PHP</title>
</head>
<body>

    <!-- Bắt buộc có enctype="multipart/form-data" -->
    <form action="" method="post" enctype="multipart/form-data">
        <!-- Giới hạn file tối đa (ví dụ 5MB = 5.000.000 byte) -->
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        
        <label>Chọn ảnh cần upload: </label>
        <input type="file" name="ufile" id="ufile" required />
        <br /><br />
        <input type="submit" name="upload" value="Upload ngay" />
    </form>

<?php 
if (isset($_POST['upload']) && isset($_FILES['ufile'])) {
    $target_dir = "uploads/";
    $file_name = basename($_FILES['ufile']['name']);
    $target_file = $target_dir . $file_name;

    // 1. Kiểm tra lỗi upload từ server
    if ($_FILES['ufile']['error'] !== 0) {
        echo "<p style='color:red;'>Lỗi khi tải file lên! Mã lỗi: " . $_FILES['ufile']['error'] . "</p>";
    } 
    // 2. Kiểm tra định dạng (chỉ cho phép .jpg, .jpeg, .gif, .png)
    elseif (!preg_match('/\.(jpg|jpeg|gif|png)$/i', $file_name)) {
        echo "<p style='color:red;'>File không hợp lệ! Chỉ chấp nhận file ảnh (jpg, png, gif).</p>";
    } 
    // 3. Kiểm tra xem file đã tồn tại trên server chưa
    elseif (file_exists($target_file)) {
        echo "<p style='color:orange;'>File <b>$file_name</b> đã tồn tại trên server!</p>";
    } 
    // 4. Di chuyển file từ thư mục tạm (tmp_name) sang thư mục uploads
    else {
        if (move_uploaded_file($_FILES['ufile']['tmp_name'], $target_file)) {
            echo "<p style='color:green;'>Upload thành công file: <b>$file_name</b></p>";
            // Hiển thị ảnh vừa upload ra xem luôn cho ngầu
            echo "<img src='$target_file' width='200' />";
        } else {
            echo "<p style='color:red;'>Có lỗi xảy ra khi lưu file. Xem lại thư mục 'uploads' đã tạo chưa!</p>";
        }
    }
}
?>

</body>
</html>