<?php
require_once '../config.php';
require_once 'auth.php';

$success_msg = "";
$err_msg = "";

// Aksi Hapus Produk
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    
    // Ambil info nama file gambar lama sebelum dihapus dari disk server
    $img_stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $img_stmt->execute([$del_id]);
    $old_img = $img_stmt->fetchColumn();
    
    if($old_img && file_exists("../uploads/" . $old_img)) {
        unlink("../uploads/" . $old_img);
    }

    $del_stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $del_stmt->execute([$del_id]);
    header("Location: /admin/products.php?success=deleted");
    exit;
}

// Tambah Produk Baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = sanitize($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = (int)$_POST['price'];
    $discount_price = (int)$_POST['discount_price'];
    $status = sanitize($_POST['status']);
    $description = $_POST['description']; // Biarkan HTML mentah dari CKEditor aman karena proteksi pdo
    $slug = generateSlug($name);

    // Proses File Upload Validasi Aman
    $filename = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($file_ext, $allowed_exts)) {
            $filename = time() . '_' . uniqid() . '.' . $file_ext;
            if(!is_dir('../uploads')) { mkdir('../uploads', 0755, true); }
            move_uploaded_file($file_tmp, "../uploads/" . $filename);
        } else {
            $err_msg = "Format ekstensi berkas gambar tidak didukung!";
        }
    }

    if (empty($err_msg)) {
        $ins_stmt = $pdo->prepare("INSERT INTO products (name, slug, category_id, price, discount_price, description, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $ins_stmt->execute([$name, $slug, $category_id, $price, $discount_price, $description, $filename, $status]);
        $success_msg = "Produk baru berhasil ditambahkan!";
    }
}

// Ambil Semua Data Produk & Kategori untuk form dropdown
$all_products = $pdo->query("SELECT p.*, c.name AS cat_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
$all_categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/ckeditor4-releases@4.21.0/ckeditor.js"></script>
    <style>
        body { background: #090d16; color: white; }
        .sidebar { min-height: 100vh; background: #0f1626; padding: 20px; border-right: 1px solid rgba(255,255,255,0.05); }
        .nav-link { color: #94a3b8; padding: 12px; border-radius: 8px; display: block; text-decoration: none; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: rgba(0,210,255,0.1); color: #00d2ff; }
        .table-dark { --bs-table-bg: #0f1626; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar">
                <h4 class="text-center py-3" style="color:#00d2ff; font-weight:bold;">CMS Admin</h4>
                <hr class="bg-secondary">
                <a href="/admin/index.php" class="nav-link"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
                <a href="/admin/products.php" class="nav-link active"><i class="fas fa-box me-2"></i> Produk (CRUD)</a>
                <a href="/admin/categories.php" class="nav-link"><i class="fas fa-tags me-2"></i> Kategori</a>
                <a href="/admin/faq.php" class="nav-link"><i class="fas fa-question-circle me-2"></i> FAQ</a>
                <a href="/admin/settings.php" class="nav-link"><i class="fas fa-sliders-h me-2"></i> Pengaturan Web</a>
                <a href="/admin/logout.php" class="nav-link text-danger mt-5"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a>
            </div>

            <div class="col-md-9 col-lg-10 p-5">
                <h2>Kelola Item Katalog Produk</h2>
                <hr class="border-secondary mb-4">

                <?php if(!empty($success_msg) || isset($_GET['success'])): ?>
                    <div class="alert alert-success">Operasi pemrosesan data berhasil dieksekusi!</div>
                <?php endif; ?>
                <?php if(!empty($err_msg)): ?>
                    <div class="alert alert-danger"><?= $err_msg ?></div>
                <?php endif; ?>

                <div class="card bg-dark border-secondary text-white mb-5">
                    <div class="card-header bg-secondary text-white fw-bold">Tambah Produk Baru</div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Item</label>
                                    <input type="text" name="name" class="form-control bg-black text-white border-secondary" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kategori</label>
                                    <select name="category_id" class="form-select bg-black text-white border-secondary" required>
                                        <?php foreach($all_categories as $c): ?>
                                            <option value="<?= $c['id'] ?>"><?= sanitize($c['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Harga Normal</label>
                                    <input type="number" name="price" class="form-control bg-black text-white border-secondary" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Harga Diskon (Isi 0 jika tidak diskon)</label>
                                    <input type="number" name="discount_price" value="0" class="form-control bg-black text-white border-secondary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status Stok</label>
                                    <select name="status" class="form-select bg-black text-white border-secondary">
                                        <option value="Ready">Ready</option>
                                        <option value="Promo">Promo</option>
                                        <option value="Sold Out">Sold Out</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Gambar Banner Produk</label>
                                    <input type="file" name="image" class="form-control bg-black text-white border-secondary" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Deskripsi Lengkap Item</label>
                                    <textarea name="description" id="editor_desc"></textarea>
                                </div>
                            </div>
                            <button type="submit" name="add_product" class="btn btn-info mt-4 px-4 fw-bold text-black">Simpan Produk</button>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle border-secondary">
                        <thead>
                            <tr>
                                <th>Visual</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Pokok</th>
                                <th>Status</th>
                                <th>Kontrol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($all_products as $row): ?>
                            <tr>
                                <td><img src="/uploads/<?= $row['image'] ?: 'default-prod.jpg' ?>" width="60" class="rounded"></td>
                                <td class="fw-bold"><?= sanitize($row['name']) ?></td>
                                <td><?= sanitize($row['cat_name']) ?></td>
                                <td><?= formatRupiah($row['price']) ?></td>
                                <td><span class="badge bg-secondary"><?= $row['status'] ?></span></td>
                                <td>
                                    <a href="/admin/products.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini permanent?')"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('editor_desc');
    </script>
</body>
</html>
