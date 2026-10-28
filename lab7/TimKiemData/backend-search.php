<?php
/* Kết nối đến MySQL server */
$link = mysqli_connect("localhost", "root", "", "udn");

// Kiểm tra kết nối
if ($link === false) {
    die("ERROR: Không thể kết nối. " . mysqli_connect_error());
}

mysqli_query($link, "set names 'utf8'");

if (isset($_REQUEST["term"])) {
    // Chuẩn bị câu lệnh SQL SELECT chống SQL Injection
    $sql = "SELECT * FROM hocphan WHERE tenhp LIKE ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Liên kết biến đến câu lệnh đã chuẩn bị như là tham số
        mysqli_stmt_bind_param($stmt, "s", $param_term);

        // Thiết lập tham số (tìm kiếm từ đầu từ hoặc chứa trong chuỗi)
        $param_term = '%' . $_REQUEST["term"] . '%';

        // Thực thi câu lệnh
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            // Kiểm tra số lượng row trả về
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<p>" . htmlspecialchars($row["tenhp"]) . "</p>";
                }
            } else {
                echo "<p>Không tìm thấy kết quả nào</p>";
            }
        } else {
            echo "ERROR: Không thể thực thi câu lệnh $sql. " . mysqli_error($link);
        }

        // Đóng câu lệnh
        mysqli_stmt_close($stmt);
    }
}

// Đóng kết nối
mysqli_close($link);
?>
