# System Analysis: KitaPeduli

Dokumen ini merangkum analisis sistem, batasan ruang lingkup, aturan bisnis, dan arsitektur teknis aplikasi **KitaPeduli**.

---

## 1. Latar Belakang & Tujuan Sistem

**KitaPeduli** adalah aplikasi web crowdfunding dan donasi sosial yang dikembangkan menggunakan **Laravel 13** dan **MySQL** untuk memenuhi tugas akhir mata kuliah Pemrograman Berorientasi Objek (PPBO). Aplikasi ini mengambil inspirasi alur bisnis dan transparansi dari platform *Kitabisa.com* serta inspirasi komposisi tata letak visual bersih dari referensi antarmuka yang diberikan, namun fokus sepenuhnya pada domain penggalangan dana sosial dengan ruang lingkup yang terukur.

Tujuan utama sistem:
1. Memungkinkan **Admin** mengelola kampanye donasi, informasi pengelola, kategori, kabar terbaru, dan laporan penyaluran dana secara transparan.
2. Memungkinkan **Donatur Terdaftar** maupun **Guest (tanpa login)** memberikan donasi dan doa dengan mudah.
3. Menyediakan mekanisme **simulasi pembayaran** (QRIS dan Transfer Bank/ATM) yang menerapkan prinsip PBO dan SOLID secara nyata.
4. Menyediakan **Web UI (Blade)** dan **REST API (JSON)** yang berbagi lapisan logika bisnis (`Service Layer`) yang sama.

---

## 2. Analisis Aktor & Batasan Ruang Lingkup

### 2.1 Aktor Sistem
- **Admin (`users.role = 'admin'`)**: Mengelola seluruh siklus hidup kampanye, kategori, kabar terbaru, laporan penyaluran dana, status pengguna terdaftar, serta memantau dashboard dan laporan transaksi.
- **Donatur Terdaftar (`users.role = 'donor'`)**: Pengguna yang melakukan registrasi dan login. Memiliki dashboard riwayat donasi pribadi dan dapat memberikan reaksi **Aamiin** pada doa donatur.
- **Guest (Pengunjung Tanpa Login)**: Bukan role di tabel `users`. Dapat menjelajahi kampanye dan melakukan donasi langsung (`donations.user_id = NULL`). Mengakses halaman simulasi dan status transaksi saat ini menggunakan token akses transaksi (`access_token`) yang di-hash di database.

### 2.2 Batasan Sistem (In-Scope vs Out-of-Scope)
- **Tidak ada Role Fundraiser/Pengajuan Kampanye Publik**: Seluruh kampanye dibuat dan dikelola langsung oleh Admin. Informasi pengelola kampanye (`organizer_name`, `organizer_type`, `organizer_description`) disimpan sebagai atribut kampanye.
- **Simulasi Pembayaran Mandiri (Tanpa Payment Gateway & Tanpa Verifikasi Admin)**:
  - Setiap halaman pembayaran diberi label tegas **"SIMULASI / DEMO: Bukan pembayaran nyata"**.
  - Ketika pengguna menekan tombol simulasi pembayaran berhasil, backend memvalidasi kepemilikan/token akses dan status `pending`, lalu memperbarui status pembayaran menjadi `paid` dan donasi menjadi `success` di dalam transaksi database atomik (`DB::transaction` + `lockForUpdate`).
- **Satu Modul "Doa-doa Orang Baik"**: Daftar donatur publik dan doa donatur digabung dalam satu fitur **Doa-doa Orang Baik** yang hanya menampilkan transaksi berstatus `success` dan menghormati opsi `is_anonymous`.

---

## 3. Arsitektur Lapisan Aplikasi (Layered Architecture)

```mermaid
flowchart TD
    subgraph Client["Presentation / Client Layer"]
        WebUI["Laravel Blade Views\n(Responsive UI + Vite CSS/JS)"]
        ApiClient["REST API Client / Postman\n(JSON Request & Response)"]
    end

    subgraph HttpLayer["HTTP & Security Layer"]
        Middleware["Middleware & Authorization\n(Auth, EnsureUserIsAdmin, EnsureUserIsDonor, EnsureActiveUser)"]
        FormRequests["Form Requests Validation\n(StoreDonationRequest, StoreCampaignRequest, dll.)"]
        WebCtrl["Web Controllers\n(Umum + 9 Admin SRP Controllers)"]
        ApiCtrl["API Controllers (/api/*)"]
    end

    subgraph DomainLayer["Business & Domain Layer (OOP & SOLID)"]
        Services["Domain Services\n(DonationService, PaymentService, CampaignService)"]
        PaymentContract["Contracts / Interfaces\n(PaymentMethodInterface, PaymentResolverInterface)"]
        PaymentAbs["Abstract PaymentMethod"]
        Qris["QrisPayment"]
        Bank["BankTransferPayment"]
    end

    subgraph DataLayer["Data Persistence Layer"]
        Models["Eloquent Models\n(User, Campaign, Category, Donation, Payment,\nCampaignUpdate, FundDisbursementReport, DonationReaction)"]
        MySQL[("MySQL Database\n(kitapeduli)")]
    end

    WebUI --> Middleware
    ApiClient --> Middleware
    Middleware --> FormRequests
    FormRequests --> WebCtrl
    FormRequests --> ApiCtrl
    WebCtrl --> Services
    ApiCtrl --> Services
    Services --> PaymentContract
    PaymentContract <|.. PaymentAbs
    PaymentAbs <|-- Qris
    PaymentAbs <|-- Bank
    Services --> Models
    Models --> MySQL
```

---

## 4. Analisis Keamanan & Integritas Data

1. **Penentuan `user_id` di Backend**: Backend tidak pernah membaca `user_id` dari body request client. Jika request terautentikasi (melalui session web atau token Bearer Sanctum), `user_id` diambil dari user yang login; jika tidak, `user_id = NULL`.
2. **Proteksi Transaksi Guest (`access_token_hash`)**:
   - Saat donasi dibuat, sistem men-generate 48-karakter random token (`Str::random(48)`) sebagai `plain_token` dan menyimpan `hash('sha256', $plainToken)` pada kolom `payments.access_token_hash`.
   - Untuk akses REST API, Guest mengirimkan token melalui HTTP Header `X-Transaction-Token` (bukan query string URL).
   - Untuk akses Web Blade, token disimpan di session browser pengguna saat transaksi dibuat.
   - Otorisasi akses dan simulasi pembayaran tidak menggunakan middleware khusus, melainkan diperiksa langsung melalui `PaymentService::canAccessPayment()` dan `PaymentService::canSimulatePayment()`.
3. **Pencegahan Double-Submit / Idempotency**:
   - Konfirmasi simulasi pembayaran dijalankan di dalam `DB::transaction()` menggunakan `Payment::where('id', $payment->id)->lockForUpdate()->first()`.
   - Jika status sudah `paid` / `success`, sistem tidak menambahkan nominal ke `campaigns.collected_amount` untuk kedua kalinya.
4. **Konsistensi Agregat Dana Kampanye**:
   - `Campaign::recalculateCollectedAmount()` menghitung langsung `SUM(amount)` dan `COUNT(*)` dari tabel `donations` yang memiliki `status = 'success'`. Donasi `pending` atau `cancelled` tidak pernah dihitung ke dalam dana terkumpul.
