<?php
require_once __DIR__ . '/inc/koneksi.php';
require_once __DIR__ . '/inc/template/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM menu WHERE status = 'tersedia' ORDER BY kategori, nama");
$menus = $stmt->fetchAll();

$data_menu = [];
foreach ($menus as $m) {
    $data_menu[$m['id']] = $m;
}

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
    $pesanan_input = $_POST['jumlah'] ?? [];
    $uang_input = (float)($_POST['uang_input'] ?? 0);
    $total_pesanan = 0;
    $detail_pesanan = [];

    foreach ($pesanan_input as $menu_id => $jumlah) {
        $jumlah = (int)$jumlah;
        if ($jumlah > 0 && isset($data_menu[$menu_id])) {
            $menu_item = $data_menu[$menu_id];
            $sub_total_item = $menu_item['harga'] * $jumlah;
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
        $ppn = $total_pesanan * 0.12;
        $total_bayar = $total_pesanan + $ppn;
        $uang_kembali = max(0, $uang_input - $total_bayar);

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
        echo "<div class='alert alert-danger shadow-sm'>Silakan pilih menu pesanan terlebih dahulu.</div>";
    }
}
?>

<style>
    body { background-color: #f8fafc; }
    h2 { font-weight: 700; color: #2d3436; }
    .card { border-radius: 14px; overflow: hidden; border: none; }
    .card-header { font-weight: 600; letter-spacing: 0.3px; }
    .form-control:focus { box-shadow: none !important; border-color: #4e73df; }
    .alert { border-radius: 10px; }
</style>

<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-receipt-cutoff"></i> Form Pemesanan <span class="text-primary">Rudo Resto</span></h2>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-list-ul"></i> Input Pesanan
        </div>
        <div class="card-body bg-white">
            <form action="pemesanan.php" method="POST" id="form-pemesanan">
                <div class="row">
                    <?php foreach ($menu_kategori as $kategori => $items): ?>
                        <?php if (!empty($items)): ?>
                            <div class="col-md-6 mb-3">
                                <h5 class="mt-2 text-primary border-bottom pb-1"><?= $kategori ?></h5>
                                <?php foreach ($items as $item): ?>
                                    <div class="input-group input-group-sm mb-2">
                                        <span class="input-group-text w-100 d-flex justify-content-between">
                                            <?= htmlspecialchars($item['nama']) ?> 
                                            <small class="text-muted">(Rp <?= number_format($item['harga']) ?>)</small>
                                        </span>
                                        <input type="number" 
                                            class="form-control text-end" 
                                            name="jumlah[<?= $item['id'] ?>]" 
                                            min="0" value="0">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 border-top pt-3">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="uang_input" class="form-label fw-semibold">Uang Input (Tunai/Debit)</label>
                            <input type="number" step="100" class="form-control form-control-lg" id="uang_input" name="uang_input" value="0" required min="0">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" name="proses_pesanan" class="btn btn-success btn-lg w-100 shadow-sm">
                                <i class="bi bi-cash-stack"></i> Proses Bill
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($hasil_transaksi): ?>
    <div class="card border-success shadow-sm">
        <div class="card-header bg-success text-white">
            <i class="bi bi-check2-circle"></i> Hasil Transaksi
        </div>
        <div class="card-body bg-white">
            <?php if (!$hasil_transaksi['pembayaran_cukup']): ?>
                <div class="alert alert-danger">
                    Uang dibayarkan (Rp <?= number_format($hasil_transaksi['uang_input']) ?>) 
                    kurang dari Total Bayar (Rp <?= number_format($hasil_transaksi['total_bayar']) ?>).
                </div>
            <?php endif; ?>

            <table class="table table-sm table-bordered align-middle">
                <thead class="table-light">
                    <tr><th>Item</th><th>Harga</th><th>Jml</th><th>Sub Total</th></tr>
                </thead>
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

            <div class="mt-4">
                <p><strong>Total Pesanan:</strong> Rp <?= number_format($hasil_transaksi['total_pesanan']) ?></p>
                <p><strong>PPN (12%):</strong> Rp <?= number_format($hasil_transaksi['ppn']) ?></p>
                <h4 class="text-danger fw-bold">TOTAL BAYAR: Rp <?= number_format($hasil_transaksi['total_bayar']) ?></h4>
                <hr>
                <p><strong>Uang Input:</strong> Rp <?= number_format($hasil_transaksi['uang_input']) ?></p>
                <h4 class="text-success fw-bold">UANG KEMBALI: Rp <?= number_format($hasil_transaksi['uang_kembali']) ?></h4>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/inc/template/footer.php'; ?>
