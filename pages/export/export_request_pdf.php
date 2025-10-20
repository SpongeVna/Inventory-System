<?php
require_once "../../config/db.php";
require_once "../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$query = "
SELECT r.*, u.nama_lengkap, b.nama_barang 
FROM request_transaksi r
JOIN users u ON r.id_user = u.id_user
JOIN barang b ON r.id_barang = b.id_barang
ORDER BY r.tanggal_request DESC";
$result = mysqli_query($conn, $query);

$html = '
<h2 style="text-align:center;">Laporan Request Transaksi</h2>
<p style="text-align:center;">Generated on '.date('d/m/Y H:i').'</p>
<table border="1" cellspacing="0" cellpadding="6" width="100%">
<thead style="background:#f1f1f1;">
<tr>
  <th>ID</th>
  <th>Nama User</th>
  <th>Barang</th>
  <th>Jenis</th>
  <th>Jumlah</th>
  <th>Status</th>
  <th>Tanggal Request</th>
</tr>
</thead><tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    $html .= "<tr>
      <td>{$row['id_request']}</td>
      <td>".htmlspecialchars($row['nama_lengkap'])."</td>
      <td>".htmlspecialchars($row['nama_barang'])."</td>
      <td>".ucfirst($row['jenis'])."</td>
      <td>{$row['jumlah']}</td>
      <td>".ucfirst($row['status'])."</td>
      <td>{$row['tanggal_request']}</td>
    </tr>";
}

$html .= '</tbody></table>';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_request_transaksi.pdf", ["Attachment" => true]);
exit;
?>