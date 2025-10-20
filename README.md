📦 Inventory System — Web-Based Inventory Management
👨‍💻 Project by: Ilyas Ramadhan

Developed for Mata Kuliah Perancangan Sistem Informasi
Universitas Pelita Bangsa — Fakultas Teknik Informatika

🚀 Overview

Inventory System adalah aplikasi web berbasis PHP & MySQL yang dirancang untuk membantu pengelolaan stok barang di gudang atau toko.
Sistem ini mendukung berbagai level pengguna (Admin, Operator, Supplier, dan Customer) dengan fitur berbeda sesuai hak aksesnya.

Semua transaksi — dari input stok hingga permintaan barang — terintegrasi dan tercatat otomatis.

🧩 Core Features
🔐 Multi-Role Authentication

Login berbasis session & role (Admin, Operator, Supplier, Customer)

Setiap user memiliki akses terbatas sesuai peran:

Admin → Mengelola master data, user, dan laporan

Operator → Input barang masuk & keluar

Supplier / Customer → Melihat status transaksi

📊 Dashboard per Role

Admin Dashboard: Statistik barang, user, dan transaksi

Operator Dashboard: Riwayat dan input transaksi cepat

Supplier/Customer Dashboard: Status permintaan barang

📦 Master Data Management

CRUD Data Barang lengkap dengan barcode otomatis

Kelola data Supplier dan Customer

Manajemen user hanya oleh Admin

🔁 Barang Masuk & Keluar

Transaksi otomatis update stok di tabel barang

Catatan transaksi tersimpan di tabel transaksi

Fitur validasi stok (tidak bisa minus)

🧾 Export Laporan

Export data ke PDF & Excel untuk:

Barang (stok)

User

Request / Transaksi

Menggunakan library resmi (dompdf & PhpSpreadsheet)

📸 Barcode Scanner (Real-Time)

Scan Barang Masuk & Keluar langsung dari kamera laptop / HP

Menggunakan QuaggaJS (offline) untuk mendeteksi barcode

Kamera otomatis aktif sesuai mode (Masuk / Keluar)

Update stok otomatis ke database saat barcode terbaca

Dilengkapi efek beep sound setiap scan sukses 🔊

🏗️ Tech Stack
Komponen	Teknologi
Backend	PHP (Native)
Frontend	HTML, CSS, Bootstrap 5
Database	MySQL
Barcode Scanner	QuaggaJS (Offline)
PDF Export	DomPDF
Excel Export	PhpSpreadsheet
Auth	PHP Session
Sound Feedback	HTML5 Audio API
📁 Folder Structure
inventory-system/
│
├── api/
│   ├── get_barang.php
│   └── update_stok.php
│
├── assets/
│   ├── js/
│   │   ├── quagga.min.js
│   │   ├── scan_in.js
│   │   └── scan_out.js
│   ├── sound/
│   │   └── beep.mp3
│   └── style.css
│
├── config/
│   └── db.php
│
├── includes/
│   ├── header.php
│   ├── sidebar.php
│   ├── footer.php
│   └── auth_check.php
│
├── pages/
│   ├── master/
│   │   ├── barang.php
│   │   ├── barang_add.php
│   │   ├── barang_edit.php
│   │   └── barang_delete.php
│   ├── transaksi/
│   │   ├── barang_masuk.php
│   │   ├── barang_keluar.php
│   │   ├── scan_in.php
│   │   └── scan_out.php
│   └── export/
│       ├── export_barang_pdf.php
│       ├── export_barang_excel.php
│       ├── export_user_pdf.php
│       └── export_user_excel.php
│
└── login.php

⚙️ Installation

Clone repository:

git clone https://github.com/username/inventory-system.git


Pindahkan ke folder htdocs XAMPP:

C:\xampp\htdocs\inventory-system


Import database:

File SQL: db_inventory.sql

Jalankan di browser:

http://localhost/inventory-system/login.php

🧠 Developer Notes

Pastikan vendor/ sudah terinstall (composer).

Untuk versi offline, semua JS & CSS sudah di-embed lokal (tanpa CDN).

File quagga.min.js dan beep.mp3 sudah siap digunakan di folder assets/.

💡 Future Improvements

Real-time update dashboard dengan AJAX

Integrasi API eksternal untuk laporan

Fitur batch scanning dan multi-user transaction logging
