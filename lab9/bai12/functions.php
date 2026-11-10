<?php
$conn = mysqli_connect("localhost", "root", "", "rating");
if (!$conn) {
    die("Lỗi kết nối CSDL: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

// 1. Hàm lấy số lượt đánh giá
function getRatingInfo($id) {
    global $conn;
    $id = (int)$id;
    $query = "SELECT * FROM rating_info WHERE product_id = $id";
    $result = mysqli_query($conn, $query);
    $return = '<ul class="rating-info"><b>Đánh giá:</b>';
    
    if ($row = mysqli_fetch_assoc($result)) {
        for ($star = 5; $star >= 1; $star--) {
            $return .= "<li><b>{$star} sao:</b> {$row['rate_' . $star]} lượt</li>";
        }
    } else {
        for ($star = 5; $star >= 1; $star--) {
            $return .= "<li><b>{$star} sao:</b> 0 lượt</li>";
        }
    }
    $return .= "</ul>";
    return $return;
}

// 2. Hàm cập nhật đánh giá khi user vote
function setRatingInfo($id, $rate) {
    global $conn;
    $id = (int)$id;
    $rate = (int)$rate;
    if ($rate >= 1 && $rate <= 5) {
        $query = "UPDATE rating_info SET rate_$rate = rate_$rate + 1 WHERE product_id = $id";
        mysqli_query($conn, $query);
    }
}

// 3. Hàm xem chi tiết 1 sản phẩm
function getProduct($id) {
    global $conn;
    $id = (int)$id;
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        $rating_info = getRatingInfo($id);
        return '<h2>' . $row['title'] . '</h2>
                <div class="product-box">
                    <div class="product-img"><img src="' . $row['img_url'] . '" /></div>
                    ' . $rating_info . '
                </div>';
    }
    return "<p>Sản phẩm không tồn tại!</p>";
}

// 4. Hàm lấy danh sách tất cả sản phẩm
function listProduct() {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM products");
    $return = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $rating_info = getRatingInfo($row['id']);
        $return .= '<div class="product-info">
                        <h3><a href="index.php?id=' . $row['id'] . '">' . $row['title'] . '</a></h3>
                        <div class="product-box">
                            <div class="product-img"><img src="' . $row['img_url'] . '" /></div>
                            ' . $rating_info . '
                        </div>
                    </div>';
    }
    return $return;
}
?>