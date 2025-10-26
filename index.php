<?php
// Sertakan file koneksi dan header
require_once __DIR__ . '/inc/koneksi.php';
require_once __DIR__ . '/inc/template/header.php';

$error = '';

// Cek apakah user sudah login
if (isset($_SESSION['user_id'])) {
header('Location: ' . BASE_URL . 'pemesanan.php');
exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$username = trim($_POST['username']);
$password = $_POST['password'];

// 1. Validasi Input Dasar
if (empty($username) || empty($password)) {
    $error = "Username dan password wajib diisi.";
} else {
    // 2. Gunakan Prepared Statements untuk keamanan
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // 3. Login Berhasil, atur Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Arahkan ke halaman Pemesanan
        header('Location: ' . BASE_URL . 'pemesanan.php');
        exit;
    } else {
        $error = "Username atau Password salah.";
    }
}
}
?>

<div class="login-container card shadow-lg mx-auto">
<div class="card-header bg-primary text-white">
    <h4 class="mb-0">RESTAURANT FAMILY - Login</h4>
</div>
<div class="card-body">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="index.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
</div>

<?php 
require_once __DIR__ . '/inc/template/footer.php';
?>