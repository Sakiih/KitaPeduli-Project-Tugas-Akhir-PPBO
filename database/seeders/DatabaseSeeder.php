<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Contracts\PaymentResolverInterface;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\Category;
use App\Models\Donation;
use App\Models\DonationReaction;
use App\Models\FundDisbursementReport;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun pengguna awal (Admin dan Donatur terdaftar)
        $admin = User::updateOrCreate(
            ['email' => 'admin@kitapeduli.test'],
            [
                'name' => 'Admin Utama KitaPeduli',
                'phone' => '081100001111',
                'password' => 'password123',
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        $donorAndi = User::updateOrCreate(
            ['email' => 'andi@kitapeduli.test'],
            [
                'name' => 'Andi Pratama',
                'phone' => '081234567801',
                'password' => 'password123',
                'role' => User::ROLE_DONOR,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        $donorSiti = User::updateOrCreate(
            ['email' => 'siti@kitapeduli.test'],
            [
                'name' => 'Siti Rahmawati',
                'phone' => '081234567802',
                'password' => 'password123',
                'role' => User::ROLE_DONOR,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        $donorBudi = User::updateOrCreate(
            ['email' => 'budi@kitapeduli.test'],
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567803',
                'password' => 'password123',
                'role' => User::ROLE_DONOR,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        // Kategori kampanye
        $categoriesData = [
            [
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
                'icon' => 'heart-pulse',
                'description' => 'Bantuan biaya pengobatan medis, alat kesehatan, dan layanan klinik masyarakat.',
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'icon' => 'graduation-cap',
                'description' => 'Beasiswa pelajar, renovasi ruang belajar, dan penyediaan buku bacaan anak.',
            ],
            [
                'name' => 'Bencana Alam',
                'slug' => 'bencana-alam',
                'icon' => 'shield-alert',
                'description' => 'Tanggap darurat bencana, dapur umum, dan pemulihan hunian warga terdampak.',
            ],
            [
                'name' => 'Kemanusiaan',
                'slug' => 'kemanusiaan',
                'icon' => 'users',
                'description' => 'Paket pangan pokok, bantuan lansia prasejahtera, dan solidaritas sosial.',
            ],
            [
                'name' => 'Panti Asuhan',
                'slug' => 'panti-asuhan',
                'icon' => 'home',
                'description' => 'Dukungan gizi, perlengkapan sekolah, dan perbaikan fasilitas panti asuhan.',
            ],
            [
                'name' => 'Lingkungan',
                'slug' => 'lingkungan',
                'icon' => 'leaf',
                'description' => 'Penanaman pohon mangrove, sarana air bersih desa, dan konservasi alam.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // Kampanye donasi demonstrasi
        $campaignsData = [
            [
                'slug' => 'bantu-pengobatan-dan-nutrisi-anak-pejuang-kesehatan',
                'category_id' => $categories['kesehatan']->id,
                'title' => 'Bantu Pengobatan & Nutrisi Anak Pejuang Kesehatan (Demo)',
                'short_description' => 'Program demonstrasi KitaPeduli untuk mendukung biaya terapi medis dan paket nutrisi anak-anak keluarga prasejahtera.',
                'story' => "Kampanye ini merupakan data demonstrasi akademik pada aplikasi KitaPeduli.\n\nBanyak anak dari keluarga prasejahtera membutuhkan pendampingan pengobatan rutin, pemeriksaan laboratorium, serta asupan nutrisi khusus selama masa pemulihan. Keterbatasan biaya transportasi dan tindakan medis sering kali menghambat keberlanjutan terapi mereka.\n\nMelalui penggalangan dana di KitaPeduli, bantuan disalurkan untuk:\n1. Paket nutrisi pemulihan dan susu medis khusus anak.\n2. Subsidi biaya pemeriksaan berkala dan obat-obatan penunjang.\n3. Operasional rumah singgah pasien anak dari luar daerah.",
                'banner_image' => 'images/campaigns/kesehatan.svg',
                'target_amount' => 50000000,
                'start_date' => now()->subDays(15)->toDateString(),
                'end_date' => now()->addDays(45)->toDateString(),
                'organizer_name' => 'Yayasan Sehat Nusantara (Demo)',
                'organizer_type' => 'Yayasan Sosial Terverifikasi',
                'organizer_description' => 'Lembaga sosial bidang pendampingan pasien anak dan kesehatan masyarakat prasejahtera.',
                'is_featured' => true,
                'is_verified_organizer' => true,
                'status' => Campaign::STATUS_ACTIVE,
            ],
            [
                'slug' => 'renovasi-perpustakaan-dan-beasiswa-anak-pesisir',
                'category_id' => $categories['pendidikan']->id,
                'title' => 'Renovasi Perpustakaan & Beasiswa Anak Pesisir (Demo)',
                'short_description' => 'Hadirkan ruang baca yang layak, buku pelajaran baru, dan perlengkapan sekolah untuk 150 siswa desa pesisir.',
                'story' => "Kampanye demonstrasi KitaPeduli untuk pemerataan akses literasi dan pendidikan.\n\nAnak-anak di kawasan pesisir memiliki semangat belajar yang luar biasa, namun ruang baca komunitas yang mereka gunakan mengalami kerusakan atap dan kekurangan buku referensi.\n\nDana donasi akan dialokasikan untuk:\n1. Perbaikan atap, rak buku, dan meja baca taman literasi.\n2. Pengadaan 600 buku bacaan anak dan ensiklopedia sains.\n3. Paket tas, sepatu, dan alat tulis untuk 150 siswa.",
                'banner_image' => 'images/campaigns/pendidikan.svg',
                'target_amount' => 40000000,
                'start_date' => now()->subDays(20)->toDateString(),
                'end_date' => now()->addDays(35)->toDateString(),
                'organizer_name' => 'Komunitas Lentera Pendidikan (Demo)',
                'organizer_type' => 'Komunitas Pendidikan',
                'organizer_description' => 'Penggerak taman bacaan masyarakat dan beasiswa pendidikan dasar.',
                'is_featured' => true,
                'is_verified_organizer' => true,
                'status' => Campaign::STATUS_ACTIVE,
            ],
            [
                'slug' => 'dapur-umum-dan-paket-logistik-warga-terdampak-banjir',
                'category_id' => $categories['bencana-alam']->id,
                'title' => 'Dapur Umum & Paket Logistik Warga Terdampak Banjir (Demo)',
                'short_description' => 'Penyaluran makanan siap saji, air bersih, selimut, dan perlengkapan kebersihan untuk posko pengungsian.',
                'story' => "Kampanye demonstrasi tanggap darurat bencana alam pada platform KitaPeduli.\n\nCurah hujan tinggi menyebabkan ratusan keluarga harus mengungsi sementara ke posko darurat. Kebutuhan paling mendesak saat ini adalah makanan hangat siap saji, air minum bersih, popok bayi, dan obat-obatan ringan.\n\nSetiap kontribusi Anda disalurkan melalui tim relawan untuk operasional dapur umum selama masa tanggap darurat.",
                'banner_image' => 'images/campaigns/bencana-alam.svg',
                'target_amount' => 75000000,
                'start_date' => now()->subDays(10)->toDateString(),
                'end_date' => now()->addDays(25)->toDateString(),
                'organizer_name' => 'Tim Relawan Tanggap Bencana KitaPeduli (Demo)',
                'organizer_type' => 'Lembaga Kemanusiaan',
                'organizer_description' => 'Unit koordinasi bantuan logistik darurat dan dapur umum bencana.',
                'is_featured' => true,
                'is_verified_organizer' => true,
                'status' => Campaign::STATUS_ACTIVE,
            ],
            [
                'slug' => 'paket-sembako-dan-pemeriksaan-lansia-sebatang-kara',
                'category_id' => $categories['kemanusiaan']->id,
                'title' => 'Paket Sembako & Pemeriksaan Lansia Sebatang Kara (Demo)',
                'short_description' => 'Berbagi paket kebutuhan pokok bulanan dan kunjungan pemeriksaan kesehatan rutin bagi lansia prasejahtera.',
                'story' => "Kampanye demonstrasi kepedulian sosial kemanusiaan di KitaPeduli.\n\nProgram ini mendampingi para lansia prasejahtera agar tetap memperoleh kebutuhan pangan bergizi seperti beras, telur, minyak goreng, serta pemeriksaan tekanan darah dan vitamin bulanan.",
                'banner_image' => 'images/campaigns/kemanusiaan.svg',
                'target_amount' => 30000000,
                'start_date' => now()->subDays(12)->toDateString(),
                'end_date' => now()->addDays(50)->toDateString(),
                'organizer_name' => 'Sahabat Lansia Berdaya (Demo)',
                'organizer_type' => 'Organisasi Sosial',
                'organizer_description' => 'Organisasi pendamping kesejahteraan lansia prasejahtera.',
                'is_featured' => false,
                'is_verified_organizer' => true,
                'status' => Campaign::STATUS_ACTIVE,
            ],
            [
                'slug' => 'dukungan-gizi-dan-asrama-panti-asuhan-harapan-bangsa',
                'category_id' => $categories['panti-asuhan']->id,
                'title' => 'Dukungan Gizi & Asrama Panti Asuhan Harapan Bangsa (Demo)',
                'short_description' => 'Bantu pemenuhan kebutuhan pangan harian, kasur asrama, dan perlengkapan belajar untuk 45 anak asuh.',
                'story' => "Kampanye demonstrasi bantuan panti asuhan pada aplikasi KitaPeduli.\n\nPanti Asuhan Harapan Bangsa merawat 45 anak yatim dan dhuafa usia sekolah dasar hingga menengah. Donasi yang terkumpul digunakan untuk pengadaan bahan pangan bergizi, perbaikan tempat tidur asrama, serta biaya penunjang sekolah.",
                'banner_image' => 'images/campaigns/panti-asuhan.svg',
                'target_amount' => 35000000,
                'start_date' => now()->subDays(18)->toDateString(),
                'end_date' => now()->addDays(40)->toDateString(),
                'organizer_name' => 'Pengurus Panti Asuhan Harapan Bangsa (Demo)',
                'organizer_type' => 'Yayasan Panti Asuhan',
                'organizer_description' => 'Yayasan pengasuhan dan pendidikan anak yatim piatu.',
                'is_featured' => false,
                'is_verified_organizer' => true,
                'status' => Campaign::STATUS_ACTIVE,
            ],
            [
                'slug' => 'konservasi-10000-bibit-mangrove-dan-air-bersih-desa',
                'category_id' => $categories['lingkungan']->id,
                'title' => 'Konservasi 10.000 Bibit Mangrove & Air Bersih Desa (Demo)',
                'short_description' => 'Penanaman sabuk hijau mangrove penahan abrasi sekaligus instalasi filter air bersih warga pesisir.',
                'story' => "Kampanye demonstrasi pelestarian lingkungan hidup pada aplikasi KitaPeduli.\n\nBersama kelompok masyarakat pesisir, program ini melakukan pembibitan dan penanaman 10.000 pohon mangrove untuk menahan abrasi pantai serta membangun instalasi penyaringan air bersih komunal.",
                'banner_image' => 'images/campaigns/lingkungan.svg',
                'target_amount' => 45000000,
                'start_date' => now()->subDays(25)->toDateString(),
                'end_date' => now()->addDays(60)->toDateString(),
                'organizer_name' => 'Inisiatif Hijau Lestari (Demo)',
                'organizer_type' => 'Komunitas Lingkungan',
                'organizer_description' => 'Komunitas konservasi ekosistem pesisir dan air bersih desa.',
                'is_featured' => true,
                'is_verified_organizer' => true,
                'status' => Campaign::STATUS_ACTIVE,
            ],
        ];

        $campaigns = [];
        foreach ($campaignsData as $campData) {
            $campaigns[$campData['slug']] = Campaign::updateOrCreate(
                ['slug' => $campData['slug']],
                array_merge($campData, ['created_by' => $admin->id])
            );
        }

        // Transaksi donasi dan simulasi pembayaran awal
        /** @var PaymentResolverInterface $paymentResolver */
        $paymentResolver = app(PaymentResolverInterface::class);

        $firstCamp = $campaigns['bantu-pengobatan-dan-nutrisi-anak-pejuang-kesehatan'];
        $secondCamp = $campaigns['renovasi-perpustakaan-dan-beasiswa-anak-pesisir'];
        $thirdCamp = $campaigns['dapur-umum-dan-paket-logistik-warga-terdampak-banjir'];

        if ($firstCamp->donations()->count() === 0) {
            $seedDonations = [
                [
                    'campaign' => $firstCamp,
                    'user_id' => $donorAndi->id,
                    'donor_name' => $donorAndi->name,
                    'donor_email' => $donorAndi->email,
                    'amount' => 500000,
                    'donor_message' => 'Semoga adik-adik lekas pulih dan selalu diberi kekuatan serta kesehatan.',
                    'is_anonymous' => false,
                    'status' => Donation::STATUS_SUCCESS,
                    'payment_method' => Payment::METHOD_QRIS,
                    'payment_status' => Payment::STATUS_PAID,
                    'reference' => 'KP-DEMO-0001QRIS',
                ],
                [
                    'campaign' => $firstCamp,
                    'user_id' => $donorSiti->id,
                    'donor_name' => $donorSiti->name,
                    'donor_email' => $donorSiti->email,
                    'amount' => 750000,
                    'donor_message' => 'Titip doa terbaik untuk kesembuhan anak-anak pejuang kesehatan. Semangat!',
                    'is_anonymous' => true,
                    'status' => Donation::STATUS_SUCCESS,
                    'payment_method' => Payment::METHOD_BANK_TRANSFER,
                    'payment_status' => Payment::STATUS_PAID,
                    'reference' => 'KP-DEMO-0002BANK',
                ],
                [
                    'campaign' => $firstCamp,
                    'user_id' => null,
                    'donor_name' => 'Rizky Hidayat (Guest)',
                    'donor_email' => 'rizky.guest@example.com',
                    'amount' => 250000,
                    'donor_message' => 'Semoga bantuan simulasi ini bermanfaat untuk kebutuhan nutrisi adik-adik.',
                    'is_anonymous' => false,
                    'status' => Donation::STATUS_SUCCESS,
                    'payment_method' => Payment::METHOD_QRIS,
                    'payment_status' => Payment::STATUS_PAID,
                    'reference' => 'KP-DEMO-0003GUES',
                ],
                [
                    'campaign' => $secondCamp,
                    'user_id' => $donorBudi->id,
                    'donor_name' => $donorBudi->name,
                    'donor_email' => $donorBudi->email,
                    'amount' => 1000000,
                    'donor_message' => 'Semoga perpustakaan desa segera selesai direnovasi dan anak-anak makin rajin membaca.',
                    'is_anonymous' => false,
                    'status' => Donation::STATUS_SUCCESS,
                    'payment_method' => Payment::METHOD_BANK_TRANSFER,
                    'payment_status' => Payment::STATUS_PAID,
                    'reference' => 'KP-DEMO-0004EDUC',
                ],
                [
                    'campaign' => $thirdCamp,
                    'user_id' => null,
                    'donor_name' => 'Dewi Lestari',
                    'donor_email' => 'dewi.guest@example.com',
                    'amount' => 600000,
                    'donor_message' => 'Semoga warga yang terdampak banjir diberikan keselamatan dan kemudahan.',
                    'is_anonymous' => true,
                    'status' => Donation::STATUS_SUCCESS,
                    'payment_method' => Payment::METHOD_QRIS,
                    'payment_status' => Payment::STATUS_PAID,
                    'reference' => 'KP-DEMO-0005FLOD',
                ],
            ];

            foreach ($seedDonations as $item) {
                $donation = Donation::create([
                    'campaign_id' => $item['campaign']->id,
                    'user_id' => $item['user_id'],
                    'donor_name' => $item['donor_name'],
                    'donor_email' => $item['donor_email'],
                    'amount' => $item['amount'],
                    'donor_message' => $item['donor_message'],
                    'is_anonymous' => $item['is_anonymous'],
                    'status' => $item['status'],
                    'amen_count' => 0,
                ]);

                $donation->setRelation('campaign', $item['campaign']);

                $plainToken = 'demo-token-'.strtolower($item['reference']);
                $payment = Payment::create([
                    'donation_id' => $donation->id,
                    'payment_method' => $item['payment_method'],
                    'reference' => $item['reference'],
                    'access_token_hash' => hash('sha256', $plainToken),
                    'amount' => $item['amount'],
                    'status' => $item['payment_status'],
                    'paid_at' => $item['payment_status'] === Payment::STATUS_PAID ? now()->subHours(6) : null,
                ]);

                $gateway = $paymentResolver->resolve($payment->payment_method);
                $payment->instruction_payload = $gateway->createInstruction($payment, $donation);
                $payment->save();

                DonationReaction::firstOrCreate([
                    'donation_id' => $donation->id,
                    'user_id' => $donorAndi->id,
                ]);
                DonationReaction::firstOrCreate([
                    'donation_id' => $donation->id,
                    'user_id' => $donorSiti->id,
                ]);
                $donation->refreshAmenCount();
            }

            // Kabar terbaru demonstrasi
            CampaignUpdate::create([
                'campaign_id' => $firstCamp->id,
                'created_by' => $admin->id,
                'title' => 'Penyaluran Tahap 1: Paket Nutrisi & Pendampingan Medis',
                'content' => 'Terima kasih kepada para Orang Baik di KitaPeduli. Tim pengelola telah menyalurkan paket nutrisi khusus tahap pertama kepada 15 anak dampingan serta memfasilitasi kontrol rutin di rumah sakit rujukan.',
                'published_at' => now()->subDays(3),
            ]);

            CampaignUpdate::create([
                'campaign_id' => $secondCamp->id,
                'created_by' => $admin->id,
                'title' => 'Pengadaan 250 Buku Bacaan Anak & Rak Kayu Baru',
                'content' => 'Proses perbaikan ruang baca berjalan lancar. Minggu ini sebanyak 250 buku cerita dan ensiklopedia sains anak telah tiba di lokasi perpustakaan pesisir.',
                'published_at' => now()->subDays(2),
            ]);

            // Laporan penyaluran dana demonstrasi
            FundDisbursementReport::create([
                'campaign_id' => $firstCamp->id,
                'created_by' => $admin->id,
                'title' => 'Pencairan Dana Tahap 1: Pembelian Paket Nutrisi Medis (Simulasi)',
                'amount' => 750000,
                'recipient_name' => 'Yayasan Sehat Nusantara (Posko Pendampingan Anak)',
                'purpose' => 'Pengadaan 15 paket susu medis dan suplemen pemulihan pasien anak',
                'description' => 'Catatan transparansi penyaluran dana simulasi untuk pembelian kebutuhan nutrisi medis khusus anak-anak dampingan tahap pertama.',
                'disbursed_at' => now()->subDays(2)->toDateString(),
            ]);

            FundDisbursementReport::create([
                'campaign_id' => $secondCamp->id,
                'created_by' => $admin->id,
                'title' => 'Pencairan Dana Tahap 1: Pengadaan Buku dan Material Rak (Simulasi)',
                'amount' => 500000,
                'recipient_name' => 'Komunitas Lentera Pendidikan Pesisir',
                'purpose' => 'Pembelian paket buku literasi dasar dan perlengkapan rak baca',
                'description' => 'Catatan laporan penyaluran dana simulasi untuk mendukung fasilitas perpustakaan anak pesisir.',
                'disbursed_at' => now()->subDay()->toDateString(),
            ]);
        }

        // Hitung ulang dana terkumpul dan jumlah donatur pada seluruh kampanye
        foreach ($campaigns as $campaign) {
            $campaign->recalculateCollectedAmount();
        }
    }
}
