<?php
require_once "../includes/auth_check.php";
require_role('operator');
require_once "../config/db.php";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="container mt-4">
    <h3>Dashboard Operator</h3>
    <p class="text-muted">Hai, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>. Berikut aktivitas gudang hari ini.</p>

    <div class="row text-center mt-4">
        <div class="col-md-6 mb-3">
            <div class="card dashboard-card shadow-sm">
                <div class="card-body">
                    <h5>Barang Masuk Hari Ini</h5>
                    <?php $masuk = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM transaksi WHERE jenis='masuk' AND DATE(tanggal_transaksi)=CURDATE()"))[0] ?? 0; ?>
                    <h3><?= $masuk ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card dashboard-card shadow-sm">
                <div class="card-body">
                    <h5>Barang Keluar Hari Ini</h5>
                    <?php $keluar = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM transaksi WHERE jenis='keluar' AND DATE(tanggal_transaksi)=CURDATE()"))[0] ?? 0; ?>
                    <h3><?= $keluar ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="pages/transaksi/barang_masuk.php" class="btn btn-success btn-sm">Input Barang Masuk</a>
        <a href="pages/transaksi/barang_keluar.php" class="btn btn-warning btn-sm">Input Barang Keluar</a>
        <a href="pages/master/barang.php" class="btn btn-outline-secondary btn-sm">Lihat Stok</a>
    </div>
</div>

<?php include "../includes/footer.php"; ?>