<?php
require_once '../config.php';
require_once 'auth.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_cat'])) {
    $cat_name = sanitize($_POST['name']);
    $slug = generateSlug($cat_name);

    if(!empty($cat_name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->execute([$cat_name, $slug]);
    }
}

if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    header("Location: /admin/categories.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { background:#090d16; color:white; } .sidebar { min-height: 100vh; background: #0f1626; padding: 20px; } </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar p-4">
                <h4 style="color:#00d2ff;">CMS Admin</h4><hr>
                <a href="/admin/index.php" class="nav-link text-white-50 mb-2 block"><i class="fas fa-chart-pie"></i> Dashboard</a>
                <a href="/admin/products.php" class="nav-link text-white-50 mb-2 block"><i class="fas fa-box"></i> Produk</a>
                <a href="/admin/categories.php" class="nav-link text-info fw-bold mb-2 block"><i class="fas fa-tags"></i> Kategori</a>
                <a href="/admin/faq.php" class="nav-link text-white-50 mb-2 block"><i class="fas fa-question-circle"></i> FAQ</a>
                <a href="/admin/settings.php" class="nav-link text-white-50 mb-2 block"><i class="fas fa-sliders-h"></i> Pengaturan Web</a>
            </div>
            <div class="col-md-9 p-5">
                <h2>Kelola Kategori Produk</h2><hr class="border-secondary">
                
                <form method="POST" class="row g-3 mb-4">
                    <div class="col-md-8"><input type="text" name="name" class="form-control bg-dark text-white border-secondary" placeholder="Nama Kategori Baru" required></div>
                    <div class="col-md-4"><button type="submit" name="add_cat" class="btn btn-info w-100 fw-bold text-black">Tambah Kategori</button></div>
                </form>

                <ul class="list-group">
                    <?php foreach($categories as $c): ?>
                        <li class="list-group-item bg-dark border-secondary text-white d-flex justify-content-between align-items-center">
                            <?= sanitize($c['name']) ?>
                            <a href="/admin/categories.php?delete=<?= $c['id'] ?>" class="text-danger" onclick="return confirm('Hapus kategori ini?')"><i class="fas fa-trash"></i></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

