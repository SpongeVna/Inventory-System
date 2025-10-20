<?php
require_once "../includes/auth_check.php";
require_roles(['supplier','customer']); // ✅ perbaikan di sini
require_once "../config/db.php";
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="container mt-4">
    <h3>Dashboard <?= ucfirst($_SESSION['role']); ?></h3>
    <p class="text-muted">Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>.</p>

    <div class="alert alert-info">
        Anda hanya dapat melihat status transaksi dan barang yang terkait dengan akun Anda.
    </div>

    <div class="text-center">
        <a href="pages/transaksi/transaction_status.php" class="btn btn-outline-primary btn-sm">Lihat Status Transaksi</a>
    </div>
</div>

<?php include "../includes/footer.php"; ?>