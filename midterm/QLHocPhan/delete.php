<?php
require_once 'db.php';

$id = $_GET['id'] ?? null;

// Xử lý xóa
if ($id) {
    // Tìm file ảnh để xóa khỏi thư mục uploads
    $stmt = $conn->prepare("SELECT Image FROM GiangVien WHERE IDGV = ?");
    $stmt->execute([$id]);
    $gv = $stmt->fetch();

    if ($gv && !empty($gv['Image']) && file_exists('uploads/' . $gv['Image'])) {
        @unlink('uploads/' . $gv['Image']);
    }

    // Xóa record trong CSDL
    $delStmt = $conn->prepare("DELETE FROM GiangVien WHERE IDGV = ?");
    $delStmt->execute([$id]);
}

// Chuyển hướng lại trang danh sách
header('Location: index.php');
exit;
