# Dokumentasi REST API: KitaPeduli

Seluruh endpoint REST API **KitaPeduli** menggunakan prefix `/api` dan selalu mengembalikan response berformat **JSON** terstruktur (`{"success": bool, "message": string, "data": ...}`), meskipun client tidak mengirim header `Accept: application/json`. Autentikasi pengguna terdaftar menggunakan **Laravel Sanctum (Bearer Token)**, sedangkan akses transaksi tunggal bagi Guest menggunakan header **`X-Transaction-Token`**.

---

## 1. Konvensi Umum

- **Base URL Lokal**: `http://127.0.0.1:8000/api`
- **Headers Standar**:
  - `Accept: application/json`
  - `Content-Type: application/json`
- **Header Autentikasi Akun Terdaftar (Admin / Donatur)**:
  - `Authorization: Bearer <sanctum_token>`
- **Header Akses Transaksi Guest**:
  - `X-Transaction-Token: <access_token>` (diperoleh dari response `POST /api/donations`)
- **Kode Status HTTP**:
  - `200 OK`: Request membaca atau memperbarui data berhasil.
  - `201 Created`: Resource baru berhasil dibuat.
  - `400 Bad Request`: Format permintaan tidak valid.
  - `401 Unauthorized`: Belum login atau token Bearer tidak valid.
  - `403 Forbidden`: Tidak memiliki hak akses (bukan Admin, akun nonaktif, bukan pemilik transaksi, atau token transaksi Guest tidak cocok).
  - `404 Not Found`: Data atau endpoint tidak ditemukan.
  - `405 Method Not Allowed`: Metode HTTP tidak didukung untuk endpoint tersebut.
  - `409 Conflict`: Aksi ditolak karena keterkaitan relasi data (foreign key constraint).
  - `422 Unprocessable Entity`: Validasi input gagal atau aturan bisnis menolak aksi (misalnya double-submit simulasi pembayaran, nominal tidak valid, pencairan melebihi sisa dana terkumpul kampanye, atau Aamiin duplikat).
  - `500 Internal Server Error`: Kesalahan tak terduga pada server (dikembalikan dalam format JSON).

---

## 2. Daftar Lengkap 38 Endpoint REST API

### 2.1 Authentication API (4 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 1 | `POST` | `/api/register` | Public | Registrasi akun Donatur Terdaftar (`role = donor`) |
| 2 | `POST` | `/api/login` | Public | Login Admin atau Donatur Terdaftar |
| 3 | `POST` | `/api/logout` | Auth | Logout dan mencabut token Sanctum aktif |
| 4 | `GET` | `/api/me` | Auth | Mengambil profil akun yang sedang login |

#### Contoh Request `POST /api/register`
```json
{
  "name": "Rina Wulandari",
  "email": "rina@example.com",
  "phone": "081234567890",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Contoh Response `201 Created`
```json
{
  "success": true,
  "message": "Registrasi akun Donatur berhasil.",
  "data": {
    "user": {
      "id": 5,
      "name": "Rina Wulandari",
      "email": "rina@example.com",
      "phone": "081234567890",
      "role": "donor",
      "status": "active"
    },
    "token": "1|laravel_sanctum_token_example...",
    "token_type": "Bearer"
  }
}
```

---

### 2.2 Campaign API (7 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 5 | `GET` | `/api/campaigns` | Public | Daftar kampanye aktif (`?search=&category_id=&sort=`) |
| 6 | `GET` | `/api/campaigns/{id}` | Public | Detail kampanye (mendukung ID atau slug) |
| 7 | `GET` | `/api/admin/campaigns` | Admin | Daftar seluruh kampanye (termasuk `inactive` & `completed`) |
| 8 | `POST` | `/api/admin/campaigns` | Admin | Membuat kampanye baru |
| 9 | `PUT` | `/api/admin/campaigns/{id}` | Admin | Mengubah informasi kampanye |
| 10 | `PATCH` | `/api/admin/campaigns/{id}/status` | Admin | Mengubah status kampanye (`active`, `completed`, `inactive`) |
| 11 | `DELETE` | `/api/admin/campaigns/{id}` | Admin | Menghapus kampanye (atau mengarsipkan menjadi `inactive` jika sudah memiliki donasi) |

#### Contoh Request `POST /api/admin/campaigns`
```json
{
  "category_id": 1,
  "title": "Bantuan Klinik Keliling Desa Terpencil",
  "short_description": "Penyediaan layanan pemeriksaan medis gratis bagi warga desa terpencil.",
  "story": "Cerita lengkap penggalangan dana klinik keliling...",
  "target_amount": 35000000,
  "end_date": "2027-02-28",
  "organizer_name": "Yayasan Medis Peduli",
  "organizer_type": "Yayasan Sosial Terverifikasi",
  "organizer_description": "Lembaga pelayanan kesehatan masyarakat.",
  "is_featured": true
}
```

---

### 2.3 Category API (4 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 12 | `GET` | `/api/categories` | Public | Daftar seluruh kategori beserta jumlah kampanye aktif |
| 13 | `POST` | `/api/admin/categories` | Admin | Membuat kategori baru |
| 14 | `PUT` | `/api/admin/categories/{id}` | Admin | Mengubah kategori |
| 15 | `DELETE` | `/api/admin/categories/{id}` | Admin | Menghapus kategori jika belum digunakan oleh kampanye |

---

### 2.4 Donation API (5 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 16 | `POST` | `/api/donations` | Public / Auth | Membuat donasi baru (Donatur Terdaftar atau Guest `user_id = NULL`) |
| 17 | `GET` | `/api/donations` | Auth | Riwayat donasi pribadi pengguna yang sedang login |
| 18 | `GET` | `/api/donations/{id}` | Owner | Detail satu transaksi donasi pribadi |
| 19 | `GET` | `/api/campaigns/{id}/donations` | Public | Daftar donasi & doa publik (**Doa-doa Orang Baik**, hanya status `success`) |
| 20 | `GET` | `/api/admin/donations` | Admin | Daftar seluruh transaksi donasi (Terdaftar & Guest) |

#### Contoh Request `POST /api/donations`
```json
{
  "campaign_id": 1,
  "donor_name": "Andi Pratama",
  "donor_email": "andi@example.com",
  "amount": 100000,
  "payment_method": "qris",
  "donor_message": "Semoga bantuan ini bermanfaat.",
  "is_anonymous": false
}
```

#### Contoh Response `201 Created`
```json
{
  "success": true,
  "message": "Transaksi donasi berhasil dibuat. Silakan lanjutkan simulasi pembayaran.",
  "data": {
    "donation": {
      "id": 10,
      "campaign_id": 1,
      "user_id": null,
      "is_guest": true,
      "donor_name": "Andi Pratama",
      "amount": 100000,
      "donor_message": "Semoga bantuan ini bermanfaat.",
      "is_anonymous": false,
      "status": "pending"
    },
    "payment": {
      "reference": "KP-20261121-ABCD1234",
      "payment_method": "qris",
      "amount": 100000,
      "status": "pending",
      "instruction": {
        "method_code": "qris",
        "method_label": "QRIS (Simulasi / Demo)",
        "is_simulation": true,
        "simulation_notice": "SIMULASI / DEMO: Bukan pembayaran nyata",
        "reference": "KP-20261121-ABCD1234",
        "amount": 100000,
        "formatted_amount": "Rp 100.000"
      }
    },
    "transaction_access": {
      "reference": "KP-20261121-ABCD1234",
      "access_token": "plain_secret_transaction_token_48_chars...",
      "header_name": "X-Transaction-Token"
    }
  }
}
```

---

### 2.5 Payment API (3 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 21 | `GET` | `/api/payments/{reference}` | Owner / Guest Token | Melihat instruksi dan status pembayaran simulasi |
| 22 | `POST` | `/api/payments/{reference}/simulate-success` | Owner / Guest Token | Menjalankan simulasi konfirmasi pembayaran berhasil (`paid` & `success`) |
| 23 | `GET` | `/api/admin/payments` | Admin | Memantau seluruh transaksi pembayaran |

#### Contoh Response `POST /api/payments/{reference}/simulate-success` (`200 OK`)
```json
{
  "success": true,
  "message": "Simulasi pembayaran berhasil dikonfirmasi. Status pembayaran menjadi paid dan donasi menjadi success.",
  "data": {
    "reference": "KP-20261121-ABCD1234",
    "payment_method": "qris",
    "amount": 100000,
    "payment_status": "paid",
    "donation_status": "success",
    "campaign": {
      "id": 1,
      "title": "Bantu Pengobatan & Nutrisi Anak Pejuang Kesehatan (Demo)",
      "collected_amount": 1600000,
      "donor_count": 4
    }
  }
}
```

---

### 2.6 Campaign Update API (4 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 24 | `GET` | `/api/campaigns/{id}/updates` | Public | Daftar kabar terbaru kampanye |
| 25 | `POST` | `/api/admin/campaigns/{id}/updates` | Admin | Membuat kabar terbaru kampanye |
| 26 | `PUT` | `/api/admin/updates/{id}` | Admin | Mengubah kabar terbaru |
| 27 | `DELETE` | `/api/admin/updates/{id}` | Admin | Menghapus kabar terbaru |

---

### 2.7 Disbursement Report API (4 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 28 | `GET` | `/api/campaigns/{id}/disbursement-reports` | Public | Daftar laporan penyaluran dana kampanye |
| 29 | `POST` | `/api/admin/campaigns/{id}/disbursement-reports` | Admin | Mencatat laporan penyaluran dana baru |
| 30 | `PUT` | `/api/admin/disbursement-reports/{id}` | Admin | Mengubah laporan penyaluran dana |
| 31 | `DELETE` | `/api/admin/disbursement-reports/{id}` | Admin | Menghapus laporan penyaluran dana |

---

### 2.8 Donation Reaction API (2 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 32 | `POST` | `/api/donations/{id}/amen` | Auth Donatur | Memberikan Aamiin pada doa donasi (maksimal 1x per user per doa) |
| 33 | `DELETE` | `/api/donations/{id}/amen` | Auth Donatur | Membatalkan Aamiin pada doa donasi |

---

### 2.9 Dashboard dan Report API (2 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 34 | `GET` | `/api/admin/dashboard` | Admin | Statistik lengkap dashboard Admin |
| 35 | `GET` | `/api/admin/reports/donations` | Admin | Laporan rekapitulasi donasi berhasil (`?campaign_id=&payment_method=&start_date=&end_date=`) |

---

### 2.10 User Management API (3 Endpoint)

| No | Method | Endpoint | Akses | Deskripsi |
|---:|---|---|---|---|
| 36 | `GET` | `/api/admin/users` | Admin | Daftar pengguna terdaftar (`admin` & `donor`) |
| 37 | `GET` | `/api/admin/users/{id}` | Admin | Detail pengguna beserta riwayat donasinya |
| 38 | `PATCH` | `/api/admin/users/{id}/status` | Admin | Mengubah status akun (`active` / `inactive`) |
