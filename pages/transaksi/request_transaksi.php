<?php
require_once "../../includes/auth_check.php";
require_roles(['supplier', 'customer']);
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = intval($_POST['id_barang']);
    $jumlah = intval($_POST['jumlah']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $jenis = ($role === 'supplier') ? 'masuk' : 'keluar';

    mysqli_query($conn, "
        INSERT INTO request_transaksi (id_user, id_barang, jumlah, jenis, keterangan)
        VALUES ($id_user, $id_barang, $jumlah, '$jenis', '$keterangan')
    ");

    $message = "<div class='alert alert-success'>Request transaksi berhasil dikirim dan menunggu persetujuan.</div>";
}

$barang = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Request Transaksi Barang</h3>
        <div class="d-flex gap-2">
            <a href="pages/export/export_request_pdf.php" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
            <a href="pages/export/export_request_excel.php" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <?= $message ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Nama Barang</label>
            <select name="id_barang" class="form-select" required>
                <option value="">-- Pilih Barang --</option>
                <?php while ($b = mysqli_fetch_assoc($barang)): ?>
                    <option value="<?= $b['id_barang'] ?>">
                        <?= htmlspecialchars($b['nama_barang']) ?> (Stok: <?= $b['stok'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" required min="1">
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Kirim Request</button>
    </form>

    <hr class="my-4">
    <h5>Riwayat Request Anda</h5>
    <table class="table table-striped mt-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "
                SELECT r.*, b.nama_barang 
                FROM request_transaksi r 
                JOIN barang b ON r.id_barang = b.id_barang 
                WHERE r.id_user = $id_user
                ORDER BY r.tanggal_request DESC
            ");
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?= $row['id_request'] ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= ucfirst($row['jenis']) ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>
                    <?php
                    if ($row['status'] == 'pending') echo "<span class='badge bg-warning text-dark'>Pending</span>";
                    elseif ($row['status'] == 'disetujui') echo "<span class='badge bg-success'>Disetujui</span>";
                    else echo "<span class='badge bg-danger'>Ditolak</span>";
                    ?>
                </td>
                <td><?= $row['tanggal_request'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>