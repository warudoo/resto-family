<?php 
// Mulai sesi di awal setiap file 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan BASE_URL terdefinisi
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/resto-family/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESTAURANT FAMILY - Aplikasi Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .login-container {
            max-width: 400px;
            margin-top: 100px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">🍽️ RUDO RESTO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item me-2">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'pemesanan.php' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>pemesanan.php">
                        <i class="bi bi-basket-fill"></i> Pemesanan
                    </a>
                </li>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item me-2">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'menu.php' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>admin/menu.php">
                            <i class="bi bi-gear-fill"></i> Kelola Menu
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout (<?= htmlspecialchars($_SESSION['username']) ?>)
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="btn btn-light btn-sm text-primary fw-bold" href="<?= BASE_URL ?>index.php">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</nav>

<div class="container mt-4">
