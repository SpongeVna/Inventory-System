<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/db.php";

if (isset($_GET['id'])) {
    $id_barang = intval($_GET['id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_barang);
    mysqli_stmt_execute($stmt);
}

header("Location: barang.php");
exit;