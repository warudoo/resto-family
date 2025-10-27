<?php
require_once __DIR__ . '/../inc/koneksi.php';
require_once __DIR__ . '/../inc/template/header.php';

// Pastikan hanya ADMIN yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$message = '';

if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $harga = (int)$_POST['harga'];
    $kategori = $_POST['kategori'];
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE menu SET nama = ?, harga = ?, kategori = ? WHERE id = ?");
        $stmt->execute([$nama, $harga, $kategori, $id]);
        $message = "<div class='alert alert-success mb-3'><i class='bi bi-check-circle-fill me-1'></i> Menu <strong>{$nama}</strong> berhasil diupdate.</div>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO menu (nama, harga, kategori) VALUES (?, ?, ?)");
        $stmt->execute([$nama, $harga, $kategori]);
        $message = "<div class='alert alert-success mb-3'><i class='bi bi-check-circle-fill me-1'></i> Menu <strong>{$nama}</strong> berhasil ditambahkan.</div>";
    }
}

// DELETE
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['flash'] = "<div class='alert alert-warning mb-3'><i class='bi bi-exclamation-triangle-fill me-1'></i> Menu ID <strong>{$id}</strong> berhasil dihapus.</div>";
    header('Location: menu.php');
    exit;
}

// READ
$stmt = $pdo->query("SELECT * FROM menu ORDER BY id ASC");
$menu_list = $stmt->fetchAll();


// EDIT (Pre-fill form)
$menu_edit = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->execute([$id]);
    $menu_edit = $stmt->fetch();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary mb-0"></i>Tambahkan Menu</h2>
</div>

<?= $_SESSION['flash'] ?? '' ?>
<?php unset($_SESSION['flash']); ?>
<?= $message ?>

<div class="card border-0 shadow-sm mb-5">
    <div class="card-header bg-success text-white fw-semibold">
        <i class="bi <?= $menu_edit ? 'bi-pencil-square' : 'bi-plus-circle' ?> me-2"></i>
        <?= $menu_edit ? 'Edit Menu: ' . htmlspecialchars($menu_edit['nama']) : 'Tambah Menu Baru' ?>
    </div>
    <div class="card-body">
        <form action="menu.php" method="POST">
            <input type="hidden" name="id" value="<?= $menu_edit['id'] ?? '' ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="nama" class="form-label fw-semibold">Nama Menu</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Nasi Goreng Spesial" 
                        value="<?= htmlspecialchars($menu_edit['nama'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="harga" class="form-label fw-semibold">Harga (Rp)</label>
                    <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 25000" 
                        value="<?= htmlspecialchars($menu_edit['harga'] ?? '') ?>" required min="100">
                </div>
                <div class="col-md-3">
                    <label for="kategori" class="form-label fw-semibold">Kategori</label>
                    <select id="kategori" name="kategori" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <?php $kategoris = ['Nasi', 'Lauk', 'Minuman', 'Pelengkap']; ?>
                        <?php foreach ($kategoris as $k): ?>
                            <option value="<?= $k ?>" <?= ($menu_edit['kategori'] ?? '') == $k ? 'selected' : '' ?>><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="submit" class="btn btn-primary w-100">
                        <i class="bi <?= $menu_edit ? 'bi-save' : 'bi-plus-lg' ?> me-1"></i>
                        <?= $menu_edit ? 'Update' : 'Simpan' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-dark text-white fw-semibold">
        <i class="bi bi-list-ul me-2"></i>Daftar Menu Saat Ini
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th style="width:5%">#</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th style="width:18%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($menu_list)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada menu yang ditambahkan.</td></tr>
                <?php else: ?>
                    <?php foreach ($menu_list as $menu): ?>
                        <tr>
                            <td><?= $menu['id'] ?></td>
                            <td><?= htmlspecialchars($menu['nama']) ?></td>
                            <td>Rp <?= number_format($menu['harga'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($menu['kategori']) ?></td>
                            <td>
                                <a href="menu.php?action=edit&id=<?= $menu['id'] ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="menu.php?action=delete&id=<?= $menu['id'] ?>" 
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                <i class="bi bi-trash3-fill"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/../inc/template/footer.php';
?>
