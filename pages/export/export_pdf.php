<?php
require_once "../../config/db.php";
require_once "../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Konfigurasi DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Ambil data transaksi
$query = "
    SELECT t.id_transaksi, b.nama_barang, t.jenis, t.jumlah, t.tanggal_transaksi, 
           u.nama_lengkap, t.keterangan
    FROM transaksi t
    JOIN barang b ON t.id_barang = b.id_barang
    JOIN users u ON t.id_user = u.id_user
    ORDER BY t.tanggal_transaksi DESC
";
$result = mysqli_query($conn, $query);

// Buat HTML untuk PDF
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #333;
        margin: 40px;
    }
    .header {
        text-align: center;
        border-bottom: 3px solid #000;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .header img {
        width: 70px;
        vertical-align: middle;
        margin-right: 10px;
    }
    .header h2 {
        display: inline-block;
        margin: 0;
        vertical-align: middle;
    }
    h3 {
        text-align: center;
        margin-top: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        border: 1px solid #777;
        padding: 6px;
        text-align: center;
    }
    th {
        background-color: #f0f0f0;
    }
    .footer {
        margin-top: 50px;
        text-align: right;
        font-size: 11px;
    }
    .signature {
        margin-top: 60px;
        text-align: right;
        font-size: 12px;
    }
    .signature p {
        margin-bottom: 60px;
    }
</style>
</head>
<body>

<div class="header">
    <img src="../../assets/logo.png" alt="Logo">
    <h2>INVENTORY SYSTEM</h2>
</div>

<h3>Laporan Transaksi Barang</h3>
<p style="text-align:center;">Dicetak pada: ' . date('d/m/Y H:i') . '</p>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nama Barang</th>
    <th>Jenis Transaksi</th>
    <th>Jumlah</th>
    <th>Tanggal</th>
    <th>Dicatat Oleh</th>
    <th>Keterangan</th>
</tr>
</thead>
<tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '
    <tr>
        <td>' . $row['id_transaksi'] . '</td>
        <td>' . htmlspecialchars($row['nama_barang']) . '</td>
        <td>' . ucfirst($row['jenis']) . '</td>
        <td>' . $row['jumlah'] . '</td>
        <td>' . $row['tanggal_transaksi'] . '</td>
        <td>' . htmlspecialchars($row['nama_lengkap']) . '</td>
        <td>' . htmlspecialchars($row['keterangan'] ?: '-') . '</td>
    </tr>';
}

$html .= '
</tbody>
</table>

<div class="signature">
    <p>Disetujui oleh,</p>
    <strong>Admin Gudang</strong>
</div>

<div class="footer">
    Inventory System © ' . date('Y') . ' | PT JOTUN INDONESIA
</div>

</body>
</html>';

// Generate PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF ke browser (langsung download)
$dompdf->stream("laporan_transaksi_" . date('Ymd_His') . ".pdf", ["Attachment" => true]);
exit;
?>