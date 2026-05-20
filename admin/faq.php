<?php
require_once '../config.php';
require_once 'auth.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_faq'])) {
    $q = sanitize($_POST['question']);
    $a = sanitize($_POST['answer']);

    if(!empty($q) && !empty($a)) {
        $stmt = $pdo->prepare("INSERT INTO faqs (question, answer) VALUES (?, ?)");
        $stmt->execute([$q, $a]);
    }
}

if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM faqs WHERE id = ?")->execute([$id]);
    header("Location: /admin/faq.php");
    exit;
}

$faqs = $pdo->query("SELECT * FROM faqs ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Manajemen FAQ</title>
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
                <a href="/admin/faq.php" class="nav-link text-info fw-bold mb-2"><i class="fas fa-question-circle"></i> FAQ</a>
                <a href="/admin/settings.php" class="nav-link text-white-50 mb-2"><i class="fas fa-sliders-h"></i> Pengaturan Web</a>
            </div>
            <div class="col-md-9 p-5">
                <h2>Kelola FAQ Toko</h2><hr class="border-secondary">
                
                <form method="POST" class="mb-5 p-4 bg-dark rounded border border-secondary">
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <input type="text" name="question" class="form-control bg-black text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jawaban Singkat</label>
                        <textarea name="answer" rows="3" class="form-control bg-black text-white border-secondary" required></textarea>
                    </div>
                    <button type="submit" name="add_faq" class="btn btn-info fw-bold text-black">Simpan FAQ</button>
                </form>

                <?php foreach($faqs as $f): ?>
                    <div class="p-3 bg-dark border border-secondary rounded mb-3 d-flex justify-content-between align-items-start">
                        <div>
                            <h5>❓ <?= sanitize($f['question']) ?></h5>
                            <p class="text-white-50 mb-0">💡 <?= nl2br(sanitize($f['answer'])) ?></p>
                        </div>
                        <a href="/admin/faq.php?delete=<?= $f['id'] ?>" class="text-danger ms-3" onclick="return confirm('Hapus data FAQ?')"><i class="fas fa-trash"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
