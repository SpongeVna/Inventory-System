<?php
// inventory-system/includes/sidebar.php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<div class="row">
  <div class="col-md-2 bg-light vh-100 p-3 shadow-sm">
    <h6 class="fw-bold mb-3 text-primary">Menu Utama</h6>
    <ul class="nav flex-column">
      <!-- Dashboard -->
      <li class="nav-item mb-1">
        <a class="nav-link" href="dashboard.php">🏠 Dashboard</a>
      </li>

      <!-- Admin Menu -->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/master/barang.php">📦 Master Barang</a></li>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/laporan.php">📊 Laporan</a></li>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/relasi/supplier.php">🏭 Supplier</a></li>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/relasi/customer.php">👥 Customer</a></li>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/user/user.php">👤 Manajemen User</a></li>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/scan_in.php">📷 Scan Barang Masuk</a></li>        
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/scan_out.php">📷 Scan Barang Keluar</a></li>
        <!-- 🔔 Tambahan: menu persetujuan request -->
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/approve_request.php">✅ Persetujuan Request</a></li>
      <?php endif; ?>

      <!-- Operator Menu -->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'operator'): ?>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/barang_masuk.php">⬇️ Barang Masuk</a></li>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/barang_keluar.php">⬆️ Barang Keluar</a></li>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/master/barang.php">📋 Lihat Stok</a></li>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/scan_in.php">📷 Scan Barang Masuk</a></li>        
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/scan_out.php">📷 Scan Barang Keluar</a></li>
        <!-- 🔔 Tambahan: menu persetujuan request (operator juga bisa setujui) -->
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/approve_request.php">✅ Persetujuan Request</a></li>
      <?php endif; ?>

      <!-- Supplier / Customer Menu -->
      <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['supplier','customer'])): ?>
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/transaction_status.php">📦 Status Transaksi</a></li>
        <!-- 🆕 Tambahan: menu untuk request transaksi -->
        <li class="nav-item mb-1"><a class="nav-link" href="pages/transaksi/request_transaksi.php">📝 Request Barang</a></li>
      <?php endif; ?>

      <!-- Logout -->
      <li class="nav-item mt-3">
        <a class="nav-link text-danger fw-bold" href="logout.php">🚪 Logout</a>
      </li>
    </ul>
  </div>

  <!-- Main content -->
  <div class="col-md-10 p-4">