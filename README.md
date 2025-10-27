# 🍴 RESTAURANT FAMILY – Aplikasi Pemesanan Bill

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
</p>

**Restaurant Family** adalah sistem **Point of Sale (POS)** sederhana berbasis web yang dibangun menggunakan **PHP Native** dan **MySQL**.
Aplikasi ini berfungsi untuk mengelola menu, mencatat pesanan, dan menghitung total bill otomatis dengan pemisahan peran antara **Admin** dan **Kasir**.
Seluruh sistem dibangun tanpa framework dengan fokus pada **keamanan (PDO, password hashing)** dan **kode bersih**.

---

## ✨ Fitur Utama

* 🔐 **Login & Logout Aman:** Menggunakan PHP Session dan `password_verify()` untuk otentikasi pengguna.
* 👥 **Manajemen Role:**

  * **Admin:** CRUD Menu (tambah, edit, hapus).
  * **Kasir:** Input pesanan dan cetak bill.
* 📝 **CRUD Menu:** Kelola daftar makanan, harga, dan kategori langsung dari dashboard admin.
* 🛒 **Form Pemesanan Dinamis:** Data menu ditarik langsung dari database dengan harga otomatis.
* 🧮 **Kalkulasi Otomatis:**

  * Total Pesanan
  * PPN 10%
  * Total Bayar (Total + PPN)
  * Uang Kembali
    Semua dihitung **real-time di frontend (JavaScript)** dan **divalidasi ulang di backend (PHP)**.
* 🧾 **Struk Pembayaran:** Ringkasan transaksi lengkap setelah proses pembayaran.

---

## 🛠️ Arsitektur & Teknologi

| Kategori         | Teknologi / Pustaka                        |
| ---------------- | ------------------------------------------ |
| **Backend**      | PHP Native (PDO)                           |
| **Database**     | MySQL / MariaDB                            |
| **Frontend**     | HTML5, CSS3, Vanilla JavaScript            |
| **UI Framework** | Bootstrap 5                                |
| **Keamanan**     | Prepared Statement (PDO), Password Hashing |

---

<details>
<summary>📂 Struktur Proyek</summary>

```
resto-family/
├── admin/
│   └── menu.php              # CRUD Menu Makanan
├── assets/
│   └── js/
│       └── kalkulator.js     # Perhitungan Real-time
├── inc/
│   ├── config.php            # BASE_URL
│   ├── koneksi.php           # Koneksi Database (PDO)
│   └── template/
│       ├── header.php
│       └── footer.php
├── index.php                 # Halaman Login
├── logout.php
└── pemesanan.php             # Form Pemesanan & Bill
```

</details>

---

## 🚀 Panduan Instalasi

### 🔧 Prasyarat

* Web Server: **XAMPP / WAMP / MAMP**
* PHP **≥ 7.4**
* MySQL / MariaDB

### 📥 Langkah Instalasi

1. **Clone Repository**

   ```bash
   git clone [link-repo-anda] resto-family
   ```

2. **Buat Database**

   * Buka **phpMyAdmin** → buat database baru (misal: `resto_db`).
   * Jalankan SQL untuk tabel `users` dan `menu`.
   * Masukkan data dummy menu (jika ada file SQL bawaan).

3. **Konfigurasi Koneksi**

   * Edit file `inc/koneksi.php` → sesuaikan `DB_NAME`, `DB_USER`, `DB_PASS`.
   * Di `inc/config.php`, pastikan `BASE_URL` sesuai folder:

     ```php
     define('BASE_URL', '/resto-family/');
     ```

4. **Buat Akun Admin**

   * Jalankan di PHP:

     ```php
     echo password_hash('admin123', PASSWORD_DEFAULT);
     ```
   * Masukkan hasil hash ke tabel `users` dengan `role='admin'`.

5. **Jalankan Aplikasi**

   * Akses di browser:
     👉 `http://localhost/resto-family/`

---

## 💡 Akses Pengguna

| Role      | Halaman          | Fitur                         |
| --------- | ---------------- | ----------------------------- |
| **Admin** | `admin/menu.php` | Tambah/Edit/Hapus menu        |
| **Kasir** | `pemesanan.php`  | Input pesanan dan hitung bill |

---

## 🧭 Catatan Tambahan

* Semua operasi CRUD aman dari SQL Injection karena menggunakan **Prepared Statements (PDO)**.
* Password disimpan dalam bentuk **hash**, bukan teks asli.
* Didesain agar mudah dikembangkan menjadi sistem POS lebih kompleks (fitur laporan, print struk thermal, dsb).

---

Mau gua tambahin juga **preview tampilan (screenshot)** di bagian atas README-nya biar lebih menarik pas diliat di GitHub?
