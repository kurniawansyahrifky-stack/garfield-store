<?php
require_once '../config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: /admin/index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            $_SESSION['last_activity'] = time();

            header("Location: /admin/index.php");
            exit;
        } else {
            $error = "Kredensial login salah!";
        }
    } else {
        $error = "Semua bidang wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Secure Login - Garfield Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-box { width: 100%; max-width: 420px; padding: 40px 30px; }
    </style>
</head>
<body>
    <div class="login-box glass text-center">
        <h2 class="logo-text mb-4" style="font-size:1.8rem;">CMS CONTROL CENTER</h2>
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger py-2" style="font-size:0.85rem;"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3 text-start">
                <label class="form-label small text-white-50">Username</label>
                <input type="text" name="username" class="form-control bg-dark border-secondary text-white" required autocomplete="off">
            </div>
            <div class="mb-4 text-start">
                <label class="form-label small text-white-50">Password Key</label>
                <input type="password" name="password" class="form-control bg-dark border-secondary text-white" required>
            </div>
            <button type="submit" class="btn-premium border-0 w-100 py-2.5">INITIALIZE ACCESS</button>
        </form>
    </div>
</body>
</html>
