<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\Category;
use App\Models\Donation;
use App\Models\FundDisbursementReport;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransparencyAndDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_prayers_hide_anonymous_identity_and_only_show_successful_donations(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Pendidikan', 'slug' => 'pendidikan']);
        $campaign = Campaign::create([
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'title' => 'Kampanye Transparansi',
            'slug' => 'kampanye-transparansi',
            'story' => 'Cerita kampanye.',
            'target_amount' => 20000000,
            'end_date' => now()->addDays(20)->toDateString(),
            'organizer_name' => 'Yayasan Transparan',
            'status' => Campaign::STATUS_ACTIVE,
        ]);

        $successAnon = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => null,
            'donor_name' => 'Nama Rahasia Tidak Boleh Bocor',
            'donor_email' => 'rahasia@example.com',
            'amount' => 100000,
            'donor_message' => 'Semoga berkah selalu.',
            'is_anonymous' => true,
            'status' => Donation::STATUS_SUCCESS,
        ]);

        Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => null,
            'donor_name' => 'Donatur Pending',
            'amount' => 500000,
            'donor_message' => 'Doa yang belum dibayar.',
            'is_anonymous' => false,
            'status' => Donation::STATUS_PENDING,
        ]);

        $response = $this->getJson("/api/campaigns/{$campaign->id}/donations");

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $successAnon->id)
            ->assertJsonPath('data.0.donor_name', 'Orang Baik (Anonim)')
            ->assertJsonMissing(['donor_email' => 'rahasia@example.com'])
            ->assertJsonMissing(['donor_name' => 'Nama Rahasia Tidak Boleh Bocor']);
    }

    public function test_amen_reaction_rules_for_guest_registered_donor_and_duplicate_prevention(): void
    {
        $admin = User::factory()->admin()->create();
        $donor = User::factory()->create(['role' => User::ROLE_DONOR]);
        $category = Category::create(['name' => 'Kemanusiaan', 'slug' => 'kemanusiaan']);

        $campaign = Campaign::create([
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'title' => 'Kampanye Doa',
            'slug' => 'kampanye-doa',
            'story' => 'Cerita.',
            'target_amount' => 10000000,
            'end_date' => now()->addDays(10)->toDateString(),
            'organizer_name' => 'Pengelola Doa',
            'status' => Campaign::STATUS_ACTIVE,
        ]);

        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => null,
            'donor_name' => 'Budi',
            'amount' => 50000,
            'donor_message' => 'Semoga dimudahkan.',
            'status' => Donation::STATUS_SUCCESS,
        ]);

        $this->postJson("/api/donations/{$donation->id}/amen")
            ->assertUnauthorized();

        $this->actingAs($donor, 'sanctum')
            ->postJson("/api/donations/{$donation->id}/amen")
            ->assertCreated()
            ->assertJsonPath('data.amen_count', 1);

        $this->actingAs($donor, 'sanctum')
            ->postJson("/api/donations/{$donation->id}/amen")
            ->assertStatus(422);

        $this->assertEquals(1, $donation->fresh()->amen_count);

        $this->actingAs($donor, 'sanctum')
            ->deleteJson("/api/donations/{$donation->id}/amen")
            ->assertOk()
            ->assertJsonPath('data.amen_count', 0);
    }

    public function test_admin_can_manage_updates_disbursements_and_view_accurate_dashboard_stats(): void
    {
        $admin = User::factory()->admin()->create();
        $donor = User::factory()->create();
        $category = Category::create(['name' => 'Lingkungan', 'slug' => 'lingkungan']);

        $campaign = Campaign::create([
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'title' => 'Kampanye Mangrove',
            'slug' => 'kampanye-mangrove',
            'story' => 'Cerita.',
            'target_amount' => 15000000,
            'end_date' => now()->addDays(30)->toDateString(),
            'organizer_name' => 'Komunitas Hijau',
            'status' => Campaign::STATUS_ACTIVE,
        ]);

        $donQris = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => $donor->id,
            'donor_name' => $donor->name,
            'amount' => 100000,
            'status' => Donation::STATUS_SUCCESS,
        ]);
        Payment::create([
            'donation_id' => $donQris->id,
            'payment_method' => Payment::METHOD_QRIS,
            'reference' => 'KP-TEST-QRIS-RPT',
            'access_token_hash' => hash('sha256', 't1'),
            'amount' => 100000,
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
        ]);

        $donBank = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => null,
            'donor_name' => 'Guest Peduli',
            'amount' => 250000,
            'status' => Donation::STATUS_SUCCESS,
        ]);
        Payment::create([
            'donation_id' => $donBank->id,
            'payment_method' => Payment::METHOD_BANK_TRANSFER,
            'reference' => 'KP-TEST-BANK-RPT',
            'access_token_hash' => hash('sha256', 't2'),
            'amount' => 250000,
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
        ]);

        Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => null,
            'donor_name' => 'Guest Pending',
            'amount' => 900000,
            'status' => Donation::STATUS_PENDING,
        ]);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/admin/campaigns/{$campaign->id}/updates", [
                'title' => 'Penanaman 1.000 Bibit Pertama',
                'content' => 'Telah dilaksanakan penanaman tahap pertama.',
            ])->assertCreated();

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/admin/campaigns/{$campaign->id}/disbursement-reports", [
                'title' => 'Pencairan Bibit Mangrove',
                'amount' => 150000,
                'recipient_name' => 'Kelompok Tani Mangrove',
                'purpose' => 'Pembelian bibit',
                'description' => 'Catatan penyaluran simulasi.',
                'disbursed_at' => now()->toDateString(),
            ])->assertCreated();

        // Pencairan melebihi sisa saldo (350.000 - 150.000 = 200.000) harus ditolak.
        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/admin/campaigns/{$campaign->id}/disbursement-reports", [
                'title' => 'Pencairan Berlebih',
                'amount' => 250000,
                'recipient_name' => 'Kelompok Tani Mangrove',
                'purpose' => 'Pembelian tambahan',
                'description' => 'Harus ditolak karena saldo tidak mencukupi.',
                'disbursed_at' => now()->toDateString(),
            ])->assertStatus(422)
            ->assertJsonPath('success', false);

        // Filter laporan per metode pembayaran harus ikut menyaring ringkasan metode.
        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/donations?payment_method=qris')
            ->assertOk()
            ->assertJsonPath('data.summary.total_successful_amount', 100000)
            ->assertJsonPath('data.summary.by_payment_method.qris', 100000)
            ->assertJsonPath('data.summary.by_payment_method.bank_transfer', 0);

        // Route berparameter numerik yang diberi nilai bukan angka harus mengembalikan JSON 404.
        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/users/bukan-angka')
            ->assertNotFound()
            ->assertJsonPath('success', false);

        $dashResponse = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/dashboard');

        $dashResponse->assertOk()
            ->assertJsonPath('data.statistics.total_collected_funds', 350000)
            ->assertJsonPath('data.statistics.successful_donations_count', 2)
            ->assertJsonPath('data.statistics.pending_donations_count', 1)
            ->assertJsonPath('data.statistics.guest_transactions_count', 2);
    }

    public function test_admin_can_manage_disbursement_crud_and_edit_campaign_updates_on_web_and_api(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Kemanusiaan', 'slug' => 'kemanusiaan']);
        $campaign = Campaign::create([
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'title' => 'Kampanye Penyaluran',
            'slug' => 'kampanye-penyaluran',
            'story' => 'Cerita.',
            'target_amount' => 10000000,
            'end_date' => now()->addDays(20)->toDateString(),
            'organizer_name' => 'Yayasan Peduli',
            'status' => Campaign::STATUS_ACTIVE,
        ]);

        Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => null,
            'donor_name' => 'Donatur Uji',
            'amount' => 1000000,
            'status' => Donation::STATUS_SUCCESS,
        ]);

        $apiCreate = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/admin/campaigns/{$campaign->id}/disbursement-reports", [
                'title' => 'Pencairan API Awal',
                'amount' => 200000,
                'recipient_name' => 'Posko Utama',
                'purpose' => 'Paket pangan',
                'description' => 'Tahap awal.',
                'disbursed_at' => now()->toDateString(),
            ])->assertCreated();

        $apiReportId = $apiCreate->json('data.id');

        $this->actingAs($admin, 'sanctum')
            ->putJson("/api/admin/disbursement-reports/{$apiReportId}", [
                'title' => 'Pencairan API Diubah',
                'amount' => 250000,
                'recipient_name' => 'Posko Cabang',
                'purpose' => 'Paket pangan lanjutan',
                'description' => 'Tahap revisi.',
                'disbursed_at' => now()->toDateString(),
            ])->assertOk()
            ->assertJsonPath('data.title', 'Pencairan API Diubah');

        $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/admin/disbursement-reports/{$apiReportId}")
            ->assertOk();

        $this->actingAs($admin)
            ->post('/admin/penyaluran-dana', [
                'campaign_id' => $campaign->id,
                'title' => 'Pencairan Web Awal',
                'amount' => 150000,
                'recipient_name' => 'Relawan Lapangan',
                'purpose' => 'Logistik darurat',
                'description' => 'Distribusi hari pertama.',
                'disbursed_at' => now()->toDateString(),
            ])->assertRedirect();

        $webReport = FundDisbursementReport::latest('id')->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin/penyaluran-dana')
            ->assertOk()
            ->assertSee('data-edit-disbursement', false);

        $this->actingAs($admin)
            ->put("/admin/penyaluran-dana/{$webReport->id}", [
                'title' => 'Pencairan Web Revisi',
                'amount' => 175000,
                'recipient_name' => 'Relawan Lapangan 2',
                'purpose' => 'Logistik revisi',
                'description' => 'Distribusi diperbarui.',
                'disbursed_at' => now()->toDateString(),
            ])->assertRedirect();

        $this->assertSame('Pencairan Web Revisi', $webReport->fresh()->title);

        $this->actingAs($admin)
            ->delete("/admin/penyaluran-dana/{$webReport->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('fund_disbursement_reports', ['id' => $webReport->id]);

        $update = CampaignUpdate::create([
            'campaign_id' => $campaign->id,
            'created_by' => $admin->id,
            'title' => 'Kabar Lama',
            'content' => 'Konten lama.',
            'published_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/kabar-terbaru')
            ->assertOk()
            ->assertSee('data-edit-update', false);

        $this->actingAs($admin)
            ->put("/admin/kabar-terbaru/{$update->id}", [
                'title' => 'Kabar Sudah Diedit',
                'content' => 'Konten sudah diperbarui.',
            ])->assertRedirect();

        $this->assertSame('Kabar Sudah Diedit', $update->fresh()->title);
    }

    public function test_admin_donation_report_validates_date_filter_parameters(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/donations?start_date=bukan-tanggal')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['start_date']);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/donations?end_date=2026-99-99')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['end_date']);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/donations?start_date=2026-05-10&end_date=2026-05-01')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['end_date']);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/donations?end_date=2026-05-10')
            ->assertOk();
    }
}
