<?php
require_once "../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_barang = intval($_POST['id_barang']);
  $jumlah = intval($_POST['jumlah']);

  $update = mysqli_query($conn, "UPDATE barang SET stok = stok - $jumlah WHERE id_barang = $id_barang");

  if ($update) {
    mysqli_query($conn, "INSERT INTO transaksi (id_barang, jumlah, jenis, keterangan) VALUES ($id_barang, $jumlah, 'keluar', 'scan_out')");
    echo "<script>alert('Stok berhasil dikurangi.'); window.location.href='scan_out.php';</script>";
  } else {
    echo "<script>alert('Terjadi kesalahan.'); window.location.href='scan_out.php';</script>";
  }
}
?>