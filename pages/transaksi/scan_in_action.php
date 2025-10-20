<?php
require_once "../../includes/auth_check.php";
require_roles(['admin', 'operator']);
require_once "../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = intval($_POST['id_barang']);
    $jumlah = intval($_POST['jumlah']);
    $id_user = $_SESSION['id_user'];

    if ($id_barang > 0 && $jumlah > 0) {
        // Tambah stok
        mysqli_query($conn, "UPDATE barang SET stok = stok + $jumlah WHERE id_barang = $id_barang");

        // Catat transaksi masuk
        mysqli_query($conn, "
            INSERT INTO transaksi (id_barang, id_user, jumlah, jenis, tanggal_transaksi, keterangan)
            VALUES ($id_barang, $id_user, $jumlah, 'masuk', NOW(), 'Scan barcode (auto)')
        ");

        echo "<script>
            alert('Barang masuk berhasil ditambahkan!');
            window.location.href='scan_in.php';
        </script>";
    } else {
        echo "<script>
            alert('Data tidak valid!');
            window.location.href='scan_in.php';
        </script>";
    }
}
?>