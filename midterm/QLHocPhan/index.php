<?php
require_once 'db.php';

// Tìm kiếm & Lọc
$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM GiangVien WHERE Fullname LIKE ? OR IDGV LIKE ? ORDER BY IDGV ASC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $conn->query("SELECT * FROM GiangVien ORDER BY IDGV ASC");
}
$giangviens = $stmt->fetchAll();

// Thống kê nghiệp vụ thực tế
$totalTeachers = count($giangviens);
$totalClasses = array_sum(array_column($giangviens, 'SumClass'));
$avgClasses = $totalTeachers > 0 ? round($totalClasses / $totalTeachers, 1) : 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Giảng Viên</title>
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

        /* Sidebar Styling */
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

        .btn-modern-primary {
            background-color: var(--primary);
            color: #ffffff;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 22px;
            border: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-modern-primary:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        }

        /* Content Card & Table */
        .content-card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            overflow: hidden;
        }

        .custom-table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .custom-table tbody tr {
            transition: background-color 0.15s ease;
        }
        .custom-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .custom-table tbody td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .gv-avatar-box {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            overflow: hidden;
            background: #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        }
        .gv-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge-id {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.82rem;
        }

        .badge-classes {
            background-color: #ecfdf5;
            color: #059669;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            transition: all 0.2s;
            text-decoration: none;
        }
        .action-btn.edit {
            background: #fffbeb;
            color: #d97706;
            border-color: #fde68a;
        }
        .action-btn.edit:hover {
            background: #d97706;
            color: #fff;
        }
        .action-btn.del {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }
        .action-btn.del:hover {
            background: #dc2626;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- SIDEBAR DỌC CHỨA DASHBOARD & STATS -->
    <aside class="sidebar">
        <!-- Brand -->
        <div class="brand-box">
            <div class="brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <div class="fw-bold fs-6 text-white" style="letter-spacing: -0.3px;">Trường ĐH CNTT & TT Việt - Hàn</div>
                <small class="text-secondary" style="font-size: 0.75rem;">Hệ thống Quản lý Học phần &bull; VKU</small>
            </div>
        </div>

        <!-- Menu điều hướng -->
        <div class="sidebar-section-title">QUẢN TRỊ VIÊN</div>
        <nav class="mb-4">
            <a href="index.php" class="sidebar-nav-item active">
                <i class="fa-solid fa-users-viewfinder"></i>
                <span>Giảng Viên</span>
            </a>
            <a href="create.php" class="sidebar-nav-item">
                <i class="fa-solid fa-user-plus"></i>
                <span>Thêm Mới</span>
            </a>
        </nav>

        <!-- DASHBOARD WIDGETS THỐNG KÊ NGHIỆP VỤ -->
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

    <!-- MAIN CONTENT BÊN PHẢI -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Danh Sách Giảng Viên</h3>
                <p class="text-muted small mb-0">Quản lý thông tin chi tiết và phân bổ lớp giảng dạy</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Search bar -->
                <form method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên hoặc mã..." value="<?= htmlspecialchars($search) ?>" style="min-width: 220px;">
                    </div>
                    <?php if ($search !== ''): ?>
                        <a href="index.php" class="btn btn-light border"><i class="fa-solid fa-xmark"></i></a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="content-card">
            <div class="table-responsive">
                <table class="table custom-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 140px;">MÃ GIẢNG VIÊN</th>
                            <th style="width: 90px;">ẢNH</th>
                            <th>HỌ VÀ TÊN</th>
                            <th style="width: 200px;">TỔNG SỐ LỚP</th>
                            <th class="text-end pe-4" style="width: 130px;">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($giangviens) > 0): ?>
                            <?php foreach ($giangviens as $gv): ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge-id"><?= htmlspecialchars($gv['IDGV']) ?></span>
                                    </td>
                                    <td>
                                        <div class="gv-avatar-box">
                                            <?php if (!empty($gv['Image']) && file_exists('uploads/' . $gv['Image'])): ?>
                                                <img src="uploads/<?= htmlspecialchars($gv['Image']) ?>" alt="Avatar">
                                            <?php else: ?>
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted fw-bold">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold fs-6 text-dark"><?= htmlspecialchars($gv['Fullname']) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge-classes">
                                            <i class="fa-solid fa-layer-group"></i>
                                            <?= (int)$gv['SumClass'] ?> lớp học
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="edit.php?id=<?= urlencode($gv['IDGV']) ?>" class="action-btn edit me-1" title="Chỉnh sửa thông tin">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="delete.php?id=<?= urlencode($gv['IDGV']) ?>" class="action-btn del" onclick="return confirm('Mày có chắc muốn xóa giảng viên <?= htmlspecialchars($gv['IDGV']) ?> không?');" title="Xóa">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa-regular fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-2 fw-medium">Không tìm thấy dữ liệu giảng viên nào!</p>
                                        <a href="create.php" class="btn btn-sm btn-outline-primary">Thêm giảng viên ngay</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>
