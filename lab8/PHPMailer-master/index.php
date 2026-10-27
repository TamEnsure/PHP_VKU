<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gửi Mail - Bài 2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .mail-card { max-width: 650px; margin: 40px auto; border-radius: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card mail-card shadow">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0">Form Gửi Mail Động</h4>
        </div>
        <div class="card-body p-4">
            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                     Đã gửi mail thành công!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     Gửi mail thất bại! Kiểm tra lại cấu hình tài khoản hoặc xem debug.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="mail.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold">Email người nhận:</label>
                    <input type="email" class="form-control" name="email" placeholder="example@gmail.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề thư:</label>
                    <input type="text" class="form-control" name="tieude" placeholder="Nhập tiêu đề" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung thư:</label>
                    <textarea name="content" class="form-control" rows="5" placeholder="Nhập nội dung thư tại đây" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Tệp đính kèm (tuỳ chọn):</label>
                    <input type="file" class="form-control" name="file">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"> Gửi Email</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
