<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";

$message = '';
$generated_barcode = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori'] ?? '');
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan'] ?? '');
    $harga = isset($_POST['harga']) ? floatval($_POST['harga']) : null;
    $stok = isset($_POST['stok']) ? intval($_POST['stok']) : 0;

    // 🔹 Cek duplikasi nama/kode barang
    $cek = mysqli_query($conn, "SELECT * FROM barang WHERE nama_barang='$nama' OR kode_barang='$kode_barang' LIMIT 1");
    if (mysqli_num_rows($cek) > 0) {
        $message = "Barang dengan nama atau kode tersebut sudah terdaftar.";
    } else {
        // Insert data barang
        $stmt = mysqli_prepare($conn, "INSERT INTO barang (kode_barang, nama_barang, kategori, satuan, stok, harga, barcode, tgl_ditambahkan) VALUES (?, ?, ?, ?, ?, ?, '', NOW())");
        mysqli_stmt_bind_param($stmt, "ssssdi", $kode_barang, $nama, $kategori, $satuan, $stok, $harga);
        $exec = mysqli_stmt_execute($stmt);

        if ($exec) {
            $insert_id = mysqli_insert_id($conn);
            $barcode_text = "BRG-" . date('Ymd') . "-" . str_pad($insert_id, 4, "0", STR_PAD_LEFT);

            // Update barcode setelah insert
            $upd = mysqli_prepare($conn, "UPDATE barang SET barcode = ? WHERE id_barang = ?");
            mysqli_stmt_bind_param($upd, "si", $barcode_text, $insert_id);
            mysqli_stmt_execute($upd);

            $message = "Barang berhasil ditambahkan. Barcode: " . $barcode_text;
            $generated_barcode = $barcode_text;
        } else {
            $message = "Gagal menambahkan barang: " . mysqli_error($conn);
        }
    }
}

include "../../includes/header.php";
include "../../includes/sidebar.php";
?>
<h3>Tambah Barang Baru</h3>
<?php if ($message): ?>
<div class="alert <?= (strpos($message, 'berhasil') !== false) ? 'alert-success' : 'alert-danger' ?>">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>
<form method="POST" class="mb-4">
    <div class="mb-3">
        <label class="form-label">Kode Barang</label>
        <input type="text" name="kode_barang" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <input type="text" name="kategori" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Satuan</label>
        <input type="text" name="satuan" class="form-control" placeholder="pcs/box/liter">
    </div>
    <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" step="0.01" name="harga" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Stok Awal</label>
        <input type="number" name="stok" value="0" class="form-control">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
    <a href="pages/master/barang.php" class="btn btn-secondary">Kembali</a>
</form>

<?php if (!empty($generated_barcode)): ?>
<h5>Barcode yang di-generate</h5>
<div><svg id="barcode-gen"></svg></div>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
JsBarcode("#barcode-gen", "<?= addslashes($generated_barcode) ?>", {format: "CODE128", displayValue: true, fontSize: 14});
</script>
<?php endif; ?>

<?php include "../../includes/footer.php"; ?>