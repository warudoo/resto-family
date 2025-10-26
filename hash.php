<?php
$password_asli = 'admin123'; // Ganti dengan password yang Anda inginkan
$hashed_password = password_hash($password_asli, PASSWORD_DEFAULT);

echo "Password Asli: " . $password_asli . "<br>";
echo "Password Hash (Inilah yang disimpan di DB): " . $hashed_password;
?>