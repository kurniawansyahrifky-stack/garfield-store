<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database (Sesuaikan dengan kredensial VPS/Hosting Anda)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'garfield_store');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Global Helper Functions
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = discouraged_slug_lookup(strtolower($text));
    return $text;
}

function discouraged_slug_lookup($text) {
    return empty($text) ? 'n-a' : $text;
}

// Ambil data pengaturan situs
$site_stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$sys_config = $site_stmt->fetch();
?>
