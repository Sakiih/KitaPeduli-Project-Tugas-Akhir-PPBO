# Activity Diagram: KitaPeduli

Dokumen ini menyajikan **Activity Diagram** untuk proses bisnis utama pada aplikasi **KitaPeduli**.

---

## 1. Activity Diagram: Authentication (Registrasi & Login)

```mermaid
flowchart TD
    Start([Mulai]) --> ChooseAuth{Pilih Aksi}
    
    ChooseAuth -->|Registrasi Donatur| OpenReg[Buka Halaman / Endpoint Registrasi]
    OpenReg --> InputReg[Isi Nama, Email, No. Telepon, Password]
    InputReg --> ValidateReg{Validasi Backend\nEmail Unik & Format Valid?}
    ValidateReg -->|Tidak| ShowRegErr[Tampilkan Pesan Error Validasi 422]
    ShowRegErr --> InputReg
    ValidateReg -->|Ya| SaveUser[Simpan User Baru dengan role = 'donor'\n& status = 'active' serta Hash Password]
    SaveUser --> IssueSession[Buat Sesi Login / Token Sanctum]
    IssueSession --> RedirectDonor[Arahkan ke Dashboard Donatur / Homepage]

    ChooseAuth -->|Login Admin / Donatur| OpenLogin[Buka Halaman / Endpoint Login]
    OpenLogin --> InputLogin[Isi Email & Password]
    InputLogin --> CheckCred{Kredensial Valid\n& Akun Active?}
    CheckCred -->|Tidak| ShowLoginErr[Tampilkan Error Kredensial / Akun Nonaktif]
    ShowLoginErr --> InputLogin
    CheckCred -->|Ya| CheckRole{Periksa Role User}
    CheckRole -->|role = 'admin'| RedirectAdmin[Arahkan ke Dashboard Admin]
    CheckRole -->|role = 'donor'| RedirectDonor
    
    RedirectAdmin --> End([Selesai])
    RedirectDonor --> End
```

---

## 2. Activity Diagram: Pengelolaan Kampanye (Admin)

```mermaid
flowchart TD
    Start([Mulai]) --> AdminAuth{Autentikasi & Role = Admin?}
    AdminAuth -->|Tidak| Deny[Tolak Akses 401/403]
    Deny --> End([Selesai])
    
    AdminAuth -->|Ya| Action{Pilih Aksi Kampanye}
    
    Action -->|Buat / Ubah Kampanye| FillForm[Isi Judul, Kategori, Deskripsi/Cerita,\nTarget Dana, Tanggal Berakhir,\nNama & Info Pengelola, Foto Banner]
    FillForm --> ValidateCamp{Validasi FormRequest\nTarget > 0 & Data Valid?}
    ValidateCamp -->|Tidak| FormError[Kembalikan Error Validasi]
    FormError --> FillForm
    ValidateCamp -->|Ya| StoreCamp[Simpan Data Kampanye & Upload Foto ke Storage]
    StoreCamp --> SuccessMsg[Tampilkan Detail / Daftar Kampanye]

    Action -->|Hapus / Arsipkan Kampanye| CheckHasDonations{Apakah Kampanye\nSudah Memiliki Transaksi Donasi?}
    CheckHasDonations -->|Ya| ArchiveCamp[Ubah Status Kampanye menjadi 'inactive'\nagar Histori Transaksi Tetap Utuh]
    CheckHasDonations -->|Tidak| DeleteCamp[Hapus Record Kampanye dari Database]
    
    ArchiveCamp --> SuccessMsg
    DeleteCamp --> SuccessMsg
    SuccessMsg --> End
```

---

## 3. Activity Diagram: Donasi Terdaftar (Registered Donor)

```mermaid
flowchart TD
    Start([Mulai]) --> LoginCheck[Donatur Login ke Sistem]
    LoginCheck --> SelectCamp[Pilih Kampanye Aktif]
    SelectCamp --> CheckCampActive{Status Kampanye = 'active'\n& Belum Melewati Tanggal Akhir?}
    CheckCampActive -->|Tidak| RejectCamp[Tampilkan Informasi Kampanye Tidak Menerima Donasi]
    RejectCamp --> End([Selesai])

    CheckCampActive -->|Ya| OpenForm[Buka Form Donasi\nNama & Email Terisi Otomatis dari Akun]
    OpenForm --> InputDonation[Isi Nominal Positif, Pesan/Doa Opsional,\nPilihan Anonim, & Metode Pembayaran: QRIS / Bank Transfer]
    InputDonation --> SubmitDonation[Kirim Request Donasi]
    SubmitDonation --> BackendProcess[Backend Mengambil user_id dari Auth Session/Token\nMengabaikan user_id dari Input Client]
    BackendProcess --> CreateRecords[DB Transaction:\n1. Buat Donation status = 'pending'\n2. Buat Payment status = 'pending', reference unik, & token hash\n3. Generate Instruksi via Polymorphism PaymentMethod]
    CreateRecords --> RedirectPay[Arahkan ke Halaman Simulasi Pembayaran]
    RedirectPay --> End
```

---

## 4. Activity Diagram: Guest Donation (Donasi Tanpa Login)

```mermaid
flowchart TD
    Start([Mulai]) --> SelectCamp[Guest Memilih Kampanye Aktif Tanpa Login]
    SelectCamp --> CheckCampActive{Kampanye Aktif?}
    CheckCampActive -->|Tidak| RejectCamp[Tolak Donasi Baru]
    RejectCamp --> End([Selesai])

    CheckCampActive -->|Ya| FillGuestForm[Guest Mengisi Nama Donatur Wajib,\nEmail Opsional, Nominal Positif,\nPesan/Doa Opsional, Pilihan Anonim,\n& Metode Pembayaran]
    FillGuestForm --> ValidateInput{Validasi Backend Lolos?}
    ValidateInput -->|Tidak| ReturnErr[Tampilkan Pesan Validasi]
    ReturnErr --> FillGuestForm

    ValidateInput -->|Ya| CreateGuestRecords[DB Transaction:\n1. Buat Donation dengan user_id = NULL & status = 'pending'\n2. Buat Payment dengan status = 'pending', reference unik,\n   dan simpan SHA-256 hash dari plain access_token]
    CreateGuestRecords --> DeliverToken[Simpan Plain Access Token di Session Web\natau Kembalikan di JSON Response API Sekali Saja]
    DeliverToken --> ShowPayPage[Arahkan Guest ke Halaman Simulasi Pembayaran\nMenggunakan Reference & Proteksi Access Token]
    ShowPayPage --> End
```

---

## 5. Activity Diagram: Simulasi Pembayaran QRIS

```mermaid
flowchart TD
    Start([Mulai pada Halaman Pembayaran QRIS]) --> ResolveMethod[PaymentMethodResolver Memilih QrisPayment]
    ResolveMethod --> RenderQris[QrisPayment::createInstruction Menghasilkan:\n- Payload & Visual Kode QRIS SIMULASI/DEMO\n- Merchant Name: KitaPeduli Demo\n- Nominal & Reference Transaksi\n- Label Peringatan: BUKAN PEMBAYARAN NYATA]
    RenderQris --> UserClick[Pengguna Menekan Tombol\n'Simulasikan Pembayaran Berhasil']
    UserClick --> VerifyAccess{Validasi Kepemilikan User\natau Hash Access Token Guest Valid?}
    VerifyAccess -->|Tidak| Deny403[Tolak Akses 403 Forbidden]
    Deny403 --> End([Selesai])

    VerifyAccess -->|Ya| LockTx[Mulai DB Transaction & lockForUpdate pada Payment/Donation]
    LockTx --> CheckPending{Apakah Status Payment = 'pending'?}
    CheckPending -->|Tidak / Sudah Paid| ReturnIdempotent[Kembalikan Status Berhasil Tanpa Menambah Dana Lagi\nAnti Double-Submit]
    ReturnIdempotent --> End

    CheckPending -->|Ya| ExecuteEncapsulated[1. Payment::markAsPaid mengubah status = 'paid' & paid_at\n2. Donation::markAsSuccessful mengubah status = 'success'\n3. Campaign::recalculateCollectedAmount menghitung ulang\n   SUM donasi berstatus 'success']
    ExecuteEncapsulated --> CommitTx[Commit DB Transaction]
    CommitTx --> ShowSuccess[Tampilkan Halaman / Response Pembayaran Berhasil\n& Doa Muncul di Doa-doa Orang Baik]
    ShowSuccess --> End
```

---

## 6. Activity Diagram: Simulasi Pembayaran Transfer Bank

```mermaid
flowchart TD
    Start([Mulai pada Halaman Pembayaran Transfer Bank]) --> ResolveMethod[PaymentMethodResolver Memilih BankTransferPayment]
    ResolveMethod --> RenderBank[BankTransferPayment::createInstruction Menghasilkan:\n- Nomor Virtual Account / Rekening ATM SIMULASI\n- Nama Bank Demo & Atas Nama KitaPeduli Simulasi\n- Langkah-langkah Transfer ATM/M-Banking Simulasi\n- Label Peringatan: SIMULASI / DEMO]
    RenderBank --> UserClick[Pengguna Menekan Tombol\n'Simulasikan Transfer Berhasil']
    UserClick --> VerifyAccess{Validasi Kepemilikan User\natau Hash Access Token Guest Valid?}
    VerifyAccess -->|Tidak| Deny403[Tolak Akses 403 Forbidden]
    Deny403 --> End([Selesai])

    VerifyAccess -->|Ya| LockTx[Mulai DB Transaction & lockForUpdate pada Payment/Donation]
    LockTx --> CheckPending{Apakah Status Payment = 'pending'?}
    CheckPending -->|Tidak / Sudah Paid| ReturnIdempotent[Kembalikan Status Berhasil Tanpa Double Counting]
    ReturnIdempotent --> End

    CheckPending -->|Ya| ExecuteEncapsulated[1. Payment::markAsPaid mengubah status = 'paid' & paid_at\n2. Donation::markAsSuccessful mengubah status = 'success'\n3. Campaign::recalculateCollectedAmount memperbarui dana terkumpul]
    ExecuteEncapsulated --> CommitTx[Commit DB Transaction]
    CommitTx --> ShowSuccess[Tampilkan Halaman / Response Transfer Simulasi Berhasil]
    ShowSuccess --> End
```

---

## 7. Activity Diagram: Pengelolaan Kabar Terbaru (`CampaignUpdate`)

```mermaid
flowchart TD
    Start([Mulai]) --> AdminCheck{Autentikasi Admin?}
    AdminCheck -->|Tidak| Deny[Tolak Akses 403]
    Deny --> End([Selesai])

    AdminCheck -->|Ya| Action{Pilih Aksi Kabar Terbaru}

    Action -->|Tambah Baru| SelectCamp[Pilih Kampanye & Isi Judul, Perkembangan,\nserta Foto Dokumentasi Opsional]
    SelectCamp --> ValidateUpd{Validasi StoreCampaignUpdateRequest\n& campaign_id Valid?}
    ValidateUpd -->|Tidak| ShowErr[Tampilkan Error Validasi]
    ShowErr --> SelectCamp
    ValidateUpd -->|Ya| SaveUpdate[Simpan Record campaign_updates\nbeserta Tanggal Publikasi]
    SaveUpdate --> DisplayPublic[Perubahan Tampil pada Bagian Kabar Terbaru\ndi Halaman Detail Kampanye Publik]

    Action -->|Edit via Modal| OpenEditModal[Klik Edit & Ubah Judul, Isi, Tanggal Publikasi,\natau Ganti Foto di Modal]
    OpenEditModal --> ValidateEdit{Validasi StoreCampaignUpdateRequest\nLolos?}
    ValidateEdit -->|Tidak| ShowErr
    ValidateEdit -->|Ya| UpdateRecord[Perbarui Record campaign_updates\n& Hapus Foto Lama Jika Diganti]
    UpdateRecord --> DisplayPublic

    Action -->|Hapus| ConfirmDelete[Konfirmasi Hapus Kabar Terbaru]
    ConfirmDelete --> DeleteRecord[Hapus File Foto Terkait & Record dari Database]
    DeleteRecord --> DisplayPublic

    DisplayPublic --> End
```

---

## 8. Activity Diagram: Pengelolaan Laporan Penyaluran Dana (`FundDisbursementReport`)

```mermaid
flowchart TD
    Start([Mulai]) --> AdminCheck{Autentikasi Admin?}
    AdminCheck -->|Tidak| Deny[Tolak Akses 403]
    Deny --> End([Selesai])

    AdminCheck -->|Ya| Action{Pilih Aksi Penyaluran Dana}

    Action -->|Catat Baru| InputDisb[Pilih Kampanye & Isi Judul, Nominal, Penerima,\nKeperluan, Tanggal, Deskripsi, & Dokumentasi]
    InputDisb --> ValidateDisb{Validasi Request & Saldo Tersedia\nvia lockForUpdate Mencukupi?}
    ValidateDisb -->|Tidak| ShowErr[Tampilkan Error Validasi / Saldo Tidak Cukup]
    ShowErr --> InputDisb
    ValidateDisb -->|Ya| SaveDisb[Simpan Record fund_disbursement_reports]
    SaveDisb --> DisplayPublic[Perubahan Tampil pada Bagian Pencairan Dana\ndi Halaman Detail Kampanye Publik]

    Action -->|Edit via Modal| OpenEditDisb[Klik Edit & Ubah Data Penyaluran di Modal]
    OpenEditDisb --> ValidateEditDisb{Validasi Request & Saldo Tersedia\nKecuali Record Ini Mencukupi?}
    ValidateEditDisb -->|Tidak| ShowErr
    ValidateEditDisb -->|Ya| UpdateDisb[Perbarui Record & Ganti Dokumentasi Jika Ada]
    UpdateDisb --> DisplayPublic

    Action -->|Hapus| ConfirmDisb[Konfirmasi Hapus Catatan Penyaluran]
    ConfirmDisb --> DeleteDisb[Hapus File Dokumentasi Terkait & Record dari Database]
    DeleteDisb --> DisplayPublic

    DisplayPublic --> End
```
