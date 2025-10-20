<?php
require_once "../../includes/auth_check.php";
require_role('admin');
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

if (!isset($_GET['id'])) {
    header("Location: user.php");
    exit;
}

$id = intval($_GET['id']);
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user=$id"));

if (!$user) {
    echo "<div class='alert alert-danger'>User tidak ditemukan.</div>";
    include "../../includes/footer.php";
    exit;
}

$message = "";

// Proses update
if (isset($_POST['update_user'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    // Update tabel users
    $update = mysqli_query($conn, "
        UPDATE users 
        SET nama_lengkap='$nama', username='$username', password='$password', role='$role'
        WHERE id_user=$id
    ");

    if ($update) {
        // Sinkronisasi dengan tabel supplier/customer
        if ($role == 'supplier') {
            // Pastikan ada di tabel supplier
            $cek = mysqli_query($conn, "SELECT * FROM supplier WHERE nama_supplier='$nama'");
            if (mysqli_num_rows($cek) == 0) {
                mysqli_query($conn, "INSERT INTO supplier (nama_supplier, kontak, alamat) VALUES ('$nama', '', '')");
            }
            // Hapus jika sebelumnya customer
            mysqli_query($conn, "DELETE FROM customer WHERE nama_customer='$nama'");
        } elseif ($role == 'customer') {
            $cek = mysqli_query($conn, "SELECT * FROM customer WHERE nama_customer='$nama'");
            if (mysqli_num_rows($cek) == 0) {
                mysqli_query($conn, "INSERT INTO customer (nama_customer, kontak, alamat) VALUES ('$nama', '', '')");
            }
            mysqli_query($conn, "DELETE FROM supplier WHERE nama_supplier='$nama'");
        } else {
            // Jika admin/operator, hapus dari dua tabel
            mysqli_query($conn, "DELETE FROM supplier WHERE nama_supplier='$nama'");
            mysqli_query($conn, "DELETE FROM customer WHERE nama_customer='$nama'");
        }

        $message = "<div class='alert alert-success'>User berhasil diperbarui.</div>";
        // Refresh data
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user=$id"));
    } else {
        $message = "<div class='alert alert-danger'>Gagal memperbarui user.</div>";
    }
}
?>

<div class="container mt-4">
    <h3>Edit User</h3>
    <?= $message ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Password (kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                <option value="operator" <?= $user['role']=='operator'?'selected':'' ?>>Operator</option>
                <option value="supplier" <?= $user['role']=='supplier'?'selected':'' ?>>Supplier</option>
                <option value="customer" <?= $user['role']=='customer'?'selected':'' ?>>Customer</option>
            </select>
        </div>

        <button type="submit" name="update_user" class="btn btn-primary">Simpan Perubahan</button>
        <a href="pages/user/user.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>