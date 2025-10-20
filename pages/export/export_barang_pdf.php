<?php
require_once "../../config/db.php";
require_once "../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Query data barang
$result = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");

// Buat HTML untuk PDF
$html = '
<h2 style="text-align:center;">Laporan Stok Barang</h2>
<p style="text-align:center;">Generated on '.date('d/m/Y H:i').'</p>
<table border="1" cellspacing="0" cellpadding="6" width="100%">
<thead style="background:#f1f1f1;">
<tr>
  <th>ID</th>
  <th>Nama Barang</th>
  <th>Kategori</th>
  <th>Stok</th>
  <th>Satuan</th>
</tr>
</thead><tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
      <td>'.$row['id_barang'].'</td>
      <td>'.htmlspecialchars($row['nama_barang']).'</td>
      <td>'.htmlspecialchars($row['kategori'] ?? '-').'</td>
      <td>'.$row['stok'].'</td>
      <td>'.htmlspecialchars($row['satuan'] ?? '-').'</td>
    </tr>';
}

$html .= '</tbody></table>';

// Konfigurasi DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("laporan_stok_barang.pdf", ["Attachment" => true]);
exit;
?>