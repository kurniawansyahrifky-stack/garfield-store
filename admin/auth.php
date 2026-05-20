<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteksi halaman: Jika tidak ada session admin, tendang kembali ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /admin/login.php");
    exit;
}

// Timeout otomatis jika tidak aktif selama 30 menit
$timeout_duration = 1800; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: /admin/login.php?msg=timeout");
    exit;
}
$_SESSION['last_activity'] = time();
?>
