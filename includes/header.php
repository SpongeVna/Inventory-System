<?php
// inventory-system/includes/header.php
// Header global: berisi tag HTML pembuka, link CSS, dan navbar utama.
// Base href digunakan supaya semua path relatif terhadap root proyek.
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <base href="/inventory-system/">
  <title>Inventory System</title>

  <!-- Bootstrap Offline -->
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-semibold" href="dashboard.php">Inventory System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if (isset($_SESSION['nama_lengkap'])): ?>
          <li class="nav-item">
            <span class="nav-link text-light">
              <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>
              <small class="text-warning">(<?= htmlspecialchars($_SESSION['role']) ?>)</small>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-light" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid py-3">