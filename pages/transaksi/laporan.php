<?php
require_once "../../includes/auth_check.php";
require_roles(['admin']);
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// Ambil semua data transaksi
$query = mysqli_query($conn, "
    SELECT t.*, b.nama_barang, u.nama_lengkap 
    FROM transaksi t
    JOIN barang b ON t.id_barang = b.id_barang
    JOIN users u ON t.id_user = u.id_user
    ORDER BY t.tanggal_transaksi DESC
");
?>

<div class="container mt-4">
    <h3 class="mb-3">Laporan Transaksi</h3>
    <p>Berikut semua transaksi barang masuk, keluar, dan request yang telah disetujui.</p>

    <div class="mb-3">
        <a href="pages/export/export_pdf.php" target="_blank" class="btn btn-danger btn-sm">
            📄 Export PDF
        </a>
        <a href="pages/export/export_excel.php" class="btn btn-success btn-sm">
            📊 Export Excel
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Jenis Transaksi</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Dicatat Oleh</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $row['id_transaksi'] ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td>
                            <span class="badge <?= $row['jenis'] === 'masuk' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= ucfirst($row['jenis']) ?>
                            </span>
                        </td>
                        <td><?= $row['jumlah'] ?></td>
                        <td><?= date('Y-m-d H:i:s', strtotime($row['tanggal_transaksi'])) ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($row['keterangan'] ?: '-') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>