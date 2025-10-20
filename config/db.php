<?php
// config/db.php

$host = "localhost";
$user = "root";       // ganti jika kamu pakai user MySQL lain
$pass = "";           // isi password MySQL kamu jika ada
$db   = "db_inventory";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>