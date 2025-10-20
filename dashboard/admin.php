<?php
require_once "../includes/auth_check.php";
require_role('admin');
require_once "../config/db.php";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="container mt-4">
    <h3>Dashboard Admin</h3>
    <p class="text-muted">Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>!</p>

    <div class="row text-center mt-4">
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card shadow-sm">
                <div class="card-body">
                    <h5>Total Barang</h5>
                    <?php $barang = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM barang"))[0]; ?>
                    <h3><?= $barang ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card shadow-sm">
                <div class="card-body">
                    <h5>Total Transaksi</h5>
                    <?php $trx = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM transaksi"))[0] ?? 0; ?>
                    <h3><?= $trx ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card shadow-sm">
                <div class="card-body">
                    <h5>Total User</h5>
                    <?php $user = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0]; ?>
                    <h3><?= $user ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="pages/master/barang.php" class="btn btn-primary btn-sm">Kelola Barang</a>
        <a href="pages/transaksi/barang_masuk.php" class="btn btn-success btn-sm">Barang Masuk</a>
        <a href="pages/transaksi/barang_keluar.php" class="btn btn-warning btn-sm">Barang Keluar</a>
        <a href="pages/transaksi/laporan.php" class="btn btn-info btn-sm">Laporan</a>
    </div>
</div>

<?php include "../includes/footer.php"; ?>