<?php
// inventory-system/api/get_barang.php
header('Content-Type: application/json');
require_once "../config/db.php";

// --- Validasi parameter ---
if (!isset($_GET['kode']) || trim($_GET['kode']) === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Kode barcode tidak dikirim.'
    ]);
    exit;
}

$kode = mysqli_real_escape_string($conn, $_GET['kode']);

// --- Cek apakah barang dengan kode tersebut ada ---
$query = "SELECT id_barang, nama_barang, stok FROM barang WHERE kode_barang = '$kode' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Query gagal dieksekusi: ' . mysqli_error($conn)
    ]);
    exit;
}

// --- Jika data ditemukan ---
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode([
        'success' => true,
        'id_barang' => $row['id_barang'],
        'nama_barang' => $row['nama_barang'],
        'stok' => $row['stok']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Barang tidak ditemukan di database.'
    ]);
}

mysqli_close($conn);
?>