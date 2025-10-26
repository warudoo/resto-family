<?php
session_start();
include 'config/db.php';

if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$u' AND password='$p'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $u;
        header('Location: dashboard.php');
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Resto Family</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
<div class="card p-4 shadow-lg" style="width: 360px;">
    <h4 class="text-center mb-4">Login Resto Family</h4>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button name="login" class="btn btn-primary w-100">Masuk</button>
    </form>
</div>
</body>
</html>
