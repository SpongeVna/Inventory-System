<?php
require_once "../../config/db.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_request_transaksi.xls");

$query = "
SELECT r.*, u.nama_lengkap, b.nama_barang 
FROM request_transaksi r
JOIN users u ON r.id_user = u.id_user
JOIN barang b ON r.id_barang = b.id_barang
ORDER BY r.tanggal_request DESC";
$result = mysqli_query($conn, $query);

echo "<table border='1'>
<tr>
  <th>ID</th>
  <th>Nama User</th>
  <th>Barang</th>
  <th>Jenis</th>
  <th>Jumlah</th>
  <th>Status</th>
  <th>Tanggal Request</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
      <td>{$row['id_request']}</td>
      <td>".htmlspecialchars($row['nama_lengkap'])."</td>
      <td>".htmlspecialchars($row['nama_barang'])."</td>
      <td>".ucfirst($row['jenis'])."</td>
      <td>{$row['jumlah']}</td>
      <td>".ucfirst($row['status'])."</td>
      <td>{$row['tanggal_request']}</td>
    </tr>";
}
echo "</table>";
exit;
?>