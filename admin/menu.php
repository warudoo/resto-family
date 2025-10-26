<?php
require_once __DIR__ . '/../inc/koneksi.php';
require_once __DIR__ . '/../inc/template/header.php';

// Pastikan hanya ADMIN yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

$message = '';

// --- LOGIKA CRUD ---

// 1. CREATE / UPDATE
if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama']);
    $harga = (int)$_POST['harga'];
    $kategori = $_POST['kategori'];
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        // UPDATE
        $stmt = $pdo->prepare("UPDATE menu SET nama = ?, harga = ?, kategori = ? WHERE id = ?");
        $stmt->execute([$nama, $harga, $kategori, $id]);
        $message = "<div class='alert alert-success'>Menu **{$nama}** berhasil diupdate.</div>";
    } else {
        // CREATE
        $stmt = $pdo->prepare("INSERT INTO menu (nama, harga, kategori) VALUES (?, ?, ?)");
        $stmt->execute([$nama, $harga, $kategori]);
        $message = "<div class='alert alert-success'>Menu **{$nama}** berhasil ditambahkan.</div>";
    }
}

// 2. DELETE
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->execute([$id]);
    $message = "<div class='alert alert-warning'>Menu ID: **{$id}** berhasil dihapus.</div>";
    // Redirect untuk menghilangkan parameter GET agar form tidak ter-submit ulang
    header('Location: menu.php'); 
    exit;
}

// 3. READ (Ambil semua menu)
$stmt = $pdo->query("SELECT * FROM menu ORDER BY kategori, nama");
$menu_list = $stmt->fetchAll();

// 4. PRE-FILL FORM untuk EDIT
$menu_edit = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->execute([$id]);
    $menu_edit = $stmt->fetch();
}
?>

<h2 class="mb-4">Kelola Menu Makanan</h2>

<?= $message ?>

<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <?= $menu_edit ? 'Edit Menu: ' . $menu_edit['nama'] : 'Tambah Menu Baru' ?>
    </div>
    <div class="card-body">
        <form action="menu.php" method="POST">
            <input type="hidden" name="id" value="<?= $menu_edit ? $menu_edit['id'] : '' ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="nama" class="form-label">Nama Menu</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $menu_edit ? htmlspecialchars($menu_edit['nama']) : '' ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="harga" class="form-label">Harga (Rp)</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="<?= $menu_edit ? $menu_edit['harga'] : '' ?>" required min="100">
                </div>
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select id="kategori" name="kategori" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <?php $kategoris = ['Nasi', 'Lauk', 'Minuman', 'Pelengkap']; ?>
                        <?php foreach ($kategoris as $k): ?>
                            <option value="<?= $k ?>" <?= $menu_edit && $menu_edit['kategori'] == $k ? 'selected' : '' ?>>
                                <?= $k ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="submit" class="btn btn-primary w-100">
                        <?= $menu_edit ? 'Update' : 'Simpan' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<h3 class="mt-5">Daftar Menu Saat Ini</h3>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($menu_list)): ?>
            <tr><td colspan="5" class="text-center">Belum ada menu yang ditambahkan.</td></tr>
        <?php endif; ?>
        <?php foreach ($menu_list as $menu): ?>
            <tr>
                <td><?= $menu['id'] ?></td>
                <td><?= htmlspecialchars($menu['nama']) ?></td>
                <td>Rp. <?= number_format($menu['harga'], 0, ',', '.') ?></td>
                <td><?= $menu['kategori'] ?></td>
                <td>
                    <a href="menu.php?action=edit&id=<?= $menu['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="menu.php?action=delete&id=<?= $menu['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once __DIR__ . '/../inc/template/footer.php';
?>