<?php
session_start();
require_once "config/db.php";

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

// Ambil role user
$role = $_SESSION['role'] ?? null;

if (!$role) {
    // fallback: ambil dari database
    $id_user = $_SESSION['id_user'];
    $query = mysqli_query($conn, "SELECT role FROM users WHERE id_user = '$id_user'");
    $row = mysqli_fetch_assoc($query);
    $role = $row['role'] ?? 'operator';
    $_SESSION['role'] = $role;
}

// Routing dashboard berdasarkan role
switch ($role) {
    case 'admin':
        header("Location: dashboard/admin.php");
        exit;
    case 'operator':
        header("Location: dashboard/operator.php");
        exit;
    case 'supplier':
    case 'customer':
        header("Location: dashboard/supplier.php");
        exit;
    default:
        echo "Role tidak dikenali.";
        exit;
}