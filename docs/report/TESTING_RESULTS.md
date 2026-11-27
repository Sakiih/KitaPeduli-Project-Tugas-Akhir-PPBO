# Hasil Pengujian Aktual (Testing Results): KitaPeduli

Dokumen ini mencatat hasil pengujian aktual yang telah dijalankan pada workspace proyek **KitaPeduli** (Laravel 13).

---

## 1. Ringkasan Eksekusi Automated Testing (`php artisan test`)

- **Command**: `php artisan test`
- **Framework Test**: PHPUnit `12.5.45` (Laravel 13 Testing Suite)
- **Status Akhir**: **PASS (28 Tests Passed, 0 Failed, 240 Assertions)**
- **Durasi Eksekusi**: `2.23s`

### Daftar Test Suite yang Dijalankan

| No | Test Class | Test Case | Status | Cakupan Verifikasi |
|---:|---|---|---:|---|
| 1 | `Tests\Unit\ExampleTest` | `test_that_true_is_true` | **PASS** | Pemeriksaan dasar unit test |
| 2 | `Tests\Unit\PaymentOopAndSolidTest` | `test_payment_method_is_abstract_and_implements_interface` | **PASS** | Memastikan `PaymentMethod` adalah `abstract class` dan mengimplementasikan `PaymentMethodInterface` |
| 3 | `Tests\Unit\PaymentOopAndSolidTest` | `test_qris_and_bank_transfer_inherit_from_payment_method` | **PASS** | Memastikan `QrisPayment` dan `BankTransferPayment` mewarisi `PaymentMethod` |
| 4 | `Tests\Unit\PaymentOopAndSolidTest` | `test_polymorphism_create_instruction_produces_distinct_simulation_instructions` | **PASS** | Memastikan pemanggilan polimorfik `createInstruction()` menghasilkan payload QRIS vs nomor rekening Virtual Account simulasi yang berbeda |
| 5 | `Tests\Unit\PaymentOopAndSolidTest` | `test_resolver_rejects_unsupported_payment_method` | **PASS** | Memastikan `PaymentMethodResolver` melempar `InvalidArgumentException` untuk kode metode di luar kontrak |
| 6 | `Tests\Unit\PaymentOopAndSolidTest` | `test_encapsulation_on_donation_and_payment_models` | **PASS** | Memastikan `publicDonorName()`, `verifyAccessToken()`, dan proteksi `markAsPaid()` bekerja dengan benar |
| 7 | `Tests\Feature\AuthAndRoleTest` | `test_donor_can_register_via_api_and_cannot_escalate_role_to_admin` | **PASS** | Registrasi publik berhasil (`201`) dan selalu menghasilkan `role = donor` meskipun klien mengirim `role = admin` |
| 8 | `Tests\Feature\AuthAndRoleTest` | `test_admin_and_donor_can_login_and_logout_via_api` | **PASS** | Login, pengambilan profil `/api/me`, dan pencabutan token `/api/logout` berhasil |
| 9 | `Tests\Feature\AuthAndRoleTest` | `test_regular_donor_and_guest_cannot_access_admin_endpoints_or_pages` | **PASS** | Guest ditolak (`401`) dan Donatur biasa ditolak (`403`) saat mengakses route Admin |
| 10 | `Tests\Feature\AuthAndRoleTest` | `test_inactive_user_cannot_login_and_api_returns_json_without_accept_header` | **PASS** | Akun nonaktif ditolak (`403`) dan endpoint API tetap mengembalikan JSON tanpa header `Accept: application/json` |
| 11 | `Tests\Feature\AuthAndRoleTest` | `test_api_500_response_hides_internal_exception_details_even_when_debug_is_enabled` | **PASS** | Respons JSON `500` API tetap terstruktur aman dan tidak membocorkan nama kelas exception atau pesan galat internal |
| 12 | `Tests\Feature\CampaignAndCategoryTest` | `test_public_can_browse_search_and_filter_active_campaigns` | **PASS** | Publik hanya melihat kampanye aktif; pencarian dan filter kategori berjalan akurat |
| 13 | `Tests\Feature\CampaignAndCategoryTest` | `test_admin_can_create_update_and_archive_campaign_with_donations` | **PASS** | Admin dapat membuat kampanye; penghapusan kampanye yang sudah punya donasi otomatis mengarsipkan (`status = inactive`) agar histori tetap utuh |
| 14 | `Tests\Feature\CampaignAndCategoryTest` | `test_numeric_prefixed_slug_does_not_collide_with_campaign_id` | **PASS** | Slug berawalan angka tetap menyelesaikan rute kampanye secara akurat tanpa bertabrakan dengan ID numerik |
| 15 | `Tests\Feature\CampaignAndCategoryTest` | `test_category_name_must_be_unique_except_own_record_on_update` | **PASS** | Validasi `unique:categories,name` menolak duplikasi kategori dan tetap mengizinkan pembaruan kategori dengan namanya sendiri |
| 16 | `Tests\Feature\CampaignAndCategoryTest` | `test_api_campaigns_sort_latest_orders_by_created_at_desc_without_featured_override` | **PASS** | Parameter `sort=latest` pada API dan Web mengurutkan kampanye berdasarkan `created_at DESC` tanpa mendahulukan `is_featured`, serta badge detail menandai kampanye lewat tenggat sebagai `berakhir` |
| 17 | `Tests\Feature\CampaignAndCategoryTest` | `test_campaign_update_validates_date_range_including_partial_updates` | **PASS** | Validasi `end_date >= start_date` berlaku termasuk pada partial update ketika hanya satu field tanggal dikirim |
| 18 | `Tests\Feature\DonationAndPaymentSimulationTest` | `test_guest_can_donate_with_null_user_id_and_simulate_qris_payment` | **PASS** | Guest dapat berdonasi (`user_id = NULL`), mengakses transaksi via `X-Transaction-Token` (menolak query-string token), menyimulasikan QRIS (`paid` & `success`), dan double-submit ditolak (`422`) |
| 19 | `Tests\Feature\DonationAndPaymentSimulationTest` | `test_registered_donor_donation_links_user_id_and_protects_personal_history` | **PASS** | Donasi terdaftar terhubung ke `user_id` akun yang login; Donatur lain ditolak (`403`) saat mencoba mengakses detail atau menyimulasikan transaksi milik pengguna lain |
| 20 | `Tests\Feature\DonationAndPaymentSimulationTest` | `test_invalid_amount_invalid_method_and_inactive_campaign_are_rejected` | **PASS** | Nominal negatif/nol, metode pembayaran di luar `qris`/`bank_transfer`, field wajib kosong, dan kampanye nonaktif ditolak (`422`) |
| 21 | `Tests\Feature\DonationAndPaymentSimulationTest` | `test_admin_can_view_payment_but_cannot_simulate_payment` | **PASS** | Admin dapat memantau informasi pembayaran (`200`) namun ditolak (`403`) saat mencoba menyimulasikan pembayaran |
| 22 | `Tests\Feature\DonationAndPaymentSimulationTest` | `test_inactive_admin_cannot_access_payment_via_service_or_api` | **PASS** | Admin berstatus nonaktif ditolak (`403`) di `PaymentService::canAccessPayment()` maupun endpoint pembayaran |
| 23 | `Tests\Feature\TransparencyAndDashboardTest` | `test_public_prayers_hide_anonymous_identity_and_only_show_successful_donations` | **PASS** | Doa-doa Orang Baik hanya menampilkan donasi `success`, menyembunyikan nama asli donatur anonim, dan tidak pernah menampilkan email donatur |
| 24 | `Tests\Feature\TransparencyAndDashboardTest` | `test_amen_reaction_rules_for_guest_registered_donor_and_duplicate_prevention` | **PASS** | Guest tidak bisa memberi Aamiin (`401`), Donatur terdaftar bisa memberi Aamiin (`201`), duplikasi Aamiin ditolak (`422`), dan pembatalan Aamiin berhasil (`200`) |
| 25 | `Tests\Feature\TransparencyAndDashboardTest` | `test_admin_can_manage_updates_disbursements_and_view_accurate_dashboard_stats` | **PASS** | CRUD Kabar Terbaru, Laporan Penyaluran Dana (menolak pencairan melebihi saldo kampanye dengan `422`), filter laporan, dan statistik Dashboard Admin akurat |
| 26 | `Tests\Feature\TransparencyAndDashboardTest` | `test_admin_can_manage_disbursement_crud_and_edit_campaign_updates_on_web_and_api` | **PASS** | Create/Update/Delete Penyaluran Dana di Web & API serta Edit Kabar Terbaru di Web berjalan normal tanpa HTTP 500 |
| 27 | `Tests\Feature\TransparencyAndDashboardTest` | `test_admin_donation_report_validates_date_filter_parameters` | **PASS** | Filter `start_date`, `end_date`, `payment_method`, dan `campaign_id` pada laporan donasi Web & API menolak input invalid |
| 28 | `Tests\Feature\WebIntegrationTest` | `test_web_pages_and_guest_donation_simulation_flow_work_end_to_end` | **PASS** | Pengujian end-to-end halaman Blade (Homepage, Detail 8 komponen, Form Donasi Guest, Simulasi Pembayaran Web, Panel Admin, Dashboard Donatur) |

---

## 2. Hasil Pemeriksaan Build, Database Migration, Gaya Kode, dan Route

1. **Migrasi & Seeder MySQL (`php artisan migrate:fresh --seed`)**:
   - Seluruh 11 file migrasi dieksekusi ulang pada database MySQL `kitapeduli` (Laragon `port 3306`).
   - `DatabaseSeeder` membuat 1 akun Admin, 3 akun Donatur Terdaftar, 6 Kategori, 6 Kampanye Demo, 5 Transaksi Donasi (Terdaftar & Guest) beserta Payment, Kabar Terbaru, Laporan Penyaluran Dana, dan Reaksi Aamiin.
   - Tidak ada perubahan skema/seed sehingga dump SQL `kitapeduli.sql` tetap valid.
2. **Standar Kode PSR-12 (`php vendor/bin/pint --test`)**:
   - Lulus (`passed`) dengan 0 pelanggaran pada seluruh berkas PHP.
3. **Build Aset Frontend (`npm.cmd run build`)**:
   - Vite `v7.3.8` mem-bundle `resources/css/app.css` dan `resources/js/app.js` ke `public/build/manifest.json` tanpa error (`518ms`).
4. **Pemeriksaan Route (`php artisan route:list`)**:
   - Seluruh **88 route** (termasuk **38 endpoint REST API** di `/api/*` dan seluruh halaman Web Blade) terdaftar dan terkompilasi tanpa konflik.
