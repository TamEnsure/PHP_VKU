<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 11 - Phân trang trong PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ecf0f1;
            padding: 20px;
        }
        .container {
            width: 600px;
            margin: 0 auto;
        }
        .title-box {
            background: #3498db;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .title-box a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 6px 0;
            border-bottom: 1px dashed rgba(255,255,255,0.3);
        }
        .title-box a:hover {
            color: #f1c40f;
            padding-left: 5px;
            transition: 0.2s;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 2px;
            background: #fff;
            color: #333;
            text-decoration: none;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .pagination a.active {
            background: #e74c3c;
            color: #fff;
            border-color: #c0392b;
            font-weight: bold;
        }
        .pagination a:hover:not(.active) {
            background: #ddd;
        }
    </style>
</head>
<body>

<div class="container">
<?php
// 1. Kết nối CSDL
$conn = mysqli_connect("localhost", "root", "", "phantrang");
if (!$conn) {
    die("Lỗi kết nối: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

// 2. Xác định trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// 3. Cấu hình số bài trên 1 trang & vị trí start
$limit = 10;
$start = ($page - 1) * $limit;

// 4. Đếm tổng số bài viết để tính tổng số trang
$count_query = mysqli_query($conn, "SELECT COUNT(id) as total FROM tintuc");
$count_data = mysqli_fetch_assoc($count_query);
$total_rows = $count_data['total'];

// Dùng ceil để làm tròn lên (VD: 25 bài / 10 = 2.5 -> 3 trang)
$total_page = ceil($total_rows / $limit);

// 5. Lấy dữ liệu theo LIMIT
$query = "SELECT * FROM tintuc LIMIT $start, $limit";
$result = mysqli_query($conn, $query);

// 6. Hiển thị danh sách tin
echo "<div class='title-box'>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<a href='#'>" . $row['id'] . ". " . $row['title'] . "</a>";
    }
} else {
    echo "<p style='color:white;'>Không có bài viết nào ở trang này!</p>";
}
echo "</div>";

// 7. Hiển thị thanh phân trang
echo "<div class='pagination'>";
for ($i = 1; $i <= $total_page; $i++) {
    // Đánh dấu trang hiện tại bằng class active
    $active_class = ($i == $page) ? "class='active'" : "";
    echo "<a href='phantrang.php?page=$i' $active_class>$i</a>";
}
echo "</div>";

mysqli_close($conn);
?>
</div>

</body>
</html>