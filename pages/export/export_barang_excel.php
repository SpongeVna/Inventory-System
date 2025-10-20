<?php
require_once "../../config/db.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_stok_barang.xls");

$result = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");

echo "<table border='1'>
<tr>
  <th>ID</th>
  <th>Nama Barang</th>
  <th>Kategori</th>
  <th>Stok</th>
  <th>Satuan</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
      <td>{$row['id_barang']}</td>
      <td>".htmlspecialchars($row['nama_barang'])."</td>
      <td>".htmlspecialchars($row['kategori'] ?? '-')."</td>
      <td>{$row['stok']}</td>
      <td>".htmlspecialchars($row['satuan'] ?? '-')."</td>
    </tr>";
}
echo "</table>";
exit;
?>