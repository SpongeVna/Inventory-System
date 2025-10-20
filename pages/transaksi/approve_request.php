<?php
require_once "../../includes/auth_check.php";
require_roles(['admin', 'operator']);
require_once "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$message = "";

// --- PROSES SETUJUI ---
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM request_transaksi WHERE id_request = $id"));

    if ($req && $req['status'] === 'pending') {
        $id_user = $_SESSION['id_user'];
        $id_barang = intval($req['id_barang']);
        $jumlah = intval($req['jumlah']);
        $jenis = $req['jenis'];
        $keterangan = mysqli_real_escape_string($conn, $req['keterangan']);

        // Update stok barang
        if ($jenis === 'masuk') {
            mysqli_query($conn, "UPDATE barang SET stok = stok + $jumlah WHERE id_barang = $id_barang");
        } else {
            mysqli_query($conn, "UPDATE barang SET stok = stok - $jumlah WHERE id_barang = $id_barang");
        }

        // Catat transaksi baru
        mysqli_query($conn, "
            INSERT INTO transaksi (id_barang, id_user, jumlah, jenis, keterangan, tanggal_transaksi)
            VALUES ($id_barang, $id_user, $jumlah, '$jenis', '$keterangan', NOW())
        ");

        // Update status request
        mysqli_query($conn, "
            UPDATE request_transaksi
            SET status='disetujui', tanggal_respon=NOW()
            WHERE id_request=$id
        ");

        $message = "Request ID #$id berhasil disetujui.";
    }
    header("Location: " . basename(__FILE__) . "?msg=approved");
    exit;
}

// --- PROSES TOLAK ---
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM request_transaksi WHERE id_request = $id"));

    if ($req && $req['status'] === 'pending') {
        mysqli_query($conn, "
            UPDATE request_transaksi
            SET status='ditolak', tanggal_respon=NOW()
            WHERE id_request=$id
        ");
        $message = "Request ID #$id ditolak.";
    }
    header("Location: " . basename(__FILE__) . "?msg=rejected");
    exit;
}
?>

<div class="container mt-4">
    <h3>Persetujuan Request Transaksi</h3>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-<?= ($_GET['msg'] === 'approved' ? 'success' : 'danger') ?> alert-dismissible fade show mt-3" role="alert">
            <?= $_GET['msg'] === 'approved' ? 'Request berhasil disetujui ✅' : 'Request berhasil ditolak ❌'; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama User</th>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "
                SELECT r.*, u.nama_lengkap, b.nama_barang
                FROM request_transaksi r
                JOIN users u ON r.id_user = u.id_user
                JOIN barang b ON r.id_barang = b.id_barang
                ORDER BY r.tanggal_request DESC
            ");
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?= $row['id_request'] ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= ucfirst($row['jenis']) ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>
                    <?php
                    if ($row['status'] == 'pending') echo "<span class='badge bg-warning text-dark'>Pending</span>";
                    elseif ($row['status'] == 'disetujui') echo "<span class='badge bg-success'>Disetujui</span>";
                    else echo "<span class='badge bg-danger'>Ditolak</span>";
                    ?>
                </td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <a href="pages/transaksi/approve_request.php?approve=<?= $row['id_request'] ?>" class="btn btn-success btn-sm">Setujui</a>
                        <a href="pages/transaksi/approve_request.php?reject=<?= $row['id_request'] ?>" class="btn btn-danger btn-sm">Tolak</a>
                    <?php else: ?>
                        <em>-</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>