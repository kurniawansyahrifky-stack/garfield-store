<?php
require_once '../config.php';
require_once 'auth.php';

// Penghitung data statistik dashboard
$count_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$count_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$count_faqs = $pdo->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Mini CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #090d16; color: white; }
        .sidebar { min-height: 100vh; background: #0f1626; padding: 20px; border-right: 1px solid rgba(255,255,255,0.05); }
        .nav-link { color: #94a3b8; padding: 12px; border-radius: 8px; display: block; text-decoration: none; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: rgba(0,210,255,0.1); color: #00d2ff; }
        .stat-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 25px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar">
                <h4 class="text-center py-3" style="color:#00d2ff; font-weight:bold;">CMS Admin</h4>
                <hr class="bg-secondary">
                <a href="/admin/index.php" class="nav-link active"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
                <a href="/admin/products.php" class="nav-link"><i class="fas fa-box me-2"></i> Produk (CRUD)</a>
                <a href="/admin/categories.php" class="nav-link"><i class="fas fa-tags me-2"></i> Kategori</a>
                <a href="/admin/faq.php" class="nav-link"><i class="fas fa-question-circle me-2"></i> FAQ</a>
                <a href="/admin/settings.php" class="nav-link"><i class="fas fa-sliders-h me-2"></i> Pengaturan Web</a>
                <a href="/admin/logout.php" class="nav-link text-danger mt-5"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a>
            </div>

            <div class="col-md-9 col-lg-10 p-5">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2>Selamat Datang, <?= sanitize($_SESSION['admin_user']) ?>! 👋</h2>
                    <a href="/" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-external-link-alt"></i> Lihat Toko</a>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h6 class="text-muted text-uppercase">Total Produk Dagangan</h6>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-1 fw-bold"><?= $count_products ?></span>
                                <i class="fas fa-box fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h6 class="text-muted text-uppercase">Kategori Produk</h6>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-1 fw-bold"><?= $count_categories ?></span>
                                <i class="fas fa-tags fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h6 class="text-muted text-uppercase">Pertanyaan FAQ</h6>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-1 fw-bold"><?= $count_faqs ?></span>
                                <i class="fas fa-question-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
