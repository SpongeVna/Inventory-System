<?php
// inventory-system/pages/barang_edit.php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: barang.php");
    exit;
}

$id_barang = intval($_GET['id']);
$message = '';

// Ambil data barang
$sql = "SELECT * FROM barang WHERE id_barang = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_barang);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$barang = mysqli_fetch_assoc($result);

if (!$barang) {
    header("Location: barang.php");
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga = isset($_POST['harga']) ? floatval($_POST['harga']) : null;
    $stok = isset($_POST['stok']) ? intval($_POST['stok']) : 0;

    $update = "UPDATE barang SET nama_barang = ?, harga = ?, stok = ? WHERE id_barang = ?";
    $stmt_upd = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt_upd, "sdii", $nama, $harga, $stok, $id_barang);

    if (mysqli_stmt_execute($stmt_upd)) {
        $message = "Data barang berhasil diperbarui.";
        // Refresh data agar tampilan ikut update
        $barang['nama_barang'] = $nama;
        $barang['harga'] = $harga;
        $barang['stok'] = $stok;
    } else {
        $message = "Gagal memperbarui data: " . mysqli_error($conn);
    }
}

include "../../includes/header.php";
include "../../includes/sidebar.php";
?>

<div class="container mt-4">
  <h3>Edit Barang</h3>

  <?php if ($message): ?>
    <div class="alert <?= (strpos($message, 'berhasil') !== false) ? 'alert-success' : 'alert-danger' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Nama Barang</label>
      <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Harga</label>
      <input type="number" step="0.01" name="harga" class="form-control" value="<?= htmlspecialchars($barang['harga']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Stok</label>
      <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($barang['stok']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Tanggal Ditambahkan</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($barang['tgl_ditambahkan']) ?>" readonly>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="pages/master/barang.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>

<?php include "../../includes/footer.php"; ?>