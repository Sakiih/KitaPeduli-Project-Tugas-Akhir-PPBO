# Checklist Screenshot untuk Laporan Akhir PPBO: KitaPeduli

Gunakan daftar periksa ini saat mengambil tangkapan layar (*screenshot*) aplikasi yang sedang berjalan untuk ditempelkan ke dokumen laporan akhir PPBO (`docs/report/LAPORAN_PPBO.md`).

---

## A. Screenshot Antarmuka Web Publik & Guest

- **1. Homepage KitaPeduli (`/`)**
  - Menampilkan Navbar KitaPeduli, Hero kampanye unggulan, bar pencarian, sidebar kategori di kiri, grid 3 kolom kartu kampanye, rekomendasi kampanye, banner CTA, dan footer.
- **2. Halaman Jelajahi Kampanye & Filter (`/kampanye`)**
  - Menampilkan penggunaan fitur pencarian kata kunci dan filter kategori kampanye.
- **3. Halaman Detail Kampanye & 8 Komponen Transparansi (`/kampanye/{slug}`)**
  - Screenshot bagian Cerita Penggalangan Dana & Progress Bar Dana Terkumpul.
  - Screenshot bagian Kabar Terbaru & Pencairan Dana / Laporan Penyaluran.
  - Screenshot bagian Informasi Pengelola Kampanye & Doa-doa Orang Baik (menampilkan donatur bernama maupun `"Orang Baik (Anonim)"` beserta jumlah Aamiin).
- **4. Formulir Donasi Mode Guest (`/kampanye/{slug}/donasi`)**
  - Menampilkan badge `Mode Guest`, pilihan nominal cepat, input identitas donatur, checkbox donasi anonim, input doa, dan pilihan metode pembayaran (`QRIS` / `Transfer Bank`).
- **5. Halaman Simulasi Pembayaran QRIS (`/pembayaran/{reference}`)**
  - Menampilkan label peringatan kuning **"SIMULASI / DEMO: Bukan pembayaran nyata"**, ilustrasi kode QRIS Demo, detail referensi transaksi, dan tombol **"Simulasikan Pembayaran Berhasil"**.
- **6. Halaman Simulasi Pembayaran Transfer Bank (`/pembayaran/{reference}`)**
  - Menampilkan nomor Virtual Account / Rekening Simulasi, kode bank demo, dan tombol **"Simulasikan Pembayaran Berhasil"**.
- **7. Halaman Hasil Pembayaran Berhasil (`paid` / `success`)**
  - Menampilkan banner hijau konfirmasi pembayaran simulasi berhasil dan pembaruan dana terkumpul kampanye.

---

## B. Screenshot Akun Donatur Terdaftar

- **8. Halaman Registrasi Donatur (`/register`) & Login (`/login`)**
- **9. Dashboard Donatur Terdaftar (`/donatur/dashboard`)**
  - Menampilkan statistik total donasi berhasil, frekuensi kebaikan, jumlah Aamiin diberikan, dan tabel transaksi terbaru.
- **10. Riwayat Donasi Pribadi (`/donatur/riwayat-donasi`) & Detail Transaksi (`/donatur/riwayat-donasi/{id}`)**
- **11. Pemberian Reaksi Aamiin pada Doa-doa Orang Baik**
  - Menampilkan tombol `Aamiin` yang aktif saat login sebagai Donatur Terdaftar.

---

## C. Screenshot Panel Administrator

- **12. Dashboard Admin (`/admin/dashboard`)**
  - Menampilkan sidebar Admin (tanpa menu verifikasi pembayaran), kartu total dana terkumpul, jumlah kampanye aktif, jumlah akun donatur vs transaksi Guest, dan tabel transaksi terbaru.
- **13. Pengelolaan Kampanye (`/admin/kampanye` & `/admin/kampanye/buat`)**
  - Menampilkan daftar kampanye, pembuatan kampanye baru, dan tombol Arsipkan pada kampanye yang sudah memiliki transaksi.
- **14. Pengelolaan Kategori (`/admin/kategori`)**
- **15. Pengelolaan Kabar Terbaru (`/admin/kabar-terbaru`) & Laporan Penyaluran Dana (`/admin/penyaluran-dana`)**
- **16. Pemantauan Seluruh Transaksi Donasi (`/admin/transaksi-donasi`) & Laporan Donasi (`/admin/laporan`)**
- **17. Manajemen Pengguna Terdaftar (`/admin/pengguna`)**

---

## D. Screenshot Pengujian REST API (Postman) & GitHub

- **18. Postman: `POST /api/login` & `POST /api/register`**
- **19. Postman: `GET /api/campaigns` & `GET /api/campaigns/{id}`**
- **20. Postman: `POST /api/donations` (Guest Donation `user_id: null` & `access_token`)**
- **21. Postman: `POST /api/payments/{reference}/simulate-success` dengan Header `X-Transaction-Token`**
- **22. Postman: `GET /api/admin/dashboard` dengan Bearer Token Admin**
- **23. Terminal: Hasil Eksekusi `php artisan test` (28 Passed, 240 Assertions)**
- **24. GitHub: Halaman Utama Repository & Riwayat Commit Kelompok**
