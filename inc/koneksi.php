<?php
require_once __DIR__ . '/config.php'; 
// Terapkan error reporting yang ketat
ini_set('display_errors', 1); // Ubah ke 0 saat production!
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Konfigurasi Database
$host = 'localhost';
$db   = 'resto-family';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mode Error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Hasil Fetch dalam bentuk Array Asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Matikan Emulasi Prepared Statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Di lingkungan production, catat error, jangan tampilkan ke pengguna
    exit('Koneksi database gagal: ' . $e->getMessage()); 
}
?>