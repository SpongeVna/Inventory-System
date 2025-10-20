<?php
require_once "../../config/db.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_user.xls");

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY role, nama_lengkap ASC");

echo "<table border='1'>
<tr>
  <th>ID</th>
  <th>Nama Lengkap</th>
  <th>Username</th>
  <th>Role</th>
  <th>Status</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $status = ($row['is_active'] ?? 1) ? 'Aktif' : 'Nonaktif';
    echo "<tr>
      <td>{$row['id_user']}</td>
      <td>".htmlspecialchars($row['nama_lengkap'])."</td>
      <td>".htmlspecialchars($row['username'])."</td>
      <td>".ucfirst($row['role'])."</td>
      <td>{$status}</td>
    </tr>";
}
echo "</table>";
exit;
?>