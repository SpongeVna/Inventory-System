<?php
require_once "../../includes/auth_check.php";
require_role('admin');
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// Tambah Supplier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_supplier'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    mysqli_query($conn, "INSERT INTO supplier (nama_supplier, kontak, alamat) VALUES ('$nama', '$kontak', '$alamat')");
    header("Location: supplier.php?msg=added");
    exit;
}

// Hapus Supplier
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM supplier WHERE id_supplier=$id");
    header("Location: supplier.php?msg=deleted");
    exit;
}
?>

<div class="container mt-4">
    <h3>Daftar Supplier</h3>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= ($_GET['msg'] === 'added') ? 'Supplier berhasil ditambahkan.' : 'Supplier berhasil dihapus.' ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#formTambah">+ Tambah Supplier</button>

    <div id="formTambah" class="collapse mb-4">
        <form method="POST" class="card card-body">
            <input type="hidden" name="tambah_supplier" value="1">
            <div class="mb-3">
                <label>Nama Supplier</label>
                <input type="text" name="nama_supplier" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Kontak</label>
                <input type="text" name="kontak" class="form-control">
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Supplier</th>
                <th>Kontak</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM supplier ORDER BY id_supplier DESC");
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?= $row['id_supplier'] ?></td>
                <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
                <td><?= htmlspecialchars($row['kontak']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td>
                    <a href="?hapus=<?= $row['id_supplier'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus supplier ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>