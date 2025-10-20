<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Cek login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

// Fungsi untuk membatasi role tertentu
function require_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: ../login.php");
        exit;
    }
}

// Fungsi untuk multi-role
function require_roles($roles = []) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        header("Location: ../login.php");
        exit;
    }
}
?>