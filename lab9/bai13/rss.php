<?php
// 1. Khai báo Header định dạng XML (Cực kỳ quan trọng, cấm có khoảng trắng hay echo trước dòng này!)
header("Content-Type: text/xml; charset=utf-8");

// 2. Kết nối CSDL MySQL
$conn = mysqli_connect("localhost", "root", "", "rss");
if (!$conn) {
    die("Lỗi kết nối CSDL: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

// 3. Truy vấn lấy danh sách tin tức
$query = "SELECT * FROM news ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$items = "";
while ($row = mysqli_fetch_assoc($result)) {
    // Dùng CDATA hoặc htmlspecialchars để tránh xung đột ký tự đặc biệt như &, <, > trong XML
    $title = htmlspecialchars($row['title']);
    $link = htmlspecialchars($row['link']);
    $description = htmlspecialchars($row['description']);

    $items .= "
        <item>
            <title>{$title}</title>
            <link>{$link}</link>
            <description>{$description}</description>
        </item>";
}

mysqli_close($conn);

// 4. Xuất cấu trúc RSS 2.0 chuẩn XML
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0">
    <channel>
        <title>Học Web PHP Pro | Antigravity RSS</title>
        <link>http://localhost/bai13/</link>
        <description>Kênh cấp tin tức RSS tự động từ hệ thống database PHP</description>
        <language>vi-VN</language>
        <?php echo $items; ?>
    </channel>
</rss>