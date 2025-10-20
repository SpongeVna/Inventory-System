<?php
require_once "../../includes/auth_check.php";
require_role('admin');
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// Tambah Customer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_customer'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_customer']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    mysqli_query($conn, "INSERT INTO customer (nama_customer, kontak, alamat) VALUES ('$nama', '$kontak', '$alamat')");
    header("Location: customer.php?msg=added");
    exit;
}

// Hapus Customer
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM customer WHERE id_customer=$id");
    header("Location: customer.php?msg=deleted");
    exit;
}
?>

<div class="container mt-4">
    <h3>Daftar Customer</h3>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= ($_GET['msg'] === 'added') ? 'Customer berhasil ditambahkan.' : 'Customer berhasil dihapus.' ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#formTambah">+ Tambah Customer</button>

    <div id="formTambah" class="collapse mb-4">
        <form method="POST" class="card card-body">
            <input type="hidden" name="tambah_customer" value="1">
            <div class="mb-3">
                <label>Nama Customer</label>
                <input type="text" name="nama_customer" class="form-control" required>
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
                <th>Nama Customer</th>
                <th>Kontak</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM customer ORDER BY id_customer DESC");
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?= $row['id_customer'] ?></td>
                <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                <td><?= htmlspecialchars($row['kontak']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td>
                    <a href="?hapus=<?= $row['id_customer'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus customer ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>