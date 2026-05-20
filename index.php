<?php
require_once 'config.php';

// Ambil Kategori
$cats_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $cats_stmt->fetchAll();

// Ambil Produk
$prod_stmt = $pdo->query("SELECT p.*, c.slug AS cat_slug FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.sort_order ASC, p.id DESC");
$products = $prod_stmt->fetchAll();

// Ambil FAQ
$faqs = $pdo->query("SELECT * FROM faqs ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($sys_config['site_name']) ?> - Premium Marketplace</title>
    <meta name="description" content="<?= sanitize($sys_config['hero_subtitle']) ?>">
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --neon-blue: <?= $sys_config['theme_color'] ?: '#00d2ff' ?>; }
    </style>
</head>
<body class="dark-mode">

    <div id="loading-screen">
        <div class="spinner"></div>
        <h2 style="font-family:'Orbitron'; margin-top:20px; color:white; letter-spacing:3px;"><?= sanitize($sys_config['site_name']) ?></h2>
        <button id="enter-btn" class="btn-premium" style="width:auto; margin-top:30px; padding:10px 40px;">ENTER WEBSITE</button>
    </div>

    <?php if(!empty($sys_config['bg_music'])): ?>
        <audio id="bg-music" loop src="/uploads/<?= $sys_config['bg_music'] ?>"></audio>
    <?php endif; ?>

    <nav class="glass-nav glass">
        <a href="/" class="logo-text"><?= sanitize($sys_config['site_name']) ?></a>
        <div class="search-box">
            <input type="text" id="search-input" placeholder="Cari item digital...">
        </div>
    </nav>

    <header class="hero">
        <h1 class="logo-text" style="font-size:3.5rem;"><?= sanitize($sys_config['hero_title']) ?></h1>
        <p style="color:var(--text-muted); max-width:600px; margin:0 auto 30px;"><?= sanitize($sys_config['hero_subtitle']) ?></p>
        
        <div class="filter-tabs">
            <button class="tab-btn active" data-filter="all">Semua</button>
            <?php foreach($categories as $cat): ?>
                <button class="tab-btn" data-filter="<?= $cat['slug'] ?>"><?= sanitize($cat['name']) ?></button>
            <?php endforeach; ?>
        </div>
    </header>

    <main class="grid-container">
        <?php if(empty($products)): ?>
            <p style="grid-column: 1/-1; text-align:center; color:var(--text-muted);">Belum ada produk yang tersedia.</p>
        <?php endif; ?>

        <?php foreach($products as $p): ?>
            <div class="product-card glass" data-category="<?= $p['cat_slug'] ?>">
                <span class="badge <?= strtolower(str_replace(' ', '-', $p['status'])) ?>"><?= $p['status'] ?></span>
                <div class="product-image">
                    <img src="/uploads/<?= $p['image'] ?: 'default-prod.jpg' ?>" alt="<?= sanitize($p['name']) ?>">
                </div>
                <div class="product-content">
                    <h3 style="margin-bottom:10px; font-size:1.2rem;"><?= sanitize($p['name']) ?></h3>
                    <div style="margin-bottom:15px;">
                        <?php if($p['discount_price'] > 0): ?>
                            <span class="price-strike"><?= formatRupiah($p['price']) ?></span>
                            <span class="price-actual"><?= formatRupiah($p['discount_price']) ?></span>
                        <?php else: ?>
                            <span class="price-actual"><?= formatRupiah($p['price']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom:20px;">
                        <?= $p['description'] ?>
                    </div>
                    <?php
                        $encoded_msg = urlencode("Halo " . $sys_config['site_name'] . ", saya ingin memesan produk: " . $p['name']);
                        $wa_url = "https://wa.me/" . $sys_config['wa_number'] . "?text=" . $encoded_msg;
                    ?>
                    <a href="<?= $wa_url ?>" target="_blank" class="btn-premium"><i class="fab fa-whatsapp"></i> BELI SEKARANG</a>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <section class="faq-sec">
        <h2 class="logo-text" style="text-align:center; margin-bottom:30px; font-size:2rem;">PERTANYAAN UMUM (FAQ)</h2>
        <?php foreach($faqs as $f): ?>
            <div class="faq-box glass">
                <button class="faq-trigger">
                    <?= sanitize($f['question']) ?>
                    <i class="fas fa-plus"></i>
                </button>
                <div class="faq-content">
                    <p><?= nl2br(sanitize($f['answer'])) ?></p>
                </div>
            </div>
        <?php endphp ?>
        <?php endforeach; ?>
    </section>

    <div class="floating-widgets">
        <?php if(!empty($sys_config['bg_music'])): ?>
            <button id="music-btn-toggle" class="circle-btn btn-music"><i class="fas fa-volume-up"></i></button>
        <?php endif; ?>
        <a href="https://wa.me/<?= $sys_config['wa_number'] ?>" target="_blank" class="circle-btn btn-wa-float"><i class="fab fa-whatsapp"></i></a>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>

