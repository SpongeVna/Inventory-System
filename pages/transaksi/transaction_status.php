<?php
require_once "../../includes/auth_check.php";
require_roles(['supplier', 'customer']);
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$role = $_SESSION['role'];
$nama = $_SESSION['nama_lengkap'];
?>

<div class="container mt-4">
    <h3>Status Transaksi Anda</h3>
    <p>Halo, <strong><?= htmlspecialchars($nama) ?></strong> (<?= ucfirst($role) ?>). Berikut riwayat transaksi yang tercatat.</p>

    <div class="alert alert-info mt-3">
        Halaman ini hanya menampilkan transaksi barang yang sudah dicatat oleh operator/admin.
    </div>

    <table class="table table-striped mt-3">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Sementara: tampilkan semua transaksi
            // (nanti bisa difilter berdasarkan id_user jika sistem supplier/customer sudah terhubung)
            $id_user = $_SESSION['id_user'];
            $query = mysqli_query($conn, "
                SELECT t.*, b.nama_barang 
                FROM transaksi t 
                JOIN barang b ON t.id_barang = b.id_barang
                WHERE t.id_user = $id_user
                ORDER BY t.tanggal_transaksi DESC
            ");
            while ($row = mysqli_fetch_assoc($query)):
            ?>
            <tr>
                <td><?= $row['id_transaksi'] ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= ucfirst($row['jenis']) ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['tanggal_transaksi'] ?></td>
                <td><?= htmlspecialchars($row['keterangan'] ?: '-') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>