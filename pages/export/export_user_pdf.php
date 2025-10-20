<?php
require_once "../../config/db.php";
require_once "../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY role, nama_lengkap ASC");

$html = '
<h2 style="text-align:center;">Laporan Data User</h2>
<p style="text-align:center;">Generated on '.date('d/m/Y H:i').'</p>
<table border="1" cellspacing="0" cellpadding="6" width="100%">
<thead style="background:#f1f1f1;">
<tr>
  <th>ID</th>
  <th>Nama Lengkap</th>
  <th>Username</th>
  <th>Role</th>
  <th>Status</th>
</tr>
</thead><tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    $status = ($row['is_active'] ?? 1) ? 'Aktif' : 'Nonaktif';
    $html .= "<tr>
      <td>{$row['id_user']}</td>
      <td>".htmlspecialchars($row['nama_lengkap'])."</td>
      <td>".htmlspecialchars($row['username'])."</td>
      <td>".ucfirst($row['role'])."</td>
      <td>{$status}</td>
    </tr>";
}

$html .= '</tbody></table>';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("laporan_user.pdf", ["Attachment" => true]);
exit;
?>