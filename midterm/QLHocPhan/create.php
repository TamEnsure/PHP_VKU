<?php
require_once 'db.php';

$errors = [];

// Thống kê
$stmtCount = $conn->query("SELECT COUNT(*) AS total, COALESCE(SUM(SumClass), 0) AS classes FROM GiangVien");
$stats = $stmtCount->fetch();
$totalTeachers = $stats['total'];
$totalClasses = $stats['classes'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idgv = trim($_POST['IDGV'] ?? '');
    $fullname = trim($_POST['Fullname'] ?? '');
    $sumclass = trim($_POST['SumClass'] ?? '0');
    $imageName = null;

    if ($idgv === '') {
        $errors[] = 'Mã giảng viên (IDGV) không được để trống!';
    } else {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM GiangVien WHERE IDGV = ?");
        $checkStmt->execute([$idgv]);
        if ($checkStmt->fetchColumn() > 0) {
            $errors[] = "Mã giảng viên '$idgv' đã tồn tại trong hệ thống!";
        }
    }

    if ($fullname === '') {
        $errors[] = 'Họ và tên giảng viên không được để trống!';
    }

    if (!is_numeric($sumclass) || $sumclass < 0) {
        $errors[] = 'Tổng số lớp phải là số nguyên không âm!';
    }

    if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['Image']['tmp_name'];
        $origName = basename($_FILES['Image']['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $imageName = time() . '_' . uniqid() . '.' . $ext;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            move_uploaded_file($tmpName, 'uploads/' . $imageName);
        } else {
            $errors[] = 'File tải lên phải là hình ảnh (jpg, png, gif, webp)!';
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO GiangVien (IDGV, Fullname, Image, SumClass) VALUES (?, ?, ?, ?)");
        $stmt->execute([$idgv, $fullname, $imageName, (int)$sumclass]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Mới Giảng Viên</title>
    <!-- Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --surface: #ffffff;
            --bg: #f8fafc;
            --sidebar-bg: #0f172a;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: #0f172a;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling đồng bộ index */
        .sidebar {
            width: 290px;
            background: var(--sidebar-bg);
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            padding: 24px 20px;
            border-right: 1px solid #1e293b;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 24px;
            border-bottom: 1px solid #1e293b;
            margin-bottom: 24px;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        .sidebar-section-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .metric-widget {
            background: #1e293b;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 14px;
            border: 1px solid #334155;
            transition: all 0.2s ease;
        }
        .metric-widget:hover {
            border-color: #475569;
            transform: translateY(-2px);
        }

        .metric-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.92rem;
            margin-bottom: 6px;
            transition: all 0.2s;
        }
        .sidebar-nav-item:hover, .sidebar-nav-item.active {
            background: #1e293b;
            color: #ffffff;
        }
        .sidebar-nav-item.active {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 32px 36px;
            background-color: var(--bg);
            overflow-y: auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        /* Card Form */
        .content-card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            padding: 32px 36px;
        }

        .form-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }

        .btn-modern-primary {
            background-color: var(--primary);
            color: #ffffff;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 24px;
            border: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s;
        }
        .btn-modern-primary:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-back {
            background-color: #f1f5f9;
            color: #475569;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 22px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }
    </style>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand-box">
            <div class="brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <div class="fw-bold fs-6 text-white" style="letter-spacing: -0.3px;">Trường ĐH CNTT & TT Việt - Hàn</div>
                <small class="text-secondary" style="font-size: 0.75rem;">Hệ thống Quản lý Học phần &bull; VKU</small>
            </div>
        </div>

        <div class="sidebar-section-title">QUẢN TRỊ VIÊN</div>
        <nav class="mb-4">
            <a href="index.php" class="sidebar-nav-item">
                <i class="fa-solid fa-users-viewfinder"></i>
                <span>Giảng Viên</span>
            </a>
            <a href="create.php" class="sidebar-nav-item active">
                <i class="fa-solid fa-user-plus"></i>
                <span>Thêm Mới</span>
            </a>
        </nav>

        <div class="sidebar-section-title">THỐNG KÊ ĐÀO TẠO</div>
        <div class="metric-widget">
            <div class="metric-icon" style="background: rgba(99, 102, 241, 0.15); color: #818cf8;">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
                <div class="text-secondary small" style="font-size: 0.78rem;">Tổng Giảng Viên</div>
                <div class="h5 mb-0 fw-bold text-white"><?= $totalTeachers ?> <span class="fs-6 fw-normal text-secondary">người</span></div>
            </div>
        </div>

        <div class="metric-widget">
            <div class="metric-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <div class="text-secondary small" style="font-size: 0.78rem;">Tổng Lớp Giảng Dạy</div>
                <div class="h5 mb-0 fw-bold text-white"><?= $totalClasses ?> <span class="fs-6 fw-normal text-secondary">lớp</span></div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT ĐỒNG BỘ -->
    <main class="main-content">
        <div class="top-bar">
            <div>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Thêm Giảng Viên Mới</h3>
                <p class="text-muted small mb-0">Thêm mới thông tin giảng viên vào cơ sở dữ liệu</p>
            </div>
        </div>

        <div class="content-card">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger border-0 rounded-3 shadow-sm py-2 px-3 mb-4">
                    <ul class="mb-0 small ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">MÃ GIẢNG VIÊN (IDGV) <span class="text-danger">*</span></label>
                        <input type="text" name="IDGV" class="form-control" placeholder="Nhập Mã Giảng viên" required value="<?= htmlspecialchars($_POST['IDGV'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">HỌ VÀ TÊN (FULLNAME) <span class="text-danger">*</span></label>
                        <input type="text" name="Fullname" class="form-control" placeholder="Nhập Họ tên Giảng viên" required value="<?= htmlspecialchars($_POST['Fullname'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">TỔNG SỐ LỚP (SUMCLASS) <span class="text-danger">*</span></label>
                        <input type="number" min="0" name="SumClass" class="form-control" placeholder="0" required value="<?= htmlspecialchars($_POST['SumClass'] ?? '0') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">HÌNH ẢNH (IMAGE)</label>
                        <input type="file" name="Image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-3 pt-4 border-top mt-4">
                    <button type="reset" class="btn btn-back">
                        <i class="fa-solid fa-rotate-left me-1"></i> Xóa Hết Dữ Liệu
                    </button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fa-solid fa-plus me-1"></i> Lưu Dữ Liệu
                    </button>
</div>
            </form>
        </div>
    </main>
</div>

</body>
</html>
