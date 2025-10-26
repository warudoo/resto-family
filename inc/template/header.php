<?php 
// Mulai sesi di awal setiap file 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESTAURANT FAMILY - Aplikasi Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin-top: 100px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>">RESTAURANT FAMILY</a> 
    <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>pemesanan.php">Pemesanan</a>
            </li>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>admin/menu.php">Kelola Menu</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>logout.php">Logout (<?= $_SESSION['username'] ?>)</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>index.php">Login</a>
            </li>
        <?php endif; ?>
    </ul>
    </div>
</div>
</nav>

<div class="container mt-4"></div>