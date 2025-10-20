<?php
require_once "../../includes/auth_check.php";
require_roles(['admin']);
require_once "../../config/db.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_transaksi_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<h3>Laporan Transaksi Inventori</h3>";
echo "<p>Dibuat pada: " . date('d/m/Y H:i:s') . "</p>";

echo "<table border='1'>
<tr style='background-color:#f2f2f2;'>
    <th>ID</th>
    <th>Nama Barang</th>
    <th>Jenis Transaksi</th>
    <th>Jumlah</th>
    <th>Tanggal</th>
    <th>Dicatat Oleh</th>
    <th>Keterangan</th>
</tr>";

$query = mysqli_query($conn, "
    SELECT t.*, b.nama_barang, u.nama_lengkap 
    FROM transaksi t
    JOIN barang b ON t.id_barang = b.id_barang
    JOIN users u ON t.id_user = u.id_user
    ORDER BY t.tanggal_transaksi DESC
");

while ($row = mysqli_fetch_assoc($query)) {
    echo "<tr>
        <td>{$row['id_transaksi']}</td>
        <td>".htmlspecialchars($row['nama_barang'])."</td>
        <td>".ucfirst($row['jenis'])."</td>
        <td>{$row['jumlah']}</td>
        <td>{$row['tanggal_transaksi']}</td>
        <td>".htmlspecialchars($row['nama_lengkap'])."</td>
        <td>".htmlspecialchars($row['keterangan'] ?: '-')."</td>
    </tr>";
}

echo "</table>";
exit;
?>