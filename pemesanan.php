<?php
require_once __DIR__ . '/inc/koneksi.php';
require_once __DIR__ . '/inc/template/header.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

// Ambil semua menu dari database
$stmt = $pdo->query("SELECT * FROM menu WHERE status = 'tersedia' ORDER BY kategori, nama");
$menus = $stmt->fetchAll();

$data_menu = [];
foreach ($menus as $m) {
    $data_menu[$m['id']] = $m;
}

// Pisahkan menu berdasarkan kategori
$menu_kategori = [
    'Nasi' => [],
    'Lauk' => [],
    'Minuman' => [],
    'Pelengkap' => [],
];
foreach ($menus as $m) {
    $menu_kategori[$m['kategori']][] = $m;
}

$hasil_transaksi = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_pesanan'])) {
    // --- BEST PRACTICE: HITUNG ULANG DI SISI SERVER (PHP) ---
    $pesanan_input = $_POST['jumlah'] ?? [];
    $uang_input = (float)($_POST['uang_input'] ?? 0);
    
    $total_pesanan = 0; // JML SELURUH SUB TOTAL
    $detail_pesanan = [];

    // 1. Loop input pesanan dan hitung total menggunakan HARGA DARI DATABASE
    foreach ($pesanan_input as $menu_id => $jumlah) {
        $jumlah = (int)$jumlah;
        if ($jumlah > 0 && isset($data_menu[$menu_id])) {
            $menu_item = $data_menu[$menu_id];
            $sub_total_item = $menu_item['harga'] * $jumlah; // Sub Total per-baris
            
            $total_pesanan += $sub_total_item;
            
            $detail_pesanan[] = [
                'nama' => $menu_item['nama'],
                'harga' => $menu_item['harga'],
                'jumlah' => $jumlah,
                'sub_total' => $sub_total_item,
            ];
        }
    }
    
    if (!empty($detail_pesanan)) {
        // 2. Terapkan Ketentuan Perhitungan
        $ppn = $total_pesanan * 0.10; // PPN = 10% X TOTAL BAYAR (ini salah di soal, seharusnya 10% X Total Pesanan)
        $total_bayar = $total_pesanan + $ppn; // TOTAL BAYAR = Total Pesanan + PPN
        
        $uang_kembali = 0;
        if ($uang_input >= $total_bayar) {
            $uang_kembali = $uang_input - $total_bayar; // UANG KEMBALI
        }
        
        // Simpan hasil untuk ditampilkan
        $hasil_transaksi = [
            'detail' => $detail_pesanan,
            'total_pesanan' => $total_pesanan,
            'ppn' => $ppn,
            'total_bayar' => $total_bayar,
            'uang_input' => $uang_input,
            'uang_kembali' => $uang_kembali,
            'pembayaran_cukup' => $uang_input >= $total_bayar,
        ];

    } else {
        echo "<div class='alert alert-danger'>Silakan pilih menu pesanan terlebih dahulu.</div>";
    }
}
?>

<h2 class="mb-4">Form Pemesanan RESTO FAMILY</h2>

<div class="row">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Input Pesanan</div>
            <div class="card-body">
                <form action="pemesanan.php" method="POST" id="form-pemesanan">
                    <div class="row mb-3">
                        <?php foreach ($menu_kategori as $kategori => $items): ?>
                            <?php if (!empty($items)): ?>
                                <div class="col-md-4">
                                    <h5 class="mt-2 text-primary"><?= $kategori ?></h5>
                                    <?php foreach ($items as $item): ?>
                                        <div class="input-group input-group-sm mb-2">
                                            <span class="input-group-text"><?= htmlspecialchars($item['nama']) ?> (Rp <?= number_format($item['harga']) ?>)</span>
                                            <input type="number" 
                                                   class="form-control text-end input-jumlah" 
                                                   name="jumlah[<?= $item['id'] ?>]" 
                                                   data-harga="<?= $item['harga'] ?>" 
                                                   data-nama="<?= htmlspecialchars($item['nama']) ?>"
                                                   data-kategori="<?= $kategori ?>"
                                                   min="0" value="0">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4 border-top pt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="uang_input" class="form-label">Uang Input (ATM/Debit/Tunai)</label>
                                <input type="number" step="100" class="form-control form-control-lg" id="uang_input" name="uang_input" value="0" required min="0">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" name="proses_pesanan" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-cart-fill"></i> PROSES BILL
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">RECAP & TOTAL BILL</div>
            <div class="card-body">
                
                <h5 class="mb-3">Detail Pesanan</h5>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr class="table-info">
                            <th>Menu</th>
                            <th>Jml</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-pesanan">
                        </tbody>
                </table>
                
                <div class="mt-4">
                    <div class="mb-2">
                        <label class="form-label fw-bold">Total Pesanan (JML SELURUH SUB TOTAL):</label>
                        <input type="text" id="total_pesanan_display" class="form-control text-end fw-bold" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">PPN (10%):</label>
                        <input type="text" id="ppn_display" class="form-control text-end" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">TOTAL BAYAR:</label>
                        <input type="text" id="total_bayar_display" class="form-control text-end form-control-lg fw-bolder text-danger" readonly>
                        <input type="hidden" id="total_bayar_hidden" value="0">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold text-success">Uang Kembali:</label>
                        <input type="text" id="kembalian_display" class="form-control text-end form-control-lg fw-bolder text-success" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($hasil_transaksi): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">HASIL TRANSAKSI</h4>
            </div>
            <div class="card-body">
                <?php if (!$hasil_transaksi['pembayaran_cukup']): ?>
                    <div class="alert alert-danger">
                        Uang yang dibayarkan (Rp <?= number_format($hasil_transaksi['uang_input']) ?>) kurang dari Total Bayar (Rp <?= number_format($hasil_transaksi['total_bayar']) ?>).
                    </div>
                <?php endif; ?>
                
                <table class="table table-sm table-bordered">
                    <thead><tr><th>Item</th><th>Harga Satuan</th><th>Jml</th><th>Sub Total</th></tr></thead>
                    <tbody>
                        <?php foreach ($hasil_transaksi['detail'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nama']) ?></td>
                                <td>Rp <?= number_format($item['harga']) ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td>Rp <?= number_format($item['sub_total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <h4 class="mt-4">Summary Bill:</h4>
                <p><strong>Total Pesanan (JML SUB TOTAL):</strong> Rp <?= number_format($hasil_transaksi['total_pesanan']) ?></p>
                <p><strong>PPN (10%):</strong> Rp <?= number_format($hasil_transaksi['ppn']) ?></p>
                <h3 class="text-danger">TOTAL BAYAR: Rp <?= number_format($hasil_transaksi['total_bayar']) ?></h3>
                <hr>
                <p><strong>Uang Input:</strong> Rp <?= number_format($hasil_transaksi['uang_input']) ?></p>
                <h3 class="text-success">UANG KEMBALI: Rp <?= number_format($hasil_transaksi['uang_kembali']) ?></h3>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
require_once __DIR__ . '/inc/template/footer.php';
?>