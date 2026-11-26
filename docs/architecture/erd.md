# Entity Relationship Diagram (ERD): KitaPeduli

Dokumen ini mendefinisikan struktur skema database MySQL (`kitapeduli`) yang terdiri dari **8 tabel inti** sesuai batasan ruang lingkup proyek. Tidak ada tabel `guest_users`, `fundraisers`, atau `public_donors`.

---

## 1. Diagram ERD (Mermaid)

```mermaid
erDiagram
    users ||--o{ campaigns : "creates (created_by)"
    users ||--o{ donations : "donates (nullable user_id)"
    users ||--o{ donation_reactions : "gives_amen"
    users ||--o{ campaign_updates : "publishes (created_by)"
    users ||--o{ fund_disbursement_reports : "records (created_by)"

    categories ||--o{ campaigns : "categorizes"

    campaigns ||--o{ donations : "receives"
    campaigns ||--o{ campaign_updates : "has_updates"
    campaigns ||--o{ fund_disbursement_reports : "has_disbursements"

    donations ||--|| payments : "has_payment"
    donations ||--o{ donation_reactions : "receives_amens"

    users {
        bigint id PK
        string name
        string email UK
        string phone "nullable"
        string password
        string role "admin | donor (default: donor)"
        string status "active | inactive (default: active)"
        timestamp email_verified_at "nullable"
        timestamps created_at_updated_at
    }

    categories {
        bigint id PK
        string name
        string slug UK
        string icon "nullable"
        text description "nullable"
        timestamps created_at_updated_at
    }

    campaigns {
        bigint id PK
        bigint category_id FK
        bigint created_by FK "users.id (Admin)"
        string title
        string slug UK
        string short_description "nullable"
        longtext story
        string banner_image "nullable"
        decimal target_amount "15,2"
        decimal collected_amount "15,2 (default: 0.00)"
        integer donor_count "default: 0"
        date start_date "nullable"
        date end_date
        string organizer_name
        string organizer_type "nullable (Yayasan/Komunitas/Lembaga)"
        text organizer_description "nullable"
        boolean is_featured "default: false"
        boolean is_verified_organizer "default: true"
        string status "active | completed | inactive"
        timestamps created_at_updated_at
    }

    donations {
        bigint id PK
        bigint campaign_id FK
        bigint user_id FK "nullable (NULL untuk Guest)"
        string donor_name
        string donor_email "nullable"
        string donor_phone "nullable"
        decimal amount "15,2 (positif)"
        text donor_message "nullable (Doa Orang Baik)"
        boolean is_anonymous "default: false"
        string status "pending | success | cancelled"
        integer amen_count "default: 0"
        timestamps created_at_updated_at
    }

    payments {
        bigint id PK
        bigint donation_id FK_UK "One-to-One dengan donations"
        string payment_method "qris | bank_transfer"
        string reference UK "Kode referensi unik (format KP-YYYYMMDD-XXXXXXXX)"
        string access_token_hash "SHA-256 hash untuk akses Guest/transaksi"
        decimal amount "15,2"
        string status "pending | paid"
        json instruction_payload "nullable (snapshot instruksi simulasi)"
        timestamp paid_at "nullable"
        timestamps created_at_updated_at
    }

    campaign_updates {
        bigint id PK
        bigint campaign_id FK
        bigint created_by FK "nullable users.id (Admin)"
        string title
        longtext content
        string image_path "nullable"
        timestamp published_at
        timestamps created_at_updated_at
    }

    fund_disbursement_reports {
        bigint id PK
        bigint campaign_id FK
        bigint created_by FK "nullable users.id (Admin)"
        string title
        decimal amount "15,2"
        string recipient_name
        string purpose
        longtext description
        string document_image "nullable"
        date disbursed_at
        timestamps created_at_updated_at
    }

    donation_reactions {
        bigint id PK
        bigint donation_id FK
        bigint user_id FK "Wajib Donatur Terdaftar"
        timestamps created_at_updated_at
    }
```

---

## 2. Spesifikasi Tabel, Constraint, dan Indeks

### 2.1 `users`
- **Tujuan**: Menyimpan akun Admin (`role = 'admin'`) dan Donatur Terdaftar (`role = 'donor'`). Guest **tidak** disimpan di tabel ini.
- **Unique Constraint**: `email`.
- **Kolom Kunci**:
  - `role`: `admin` atau `donor` (default `donor`).
  - `status`: `active` atau `inactive` (default `active`).

### 2.2 `categories`
- **Tujuan**: Menyimpan kategori kampanye donasi (mis. Kesehatan, Pendidikan, Bencana Alam, Kemanusiaan, Panti Asuhan, Lingkungan).
- **Unique Constraint**: `slug` unik pada level database, sedangkan keunikan `name` ditegakkan pada lapisan aplikasi melalui validasi `StoreCategoryRequest` (`unique:categories,name`, dengan pengecualian ID kategori terkait saat pembaruan).
- **Relasi**: `1:N` ke `campaigns` (`ON DELETE RESTRICT` agar kategori yang masih memiliki kampanye tidak terhapus sembarangan).

### 2.3 `campaigns`
- **Tujuan**: Menyimpan data kampanye penggalangan dana beserta informasi pengelola kampanye (`organizer_name`, `organizer_type`, `organizer_description`).
- **Foreign Keys**:
  - `category_id` -> `categories.id` (`ON DELETE RESTRICT`)
  - `created_by` -> `users.id` (`ON DELETE RESTRICT`)
- **Tipe Data Uang**: `target_amount` dan `collected_amount` menggunakan `DECIMAL(15, 2)`.
- **Status**: `active`, `completed`, `inactive`.

### 2.4 `donations`
- **Tujuan**: Menyimpan seluruh transaksi donasi, baik dari Donatur Terdaftar maupun Guest.
- **Foreign Keys**:
  - `campaign_id` -> `campaigns.id` (`ON DELETE RESTRICT`)
  - `user_id` -> `users.id` (`NULLABLE`, `ON DELETE SET NULL`)
- **Aturan Bisnis Penting**:
  - Jika Donatur Terdaftar: `user_id` diisi dari akun yang sedang login.
  - Jika Guest: `user_id = NULL`, `donor_name` tetap wajib diisi.
  - `amount`: `DECIMAL(15, 2)` wajib positif.
  - `is_anonymous`: `BOOLEAN`, menyembunyikan nama asli pada tampilan publik ("Orang Baik / Donatur Anonim"), namun catatan asli tetap tersimpan untuk keperluan internal/administrasi.
  - `status`: `pending`, `success`, `cancelled`.

### 2.5 `payments`
- **Tujuan**: Menyimpan data pembayaran simulasi yang terhubung `1:1` dengan `donations`.
- **Foreign Keys & Unique Constraints**:
  - `donation_id` -> `donations.id` (`UNIQUE`, `ON DELETE CASCADE`)
  - `reference` -> `UNIQUE` (contoh: `KP-20261121-ABC12345`)
- **Keamanan Akses Guest**:
  - `access_token_hash` menyimpan hash SHA-256 dari token acak transaksi. Plain token hanya diberikan kepada pembuat transaksi saat donasi dibuat (melalui session atau response pembuatan donasi) dan dikirim via header `X-Transaction-Token` (atau session web) untuk mengakses detail/simulasi pembayaran.
- **Status**: `pending`, `paid`.

### 2.6 `campaign_updates`
- **Tujuan**: Menyimpan kabar terbaru perkembangan kampanye yang dikelola Admin.
- **Foreign Keys**:
  - `campaign_id` -> `campaigns.id` (`ON DELETE CASCADE`)
  - `created_by` -> `users.id` (`NULLABLE`, `ON DELETE SET NULL`)

### 2.7 `fund_disbursement_reports`
- **Tujuan**: Menyimpan catatan laporan penyaluran/pencairan dana kampanye sebagai bentuk transparansi informasi oleh Admin.
- **Foreign Keys**:
  - `campaign_id` -> `campaigns.id` (`ON DELETE CASCADE`)
  - `created_by` -> `users.id` (`NULLABLE`, `ON DELETE SET NULL`)
- **Tipe Data Uang**: `amount` menggunakan `DECIMAL(15, 2)`.

### 2.8 `donation_reactions`
- **Tujuan**: Menyimpan reaksi **Aamiin** dari Donatur Terdaftar pada pesan/doa donasi yang berhasil.
- **Foreign Keys**:
  - `donation_id` -> `donations.id` (`ON DELETE CASCADE`)
  - `user_id` -> `users.id` (`ON DELETE CASCADE`)
- **Unique Constraint**: Composite unique index `UNIQUE(donation_id, user_id)` untuk menjamin satu pengguna terdaftar hanya dapat memberikan maksimal satu Aamiin pada satu doa donasi.
