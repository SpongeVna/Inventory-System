<?php
require_once "../../includes/auth_check.php";
require_once "../../config/db.php";

if (!in_array($_SESSION['role'], ['admin', 'operator'])) {
    header("Location: ../dashboard.php");
    exit;
}

include "../../includes/header.php";
include "../../includes/sidebar.php";


$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = intval($_POST['id_barang']);
    $jumlah = intval($_POST['jumlah']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Update stok barang
    $update = mysqli_query($conn, "UPDATE barang SET stok = stok + $jumlah WHERE id_barang = $id_barang");

    if ($update) {
        // Catat transaksi
        $id_user = $_SESSION['id_user'];
        mysqli_query($conn, "INSERT INTO transaksi (id_barang, id_user, jumlah, jenis, keterangan) VALUES ($id_barang, $id_user, $jumlah, 'masuk', '$keterangan')");
        $message = "<div class='alert alert-success'>Barang masuk berhasil dicatat.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Terjadi kesalahan saat mencatat transaksi.</div>";
    }
}

$barang = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");
?>

<div class="container mt-4">
    <h3>Transaksi Barang Masuk</h3>
    <?= $message ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Nama Barang</label>
            <select name="id_barang" class="form-select" required>
                <option value="">-- Pilih Barang --</option>
                <?php while ($b = mysqli_fetch_assoc($barang)): ?>
                    <option value="<?= $b['id_barang'] ?>"><?= htmlspecialchars($b['nama_barang']) ?> (Stok: <?= $b['stok'] ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Jumlah Masuk</label>
            <input type="number" name="jumlah" class="form-control" required min="1">
        </div>

        <div class="mb-3">
            <label>Keterangan (opsional)</label>
            <input type="text" name="keterangan" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Simpan Transaksi</button>
        <a href="pages/master/barang.php" class="btn btn-secondary">Kembali</a>
    </form>

    <hr class="my-4">
    <h5>Riwayat Barang Masuk</h5>
    <table class="table table-striped mt-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "
                SELECT t.*, b.nama_barang 
                FROM transaksi t 
                JOIN barang b ON t.id_barang = b.id_barang 
                WHERE t.jenis='masuk' 
                ORDER BY t.tanggal_transaksi DESC
            ");
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?= $row['id_transaksi'] ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['tanggal_transaksi'] ?></td>
                <td><?= htmlspecialchars($row['keterangan'] ?: '-') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>