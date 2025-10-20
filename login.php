<?php
session_start();
require_once 'config/db.php';

$message = '';

// Jika user sudah login, langsung arahkan ke dashboard router
if (isset($_SESSION['id_user'])) {
    header("Location: dashboard.php");
    exit;
}

// Proses form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim($_POST['password']);

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        $message = "Username dan password wajib diisi.";
    } else {
        // Cek user di database
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");
        
        if ($query && mysqli_num_rows($query) === 1) {
            $user = mysqli_fetch_assoc($query);

            // Verifikasi password hash
            if (password_verify($password, $user['password'])) {
                // Set session user
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

                // Arahkan ke dashboard router
                header("Location: dashboard.php");
                exit;
            } else {
                $message = "Password salah.";
            }
        } else {
            $message = "Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Storage Inventory</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            background-color: #f5f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            width: 360px;
            margin: 8% auto;
            background: #fff;
            padding: 30px 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            color: #444;
        }
        form input {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }
        form button {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        form button:hover {
            background: #0056b3;
        }
        .alert {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login Sistem Inventory</h2>

        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" autocomplete="off" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>