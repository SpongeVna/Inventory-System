<?php
require_once "../../includes/auth_check.php";
require_role('admin');
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$message = "";

// Tambah user baru
if (isset($_POST['add_user'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Cek username duplikat
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $message = "<div class='alert alert-warning'>Username sudah digunakan!</div>";
    } else {
        // Tambah ke tabel users
        $insert = mysqli_query($conn, "
            INSERT INTO users (nama_lengkap, username, password, role)
            VALUES ('$nama', '$username', '$password', '$role')
        ");
        $id_user = mysqli_insert_id($conn);

        // Jika role supplier / customer, buat entri otomatis
        if ($role == 'supplier') {
            mysqli_query($conn, "
                INSERT INTO supplier (nama_supplier, kontak, alamat)
                VALUES ('$nama', '', '')
            ");
        } elseif ($role == 'customer') {
            mysqli_query($conn, "
                INSERT INTO customer (nama_customer, kontak, alamat)
                VALUES ('$nama', '', '')
            ");
        }

        $message = $insert
            ? "<div class='alert alert-success'>User berhasil ditambahkan!</div>"
            : "<div class='alert alert-danger'>Gagal menambahkan user.</div>";
    }
}

// Hapus user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user=$id"));

    if ($user) {
        if ($user['role'] == 'supplier') {
            mysqli_query($conn, "DELETE FROM supplier WHERE nama_supplier='" . mysqli_real_escape_string($conn, $user['nama_lengkap']) . "'");
        } elseif ($user['role'] == 'customer') {
            mysqli_query($conn, "DELETE FROM customer WHERE nama_customer='" . mysqli_real_escape_string($conn, $user['nama_lengkap']) . "'");
        }

        mysqli_query($conn, "DELETE FROM users WHERE id_user=$id");
        $message = "<div class='alert alert-success'>User berhasil dihapus.</div>";
    }
}

// Ambil data semua user
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id_user DESC");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Manajemen User</h3>
        <div class="d-flex gap-2">
            <a href="pages/export/export_user_pdf.php" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> 📄 Export PDF
            </a>
            <a href="pages/export/export_user_excel.php" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> 📊 Export Excel
            </a>
        </div>
    </div>

    <?= $message ?>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
        + Tambah User
    </button>

    <table class="table table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($u = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= $u['id_user'] ?></td>
                <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><span class="badge bg-secondary"><?= ucfirst($u['role']) ?></span></td>
                <td>
                    <a href="pages/user/user_edit.php?id=<?= $u['id_user'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $u['id_user'] ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
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
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_user" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<?php include "../../includes/footer.php"; ?>
