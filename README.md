# 🍴 RESTAURANT FAMILY - Aplikasi Pemesanan Bill

\<p align="center"\>
\<img src="[https://img.shields.io/badge/PHP-777BB4?style=for-the-badge\&logo=php\&logoColor=white](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)" alt="PHP"\>
\<img src="[https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge\&logo=mysql\&logoColor=white](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)" alt="MySQL"\>
\<img src="[https://img-shields.io/badge/Bootstrap-563D7C?style=for-the-badge\&logo=bootstrap\&logoColor=white](https://www.google.com/search?q=https://img-shields.io/badge/Bootstrap-563D7C%3Fstyle%3Dfor-the-badge%26logo%3Dbootstrap%26logoColor%3Dwhite)" alt="Bootstrap 5"\>
\<img src="[https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge\&logo=javascript\&logoColor=black](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)" alt="JavaScript"\>
\</p\>

**RESTAURANT FAMILY - Aplikasi Pemesanan Bill** adalah sistem *Point of Sale* (POS) sederhana berbasis web yang dibangun menggunakan **PHP Native** dan **MySQL**. Aplikasi ini dirancang untuk memproses input pesanan, menghitung total bill secara otomatis, dan memisahkan akses antara Kasir dan Admin. Proyek ini dibangun tanpa *framework* dengan fokus pada implementasi PHP Native yang aman dan *clean code* (menggunakan PDO dan *password hashing*).

-----

## ✨ Fitur Utama

  - **🔐 Sistem Login & Logout:** Mekanisme otentikasi yang aman menggunakan PHP Session dan `password_verify()` untuk membatasi akses.
  - **👥 Manajemen Peran (Roles):** Sistem mendukung dua peran utama: **Admin** (akses penuh ke CRUD Menu) dan **Kasir** (hanya akses ke Form Pemesanan).
  - **📝 CRUD Menu Makanan:** Fitur untuk menambah, mengedit, dan menghapus menu makanan beserta harga dan kategorinya (*fitur eksklusif untuk Admin*).
  - **🛒 Form Pemesanan Dinamis:** Form pemesanan yang mengambil daftar menu dan harga secara dinamis dari database.
  - **🧮 Kalkulasi Bill Otomatis:** Menghitung total pembayaran secara *real-time* di *frontend* (JS) dan divalidasi ulang di *backend* (PHP) saat *submit* untuk memastikan keamanan harga.
  - **💰 Perhitungan Final Sesuai Ketentuan:**
      - Menghitung **Total Pesanan** (JML SELURUH SUB TOTAL).
      - Menambahkan **PPN 10%** dari Total Pesanan.
      - Menghitung **TOTAL BAYAR** (Total Pesanan + PPN).
      - Menghitung **Uang Kembali** (Uang Input - Total Bayar).
  - **🧾 Struk Pembayaran:** Menampilkan rekapitulasi bill yang terperinci setelah proses pembayaran.

-----

## 🛠️ Arsitektur & Teknologi

| Kategori | Teknologi / Pustaka |
| :--- | :--- |
| **Backend** | `PHP Native` (PDO untuk koneksi aman) |
| **Database** | `MySQL` / `MariaDB` |
| **Frontend** | `HTML5`, `CSS3`, `Vanilla JavaScript` |
| **Framework UI** | `Bootstrap 5` |
| **Keamanan** | `Prepared Statements` (PDO), `Password Hashing` (`password_verify`) |

<br>

\<details\>
\<summary\>📂 Struktur Proyek\</summary\>

\<pre\>
resto-family/
├── admin/
│   ├── menu.php              // CRUD Menu Makanan
├── assets/
│   └── js/
│       └── kalkulator.js       // Skrip Perhitungan Real-time
├── inc/
│   ├── config.php            // BASE\_URL
│   ├── koneksi.php           // Koneksi PDO
│   └── template/
│       ├── header.php
│       └── footer.php
├── index.php                 // Halaman Login
├── logout.php
└── pemesanan.php             // Form Pemesanan & Kalkulasi Bill
\</pre\>

\</details\>

-----

## 🚀 Panduan Instalasi & Penggunaan

### **Prasyarat**

1.  **Web Server** (Wajib): XAMPP, WAMP, atau MAMP.
2.  **PHP** versi 7.4 atau lebih tinggi.
3.  **Database MySQL** atau MariaDB.

### **Langkah-langkah Instalasi**

1.  **Clone Repositori**

    *(Asumsikan Anda menamai folder proyek `resto-family`)*

    ```bash
    git clone [Link Repositori Anda] resto-family
    ```

2.  **Setup Database**

      - Buka **phpMyAdmin** dan buat database baru (misalnya bernama `resto_db`).
      - Jalankan kode SQL untuk membuat tabel `users` dan `menu` (sesuai skema yang telah disiapkan).
      - **Isi Data Menu Dummy** dengan kode SQL yang sudah disediakan sebelumnya.

3.  **Konfigurasi Koneksi**

      - Buka file **`inc/koneksi.php`** dan sesuaikan detail koneksi database (`DB_NAME`, `DB_USER`, `DB_PASS`).
      - Buka file **`inc/config.php`** dan pastikan `BASE_URL` sesuai dengan nama folder Anda:
        ```php
        define('BASE_URL', '/resto-family/'); 
        ```

4.  **Buat Akun Admin Awal**

      - Gunakan fungsi `password_hash()` di PHP untuk mendapatkan hash password, lalu masukkan hash tersebut ke tabel `users` dengan `role='admin'`.

5.  **Jalankan Aplikasi**

      - Buka browser dan akses `http://localhost/resto-family/`.

### **Akses dan Penggunaan Aplikasi**

  - **Halaman Awal:** Akan mengarahkan ke halaman login (`index.php`).
  - **Akses Admin:** Setelah login sebagai admin, Anda dapat mengakses **"Kelola Menu"** (`admin/menu.php`) untuk menambah, edit, atau hapus item.
  - **Akses Kasir/Pemesanan:** Akses **"Pemesanan"** (`pemesanan.php`) untuk memasukkan pesanan dan memproses bill.
