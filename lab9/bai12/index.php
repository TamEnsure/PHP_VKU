<?php
include("functions.php");

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Khi người dùng bấm nút Rate
    if (isset($_POST['rate_submit']) && isset($_POST['rate'])) {
        setRatingInfo($id, $_POST['rate']);
    }

    $html = getProduct($id);
    include("template/view-product.php");
} else {
    $html = listProduct();
    include("template/list-products.php");
}
?>