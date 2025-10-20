<?php
require_once "../../includes/auth_check.php";
require_roles(['admin', 'operator']);
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Scan Barang Keluar</h3>
    <div>
      <label class="form-check-label me-2" for="modeCepat">Mode Cepat (1 item/scan)</label>
      <input class="form-check-input" type="checkbox" id="modeCepat">
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Aktifkan Kamera</h5>
      <p class="text-muted">Arahkan kamera ke barcode barang untuk mulai scan.</p>
      <div id="scanner-container" class="border rounded p-3 mb-3 text-center" style="height: 250px; background: #f8f9fa;">
        <video id="preview" width="100%" height="100%" autoplay muted playsinline></video>
      </div>

      <form id="scanForm" method="POST" action="scan_out_action.php" class="d-none">
        <div class="mb-3">
          <label>Nama Barang</label>
          <input type="text" id="nama_barang" name="nama_barang" class="form-control" readonly>
          <input type="hidden" id="id_barang" name="id_barang">
        </div>

        <div class="mb-3" id="jumlahInput">
          <label>Jumlah Keluar</label>
          <input type="number" id="jumlah" name="jumlah" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Simpan Transaksi</button>
      </form>

      <div id="message" class="mt-3"></div>
    </div>
  </div>
</div>

<script src="/inventory-system/assets/js/quagga.min.js"></script>
<script src="/inventory-system/assets/js/scan_out.js"></script>

<?php include "../../includes/footer.php"; ?>