-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 12:17 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `harga` decimal(10,2) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `tgl_ditambahkan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `kode_barang`, `nama_barang`, `kategori`, `satuan`, `stok`, `harga`, `barcode`, `tgl_ditambahkan`) VALUES
(1, 'JTN000001', 'T-SHIRT PINGUIN ABU', 'Souvenir', 'pcs', 1650, 62000.00, 'BRG-20251008-0001', '2025-10-08 18:21:47'),
(2, 'JTN000002', 'T-SHIRT PINGUIN BIRU', 'Souvenir', 'pcs', 1800, 62000.00, 'BRG-20251008-0002', '2025-10-08 18:25:30'),
(3, 'JTN000003', 'T-SHIRT PINGUIN HITAM', 'Souvenir', 'pcs', 2350, 62000.00, 'BRG-20251008-0005', '2025-10-08 18:35:16'),
(4, 'JTN000004', 'POLO SHIRT DEALER VISIT', 'Souvenir', 'pcs', 710, 75000.00, 'BRG-20251008-0006', '2025-10-08 18:40:22'),
(5, 'JTN000005', 'TOPI BIRU DONKER', 'Souvenir', 'pcs', 150, 21000.00, 'BRG-20251009-0009', '2025-10-09 07:49:56'),
(6, 'JTN000006', 'KAOS TUKANG LK-180', 'Souvenir', 'pcs', 600, 65000.00, 'BRG-20251013-0010', '2025-10-13 04:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `id_user`, `nama_customer`, `kontak`, `alamat`, `created_at`) VALUES
(1, NULL, 'PT SAVANA RIZKI ABADI', 'Mizzu Fauzil Adhim', 'Jl. H. Samali No.87, RT.19/RW.1, Pejaten Barat, Ps. Minggu, Kota Jakart Selatan, Daerah Khusus Ibu kota Jakarta 12510', '2025-10-13 03:58:34'),
(2, NULL, 'PT MARI PRODUKSI IDE', 'Hendra Hermawan', 'Komplek Perkantoran Selmis Block C No 52 C, Jl. Asem Baris Raya, Kebon Baru tebet, Jakarta Selatan - 12830', '2025-10-13 03:59:30'),
(3, NULL, 'PT NAKU LOGISTIC INDONESIA (KUEHNE + NAGEL INDONESIA)', 'Imam Permana', 'Blok C-1 Jl. Sumatera Blok C-1 kawasan, MM2100 - Ejip Bridge, Gandasari, Kec. Cikarang Bar., Kabupaten Bekasi, Jawa Barat 17520', '2025-10-13 04:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `request_transaksi`
--

CREATE TABLE `request_transaksi` (
  `id_request` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `tanggal_request` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_respon` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_transaksi`
--

INSERT INTO `request_transaksi` (`id_request`, `id_user`, `id_barang`, `jumlah`, `jenis`, `keterangan`, `status`, `tanggal_request`, `tanggal_respon`) VALUES
(1, 5, 3, 150, 'masuk', 'Untuk Event Grand Opening Toko Di Daerah BSD', 'disetujui', '2025-10-09 10:41:45', '2025-10-09 11:01:22'),
(2, 7, 1, 150, 'keluar', 'Untuk Crew Pengecatan Jembatan di Jakarta', 'disetujui', '2025-10-09 10:44:12', '2025-10-09 11:01:19'),
(3, 5, 4, 10, 'masuk', 'Untuk kebutuhan proyek di daerah BSD', 'disetujui', '2025-10-14 06:44:52', '2025-10-14 06:47:16'),
(4, 7, 6, 60, 'keluar', 'Untuk Crew proyek pembangunan sekolah', 'ditolak', '2025-10-14 06:45:52', '2025-10-14 06:47:14');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `id_user`, `nama_supplier`, `kontak`, `alamat`, `created_at`) VALUES
(1, NULL, 'PT SOLID PRO SUKSES', 'Rizki Aprillia Rasmana', 'Ruko Daan Mogot Baru, Blok KJ-i / 06, Jl. Tampak Siring, Kalideres, Jakarta Barat 11840', '2025-10-13 03:53:40'),
(2, NULL, 'PT CERIA CREATIVE INDONESIA', 'Nurfiki Faturohman', 'Jl. Mangga Dua Raya No. 2F, Jakarta, Indonesia 11110', '2025-10-13 03:54:32'),
(3, NULL, 'PT SINAR KARYA MITRA PARIWARA', 'Muhammad Rifqi Bayu Sasongko', 'Jl Raya Puspitek Kodiklat TNI Rt.002/Rw. 003 No 1, Kota Tangerang Selatan, Banten 15316', '2025-10-13 03:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal_transaksi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_barang`, `id_user`, `jumlah`, `jenis`, `keterangan`, `tanggal_transaksi`) VALUES
(1, 3, NULL, 400, 'masuk', '', '2025-10-09 17:09:20'),
(2, 5, NULL, 200, 'keluar', '', '2025-10-09 17:09:34'),
(3, 5, NULL, 50, 'masuk', '', '2025-10-09 17:09:53'),
(4, 1, 2, 150, 'keluar', 'Untuk Crew Pengecatan Jembatan di Jakarta', '2025-10-09 18:01:19'),
(5, 3, 2, 150, 'masuk', 'Untuk Event Grand Opening Toko Di Daerah BSD', '2025-10-09 18:01:22'),
(6, 4, 10, 10, 'masuk', 'Untuk kebutuhan proyek di daerah BSD', '2025-10-14 13:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','operator','supplier','customer') NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `nama_lengkap`) VALUES
(1, 'admin', '$2y$10$u5jEW1LXo.umYe3mQkDjReQc31pMiSO4if/LR8ORDQZMudTdUiIW2', 'admin', 'Administrator'),
(2, 'admin1', '$2y$10$9SrKw7KevbD6MQH7E68E4.5LbWJSm2ZfptgqumsRKrz63IF/GXJoe', 'admin', 'Ilyas Ramadhan'),
(3, 'operator1', '$2y$10$HxD18BIIiY.EkSQMEMgoNekDVFbQ0DdMd6pTJV5ba/LSOqCuPgFle', 'operator', 'Novitasari'),
(4, 'operator2', '$2y$10$k7JHGRLiKsyyp5OyJNb.4eSERqbGNYQEn/ANcfKujZ4OVZ6P.kX0K', 'operator', 'Imam Permana'),
(5, 'supplier1', '$2y$10$Y4cGvTX.ireD50B5gR73Xu/ch6SWpbu2RvtMyZUKQeoj8SsqO7arG', 'supplier', 'Rizki Aprillia Rasmana'),
(6, 'supplier2', '$2y$10$IqppV9IlRhdHG4HdiTKDhe1PRzSsNSybJLePgQrYH89dM891t24Hy', 'supplier', 'Nurfiki Faturohman'),
(7, 'customer1', '$2y$10$RiSw7jdXNH5cKkzyupD0leOkPq4jI4JB0ESP6zMlp5UB.tuxA0ZOa', 'customer', 'Mizzu Fauzil Adhim'),
(8, 'customer2', '$2y$10$a7qkvYp9Iv8bAwbN/Au5Fe0X1Nvcz/HQho.0/7Zh2.egNBYh1T4yC', 'customer', 'Iman Safari'),
(9, 'admin2', '$2y$10$o.0EF4cLBhyN9uqtk9wxQ./UsBCMs0Tq.QHJH2U9e.HcvZNUpdRIO', 'admin', 'Mei Rahmawati'),
(10, 'admin3', '$2y$10$BHR.pMkbKtDhtJ/o56T1t.KQBy4b0vVDr7YRRQ1hUF3tzdpVVweRm', 'admin', 'Simfrosa Gradiani'),
(11, 'supplier', '$2y$10$jWkBoSLDN1KpPtO82Wd0m.9AhRoxIF.//czx.nZy2PIFMrk3xeFTC', 'supplier', 'Muhammad Rifqi Bayu Sasongko'),
(12, 'customer3', '$2y$10$m0lRXao18P9LOrYshb2lieCr0M8M5wJISJXDzWzwSiuCAE8D.hUAi', 'customer', 'Hendra Hermawan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`),
  ADD KEY `fk_customer_user` (`id_user`);

--
-- Indexes for table `request_transaksi`
--
ALTER TABLE `request_transaksi`
  ADD PRIMARY KEY (`id_request`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`),
  ADD KEY `fk_supplier_user` (`id_user`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `request_transaksi`
--
ALTER TABLE `request_transaksi`
  MODIFY `id_request` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `fk_supplier_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
