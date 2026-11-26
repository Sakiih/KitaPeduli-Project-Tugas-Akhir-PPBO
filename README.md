# KitaPeduli: Platform Crowdfunding & Donasi Sosial (Laravel 13)

**KitaPeduli** adalah aplikasi web crowdfunding dan donasi sosial yang dibangun menggunakan **Laravel 13**, **MySQL**, **Laravel Blade**, **Tailwind CSS v4 (Vite)**, dan **Laravel Sanctum REST API**. Proyek ini dikembangkan untuk memenuhi Tugas Akhir mata kuliah **Pemrograman Berorientasi Objek (PPBO)** dengan menerapkan empat pilar PBO (**Abstraction, Inheritance, Encapsulation, Polymorphism**) serta prinsip **SOLID** secara nyata.

> **Catatan Penting**: Seluruh fitur pembayaran pada aplikasi ini (QRIS maupun Transfer Bank/ATM) menggunakan **Simulasi Mandiri (SIMULASI / DEMO: Bukan pembayaran nyata)** untuk keperluan demonstrasi akademik.

---

## 1. Fitur Utama

1. **Authentication & Role-Based Authorization**:
   - Dua role akun pada tabel `users`: **Admin (`admin`)** dan **Donatur Terdaftar (`donor`)**.
   - **Guest (Pengunjung Tanpa Login)** dapat menjelajahi kampanye dan berdonasi langsung tanpa perlu membuat akun (`donations.user_id = NULL`).
2. **Homepage & Penjelajahan Kampanye**:
   - Hero kampanye unggulan, pencarian kata kunci, filter kategori, pengurutan (*Terbaru, Segera Berakhir, Dana Terbanyak*), pagination, dan rekomendasi kampanye.
3. **Detail Kampanye & 8 Komponen Transparansi**:
   - (1) Cerita Penggalangan Dana, (2) Kabar Terbaru, (3) Pencairan Dana / Laporan Penyaluran, (4) Informasi Pengelola Kampanye, (5) Ringkasan Donasi & Statistik Dana, (6) Doa-doa Orang Baik (sekaligus daftar donatur publik dari donasi `success`), (7) Pedoman Kebaikan, dan (8) FAQ.
4. **Donasi Terdaftar & Guest Donation**:
   - Mendukung pilihan nominal cepat, pesan/doa opsional, opsi **Donasi Anonim** (`is_anonymous = true` menampilkan *"Orang Baik (Anonim)"* di publik), serta proteksi token akses transaksi (`X-Transaction-Token` / session).
5. **Simulasi Pembayaran Berbasis PBO & SOLID**:
   - Mendukung **QRIS Simulasi** (`QrisPayment`) dan **Transfer Bank / ATM Simulasi** (`BankTransferPayment`) yang mewarisi abstract class `PaymentMethod`.
   - Konfirmasi pembayaran dilakukan secara atomik (`DB::transaction` + `lockForUpdate`) dengan proteksi anti *double-submit*.
6. **Reaksi Aamiin pada Doa-doa Orang Baik**:
   - Guest dapat membaca doa dan melihat jumlah Aamiin; Donatur Terdaftar dapat memberikan maksimal 1 Aamiin pada setiap pesan donasi.
7. **Dashboard Donatur & Panel Admin**:
   - Donatur Terdaftar memiliki Dashboard, Riwayat Donasi Pribadi, Detail Transaksi, dan Pengelolaan Profil.
   - Admin memiliki Dashboard Statistik, CRUD Kampanye (dengan proteksi pengarsipan otomatis bagi kampanye yang telah memiliki transaksi), CRUD Kategori, CRUD Kabar Terbaru, CRUD Laporan Penyaluran Dana, Pemantauan Transaksi, Manajemen Pengguna, dan Laporan Donasi.
8. **REST API Lengkap (38 Endpoint)**:
   - Seluruh endpoint `/api/*` mengembalikan JSON dan berbagi *Service Layer* yang sama dengan antarmuka web.

---

## 2. Teknologi & Prasyarat

- **PHP**: `^8.3` (Diuji pada PHP `8.3.30`)
- **Framework**: Laravel `13.x` (`v13.43.0`)
- **Database**: MySQL `8.4.3` (Database: `kitapeduli`, tersedia juga dump SQL `kitapeduli.sql` untuk HeidiSQL)
- **Autentikasi API**: Laravel Sanctum `^4.5`
- **Node.js & NPM**: Node.js `v22.22.0`, NPM `10.9.4`, & Vite `v7.3.8`
- **Server Lokal**: Laragon (Windows)

---

## 3. Langkah Instalasi & Menjalankan Aplikasi (Windows / Laragon)

### 3.1 Persiapan File Konfigurasi `.env`
Jika file `.env` belum tersedia, salin dari `.env.example` dan buat *application key*:

```powershell
copy .env.example .env
php artisan key:generate
```

Pastikan konfigurasi database pada `.env` mengarah ke MySQL Laragon:

```env
APP_NAME=KitaPeduli
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kitapeduli
DB_USERNAME=root
DB_PASSWORD=
```

### 3.2 Buat Database MySQL, Jalankan Migration, dan Seeder
Pastikan layanan **MySQL** pada Laragon sudah aktif, lalu buat database `kitapeduli` dan jalankan migrasi beserta data demonstrasi:

```powershell
mysql -u root -e "CREATE DATABASE IF NOT EXISTS kitapeduli CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate:fresh --seed
php artisan storage:link
```

*(Sebagai alternatif di HeidiSQL, Anda juga dapat langsung mengimpor file [`kitapeduli.sql`](kitapeduli.sql) ke database `kitapeduli`.)*

### 3.3 Instalasi & Build Frontend (Vite)
```powershell
npm install
npm run build
```

### 3.4 Menjalankan Server Aplikasi
```powershell
php artisan serve
```
Buka browser dan akses: **`http://127.0.0.1:8000`**

*(Untuk pengembangan frontend dengan hot-reload, Anda juga dapat menjalankan `npm run dev` pada terminal terpisah.)*

---

## 4. Akun Demo (Seeder)

| Role | Email | Password | Hak Akses Utama |
|---|---|---|---|
| **Admin** | `admin@kitapeduli.test` | `password123` | Mengelola kampanye, kategori, kabar terbaru, laporan penyaluran, pengguna, dan laporan donasi |
| **Donatur Terdaftar 1** | `andi@kitapeduli.test` | `password123` | Berdonasi, simulasi pembayaran, riwayat donasi pribadi, memberi Aamiin |
| **Donatur Terdaftar 2** | `siti@kitapeduli.test` | `password123` | Berdonasi, simulasi pembayaran, riwayat donasi pribadi, memberi Aamiin |
| **Donatur Terdaftar 3** | `budi@kitapeduli.test` | `password123` | Berdonasi, simulasi pembayaran, riwayat donasi pribadi, memberi Aamiin |
| **Guest (Tanpa Login)** | *(Tidak perlu login)* | - | Menjelajahi kampanye, berdonasi tanpa akun (`user_id = NULL`), simulasi pembayaran |

---

## 5. Pengujian Otomatis & REST API

Untuk menjalankan seluruh rangkaian *Unit Test* dan *Feature Test* (22 test terisolasi menggunakan in-memory SQLite tanpa mengganggu database MySQL utama):

```powershell
php artisan test
```

### Dokumentasi & Koleksi Postman
- Dokumentasi lengkap 38 endpoint REST API: [`docs/api/api-documentation.md`](docs/api/api-documentation.md)
- File Postman Collection: [`docs/api/KitaPeduli.postman_collection.json`](docs/api/KitaPeduli.postman_collection.json)
- File Postman Environment: [`docs/api/KitaPeduli.postman_environment.json`](docs/api/KitaPeduli.postman_environment.json)

---

## 6. Dokumentasi Arsitektur & Laporan PPBO

- **Use Case Diagram**: [`docs/architecture/use-case-diagram.md`](docs/architecture/use-case-diagram.md)
- **Activity Diagram**: [`docs/architecture/activity-diagram.md`](docs/architecture/activity-diagram.md)
- **Class Diagram**: [`docs/architecture/class-diagram.md`](docs/architecture/class-diagram.md)
- **Entity Relationship Diagram (ERD)**: [`docs/architecture/erd.md`](docs/architecture/erd.md)
- **System Analysis**: [`docs/architecture/system-analysis.md`](docs/architecture/system-analysis.md)
- **Implementasi PBO & SOLID**: [`docs/architecture/oop-and-solid-implementation.md`](docs/architecture/oop-and-solid-implementation.md)
- **Laporan Akhir PPBO**: [`docs/report/LAPORAN_PPBO.md`](docs/report/LAPORAN_PPBO.md)
- **Hasil Pengujian Aktual**: [`docs/report/TESTING_RESULTS.md`](docs/report/TESTING_RESULTS.md)
- **Checklist Screenshot Laporan**: [`docs/report/SCREENSHOT_CHECKLIST.md`](docs/report/SCREENSHOT_CHECKLIST.md)

---

## 7. Struktur Folder Proyek

```text
KitaPeduli/
├── README.md
├── kitapeduli.sql
├── .env.example
├── .gitignore
├── app/
│   ├── Contracts/
│   │   ├── PaymentMethodInterface.php
│   │   └── PaymentResolverInterface.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/ (9 SRP Admin Controllers)
│   │   │   ├── Api/ (10 API Controllers, 38 Endpoints)
│   │   │   ├── Donor/DonorDashboardController.php
│   │   │   ├── AuthController.php
│   │   │   ├── CampaignController.php
│   │   │   ├── DonationController.php
│   │   │   ├── HomeController.php
│   │   │   └── PaymentController.php
│   │   ├── Middleware/
│   │   │   ├── EnsureActiveUser.php
│   │   │   ├── EnsureUserIsAdmin.php
│   │   │   └── EnsureUserIsDonor.php
│   │   └── Requests/ (8 FormRequest Validation Classes)
│   ├── Models/ (8 Eloquent Models)
│   ├── Payments/
│   │   ├── PaymentMethod.php (Abstract Class)
│   │   ├── QrisPayment.php
│   │   ├── BankTransferPayment.php
│   │   └── PaymentMethodResolver.php
│   └── Services/
│       ├── CampaignService.php
│       ├── DonationService.php
│       └── PaymentService.php
├── database/
│   ├── factories/UserFactory.php
│   ├── migrations/ (11 Migration Files)
│   └── seeders/DatabaseSeeder.php
├── docs/
│   ├── api/
│   ├── architecture/
│   └── report/
├── public/images/campaigns/ (Illustrasi SVG Banner Kampanye)
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/ (Layouts, Components, Home, Campaigns, Donations, Payments, Donor, Admin)
├── routes/
│   ├── api.php
│   └── web.php
└── tests/
    ├── Feature/ (5 Feature Test Suites)
    └── Unit/ (2 Unit Test Suites)
```
