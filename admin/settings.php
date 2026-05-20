<?php
require_once '../config.php';
require_once 'auth.php';

$success = false;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $site_name = sanitize($_POST['site_name']);
    $hero_title = sanitize($_POST['hero_title']);
    $hero_subtitle = sanitize($_POST['hero_subtitle']);
    $wa_number = sanitize($_POST['wa_number']);
    $theme_color = sanitize($_POST['theme_color']);

    // Proses Ganti Musik Latar (.mp3) Aman
    $music_sql = "";
    $music_param = [];
    if(isset($_FILES['bg_music']) && $_FILES['bg_music']['error'] === UPLOAD_ERR_OK) {
        $m_tmp = $_FILES['bg_music']['tmp_name'];
        $m_name = $_FILES['bg_music']['name'];
        $m_ext = strtolower(pathinfo($m_name, PATHINFO_EXTENSION));

        if($m_ext === 'mp3') {
            $music_filename = "music_" . time() . ".mp3";
            move_uploaded_file($m_tmp, "../uploads/" . $music_filename);
            $music_sql = ", bg_music = ?";
            $music_param[] = $music_filename;
        }
    }

    $base_sql = "UPDATE settings SET site_name = ?, hero_title = ?, hero_subtitle = ?, wa_number = ?, theme_color = ?" . $music_sql . " WHERE id = 1";
    
    $params = array_merge([$site_name, $hero_title, $hero_subtitle, $wa_number, $theme_color], $music_param);
    
    $stmt = $pdo->prepare($base_sql);
    $stmt->execute($params);
    
    header("Location: /admin/settings.php?success=1");
    exit;
}

// Reload data fresh
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$current_settings = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Konfigurasi CMS Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { background:#090d16; color:white; } .sidebar { min-height: 100vh; background: #0f1626; padding: 20px; } </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar p-4">
                <h4 style="color:#00d2ff;">CMS Admin</h4><hr>
                <a href="/admin/index.php" class="nav-link text-white-50 mb-2"><i class="fas fa-chart-pie"></i> Dashboard</a>
                <a href="/admin/products.php" class="nav-link text-white-50 mb-2"><i class="fas fa-box"></i> Produk</a>
                <a href="/admin/categories.php" class="nav-link text-white-50 mb-2"><i class="fas fa-tags"></i> Kategori</a>
                <a href="/admin/faq.php" class="nav-link text-white-50 mb-2"><i class="fas fa-question-circle"></i> FAQ</a>
                <a href="/admin/settings.php" class="nav-link text-info fw-bold mb-2"><i class="fas fa-sliders-h"></i> Pengaturan Web</a>
            </div>
            <div class="col-md-9 p-5">
                <h2>Pengaturan Informasi Situs Utama</h2><hr class="border-secondary">

                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success">Konfigurasi pengaturan situs berhasil diperbarui!</div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="bg-dark p-4 rounded border border-secondary">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Website</label>
                            <input type="text" name="site_name" value="<?= sanitize($current_settings['site_name']) ?>" class="form-control bg-black text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor WhatsApp Toko (Awali 62)</label>
                            <input type="text" name="wa_number" value="<?= sanitize($current_settings['wa_number']) ?>" class="form-control bg-black text-white border-secondary" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Judul Utama Banner (Hero Title)</label>
                            <input type="text" name="hero_title" value="<?= sanitize($current_settings['hero_title']) ?>" class="form-control bg-black text-white border-secondary" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sub-Judul Banner (Hero Subtitle)</label>
                            <input type="text" name="hero_subtitle" value="<?= sanitize($current_settings['hero_subtitle']) ?>" class="form-control bg-black text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Warna Tema Utama (Hex Code)</label>
                            <input type="color" name="theme_color" value="<?= sanitize($current_settings['theme_color']) ?>" class="form-control form-control-color bg-black border-secondary w-100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Musik Latar Autoplay (.mp3)</label>
                            <input type="file" name="bg_music" class="form-control bg-black text-white border-secondary" accept=".mp3">
                            <small class="text-muted">File musik saat ini: <?= $current_settings['bg_music'] ?: 'Tidak Aktif' ?></small>
                        </div>
                    </div>
                    <button type="submit" name="save_settings" class="btn btn-info fw-bold text-black mt-4 px-4">Simpan Konfigurasi</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

