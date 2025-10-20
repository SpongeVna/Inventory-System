<?php
require_once "../config/db.php";
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['kode']) || !isset($input['tipe'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$kode = mysqli_real_escape_string($conn, $input['kode']);
$tipe = $input['tipe']; // "in" atau "out"

$q = mysqli_query($conn, "SELECT id_barang, stok, nama_barang FROM barang WHERE barcode = '$kode'");
if (mysqli_num_rows($q) === 0) {
    echo json_encode(['success' => false, 'message' => 'Barang tidak ditemukan']);
    exit;
}

$barang = mysqli_fetch_assoc($q);
$id = $barang['id_barang'];
$stokBaru = $barang['stok'] + ($tipe === "in" ? 1 : -1);

if ($stokBaru < 0) $stokBaru = 0;

$update = mysqli_query($conn, "UPDATE barang SET stok = '$stokBaru' WHERE id_barang = '$id'");
if ($update) {
    echo json_encode([
        'success' => true,
        'nama_barang' => $barang['nama_barang'],
        'stok_baru' => $stokBaru
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui stok']);
}