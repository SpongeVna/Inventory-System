<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM barang WHERE 
        nama_barang LIKE '%$search%' 
        OR kategori LIKE '%$search%' 
        OR barcode LIKE '%$search%'
        ORDER BY id_barang DESC";
$res = mysqli_query($conn, $sql);
?>

<div class="container mt-4">

  <div class="d-flex justify-content-between mb-3">
      <h3>Daftar Barang</h3>
      <a href="pages/master/barang_add.php" class="btn btn-primary">+ Tambah Barang</a>
  </div>

  <div class="mb-3">
      <a href="pages/export/export_barang_pdf.php" target="_blank" class="btn btn-danger btn-sm">📄 Export PDF</a>
      <a href="pages/export/export_barang_excel.php" class="btn btn-success btn-sm">📊 Export Excel</a>
  </div>

  <form method="GET" class="mb-3">
      <div class="input-group">
          <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Cari nama, kategori, atau barcode">
          <button class="btn btn-outline-secondary" type="submit">Cari</button>
      </div>
  </form>

  <table class="table table-striped">
      <thead>
          <tr>
              <th>ID</th>
              <th>Nama Barang</th>
              <th>Barcode</th>
              <th>Stok</th>
              <th>Harga</th>
              <th>Tgl Ditambahkan</th>
              <th>Aksi</th>
          </tr>
      </thead>
      <tbody>
          <?php while ($row = mysqli_fetch_assoc($res)): ?>
          <tr>
              <td><?= $row['id_barang'] ?></td>
              <td><?= htmlspecialchars($row['nama_barang']) ?></td>
              <td>
                  <?= htmlspecialchars($row['barcode']) ?><br>
                  <svg id="barcode-<?= $row['id_barang'] ?>"></svg>
              </td>
              <td><?= $row['stok'] ?></td>
              <td><?= ($row['harga'] !== null) ? number_format($row['harga'],2,',','.') : '-' ?></td>
              <td><?= $row['tgl_ditambahkan'] ?></td>
              <td>
                  <a href="pages/master/barang_edit.php?id=<?= $row['id_barang'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="pages/master/barang_delete.php?id=<?= $row['id_barang'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus barang ini?')">Hapus</a>
              </td>
          </tr>
          <?php endwhile; ?>
      </tbody>
  </table>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
<?php
mysqli_data_seek($res, 0);
while ($r = mysqli_fetch_assoc($res)) {
    $id = $r['id_barang'];
    $code = addslashes($r['barcode']);
    echo "JsBarcode('#barcode-{$id}', '{$code}', {format: 'CODE128', displayValue: true, fontSize: 12});\n";
}
?>
</script>

<script src="../../assets/js/quagga.min.js"></script>
<script src="../../assets/js/scan_in.js"></script>
<script src="../../assets/js/scan_out.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const tabIn = document.getElementById("scan-in-tab");
  const tabOut = document.getElementById("scan-out-tab");

  // aktifkan kamera pertama kali (default tab aktif)
  if (window.startScanIn) startScanIn();

  // saat pindah ke tab Scan Barang Masuk
  tabIn.addEventListener("click", () => {
    if (window.stopScanOut) stopScanOut();
    if (window.startScanIn) startScanIn();
  });

  // saat pindah ke tab Scan Barang Keluar
  tabOut.addEventListener("click", () => {
    if (window.stopScanIn) stopScanIn();
    if (window.startScanOut) startScanOut();
  });
});
</script>

<?php include "../../includes/footer.php"; ?>
