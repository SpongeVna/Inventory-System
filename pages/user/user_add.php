<?php
require_once "../../includes/auth_check.php";
require_role('admin');
require_once "../../config/db.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim(mysqli_real_escape_string($conn, $_POST['nama_lengkap']));
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Cek username sudah ada atau belum
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        $message = "Username sudah digunakan.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama', '$username', '$password', '$role')");
        if ($insert) {
            header("Location: user.php");
            exit;
        } else {
            $message = "Gagal menambahkan user: " . mysqli_error($conn);
        }
    }
}

include "../../includes/header.php";
include "../../includes/sidebar.php";
?>

<div class="container mt-4">
    <h3>Tambah User Baru</h3>

    <?php if ($message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="operator">Operator</option>
                <option value="supplier">Supplier</option>
                <option value="customer">Customer</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="pages/user/user.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>