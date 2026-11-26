# Use Case Diagram: KitaPeduli

Dokumen ini menjelaskan rancangan **Use Case Diagram** untuk platform crowdfunding dan donasi **KitaPeduli** sesuai ruang lingkup tugas akhir PPBO Laravel 13.

## 1. Definisi Aktor

Sistem **KitaPeduli** memiliki tiga entitas pengguna yang berinteraksi dengan sistem, di mana hanya **dua** yang merupakan role akun pada tabel `users`:

1. **Admin (`role = 'admin'`)**
   - Satu-satunya aktor yang berhak membuat, mengubah, menonaktifkan/mengarsipkan kampanye, mengelola kategori, mempublikasikan kabar terbaru, mencatat laporan penyaluran dana, mengelola status akun pengguna terdaftar, serta memantau seluruh transaksi dan laporan donasi.
   - Admin **tidak** melakukan verifikasi atau persetujuan pembayaran karena sistem menggunakan simulasi pembayaran mandiri.
2. **Donatur Terdaftar (`role = 'donor'`)**
   - Pengguna yang memiliki akun dan telah login.
   - Dapat menjelajahi kampanye, melakukan donasi, menjalankan simulasi pembayaran (QRIS atau Transfer Bank), melihat riwayat dan detail donasi pribadi, mengirim pesan/doa, memilih donasi anonim, memberikan reaksi **Aamiin** pada doa donatur lain, serta mengelola profil akun.
3. **Guest / Donatur Tanpa Login (Bukan Role Akun)**
   - Pengunjung publik yang belum login dan tidak memiliki baris pada tabel `users`.
   - Dapat melihat homepage, mencari dan memfilter kampanye, melihat detail dan transparansi kampanye (kabar terbaru, penyaluran dana, doa-doa orang baik), serta melakukan donasi langsung tanpa registrasi (`donations.user_id = NULL`).
   - Dapat menjalankan simulasi pembayaran dan melihat status transaksi yang sedang dilakukan menggunakan token akses transaksi (`access_token`) yang aman.
   - Tidak memiliki halaman riwayat donasi pribadi dan tidak dapat memberikan reaksi Aamiin.

---

## 2. Diagram Use Case (Mermaid)

```mermaid
flowchart LR
    subgraph Actors["Aktor Sistem"]
        Guest["Guest\n(Pengunjung Tanpa Login)"]
        Donor["Donatur Terdaftar\n(Role: donor)"]
        Admin["Admin\n(Role: admin)"]
    end

    subgraph PublicUC["1. Akses Publik & Penjelajahan"]
        UC_Home["Melihat Homepage & Kampanye Unggulan"]
        UC_List["Mencari & Memfilter Kampanye"]
        UC_Detail["Melihat Detail Kampanye & Pengelola"]
        UC_Trans["Melihat Kabar Terbaru & Laporan Penyaluran"]
        UC_Prayers["Melihat Doa-doa Orang Baik & Jumlah Aamiin"]
        UC_Static["Membaca FAQ & Pedoman Kebaikan"]
    end

    subgraph DonationUC["2. Donasi & Simulasi Pembayaran"]
        UC_DonateGuest["Melakukan Donasi sebagai Guest\n(Isi Identitas, Nominal, Doa, Anonim)"]
        UC_DonateAuth["Melakukan Donasi sebagai Donatur Terdaftar"]
        UC_SelectPay["Memilih Metode Pembayaran\n(QRIS / Transfer Bank Simulasi)"]
        UC_SimPay["Menjalankan Simulasi Pembayaran Berhasil"]
        UC_ViewTxStatus["Melihat Status Transaksi Saat Ini\n(Owner / Guest Token)"]
    end

    subgraph DonorUC["3. Fitur Khusus Donatur Terdaftar"]
        UC_Register["Registrasi Akun Donatur"]
        UC_LoginDonor["Login & Logout"]
        UC_History["Melihat Dashboard & Riwayat Donasi Pribadi"]
        UC_Amen["Memberikan / Membatalkan Aamiin pada Doa"]
        UC_Profile["Mengelola Profil Akun"]
    end

    subgraph AdminUC["4. Administrasi & Transparansi (Admin)"]
        UC_AdminDash["Melihat Statistik Dashboard Admin"]
        UC_ManageCat["Mengelola Kategori Kampanye (CRUD)"]
        UC_ManageCamp["Mengelola Kampanye & Info Pengelola (CRUD / Arsip)"]
        UC_ManageUpd["Mengelola Kabar Terbaru Kampanye (CRUD)"]
        UC_ManageDisb["Mengelola Laporan Penyaluran Dana (CRUD)"]
        UC_MonitorTx["Memantau Seluruh Transaksi Donasi & Pembayaran"]
        UC_ManageUser["Melihat Pengguna & Mengubah Status Akun"]
        UC_Report["Melihat Laporan & Statistik Donasi"]
    end

    %% Relasi Guest
    Guest --> UC_Home
    Guest --> UC_List
    Guest --> UC_Detail
    Guest --> UC_Trans
    Guest --> UC_Prayers
    Guest --> UC_Static
    Guest --> UC_DonateGuest
    Guest --> UC_Register
    UC_DonateGuest --> UC_SelectPay
    UC_SelectPay --> UC_SimPay
    UC_SimPay --> UC_ViewTxStatus

    %% Relasi Donatur Terdaftar
    Donor --> UC_Home
    Donor --> UC_List
    Donor --> UC_Detail
    Donor --> UC_Trans
    Donor --> UC_Prayers
    Donor --> UC_Static
    Donor --> UC_LoginDonor
    Donor --> UC_DonateAuth
    Donor --> UC_History
    Donor --> UC_Amen
    Donor --> UC_Profile
    UC_DonateAuth --> UC_SelectPay

    %% Relasi Admin
    Admin --> UC_LoginDonor
    Admin --> UC_AdminDash
    Admin --> UC_ManageCat
    Admin --> UC_ManageCamp
    Admin --> UC_ManageUpd
    Admin --> UC_ManageDisb
    Admin --> UC_MonitorTx
    Admin --> UC_ManageUser
    Admin --> UC_Report
```

---

## 3. Matriks Hak Akses Use Case

| Kode | Nama Use Case | Guest | Donatur Terdaftar | Admin |
|---|---|---:|---:|---:|
| UC-01 | Melihat Homepage, Daftar, dan Detail Kampanye | Ya | Ya | Ya |
| UC-02 | Mencari dan Memfilter Kampanye per Kategori | Ya | Ya | Ya |
| UC-03 | Melihat Transparansi (Kabar Terbaru, Penyaluran, Doa) | Ya | Ya | Ya |
| UC-04 | Registrasi Akun Donatur | Ya | - | Tidak |
| UC-05 | Login dan Logout | - | Ya | Ya |
| UC-06 | Membuat Donasi & Memilih Metode Pembayaran | Ya (`user_id=NULL`) | Ya (`user_id=Auth`) | Tidak |
| UC-07 | Menjalankan Simulasi Pembayaran (QRIS / Bank Transfer) | Ya (Token Transaksi) | Ya (Owner/Token) | Tidak |
| UC-08 | Melihat Status/Hasil Transaksi Saat Ini | Ya (Token Transaksi) | Ya | Ya (Semua) |
| UC-09 | Melihat Riwayat Donasi Pribadi | Tidak | Ya | Tidak Berlaku |
| UC-10 | Memberikan / Membatalkan Aamiin pada Doa Donatur | Tidak | Ya (Maks 1/doa) | Tidak |
| UC-11 | Mengelola Kategori Kampanye | Tidak | Tidak | Ya |
| UC-12 | Membuat, Mengubah, dan Mengarsipkan Kampanye | Tidak | Tidak | Ya |
| UC-13 | Mengelola Kabar Terbaru Kampanye | Tidak | Tidak | Ya |
| UC-14 | Mengelola Laporan Penyaluran Dana | Tidak | Tidak | Ya |
| UC-15 | Memantau Seluruh Transaksi & Laporan Donasi | Tidak | Tidak | Ya |
| UC-16 | Mengelola Status Akun Pengguna Terdaftar | Tidak | Tidak | Ya |
