-- TABEL USERS (Untuk Login)
CREATE TABLE users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    -- Simpan password yang sudah di-hash (misalnya 255 karakter)
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir'
);

-- TABEL MENU (Untuk Daftar Makanan)
CREATE TABLE menu (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(150) NOT NULL,
    harga DECIMAL(10, 0) NOT NULL, -- DECIMAL untuk harga
    kategori ENUM('Nasi', 'Lauk', 'Minuman', 'Pelengkap') NOT NULL,
    status ENUM('tersedia', 'habis') NOT NULL DEFAULT 'tersedia'
);

-- Contoh Data Awal (Admin)
-- Ganti 'hashed_password_admin' dengan hasil dari password_hash('passwordku', PASSWORD_DEFAULT)
INSERT INTO users (username, password, role) VALUES ('adminresto', '$2y$10$............', 'admin');