<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationAndPaymentSimulationTest extends TestCase
{
    use RefreshDatabase;

    private Campaign $activeCampaign;

    private Campaign $inactiveCampaign;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Kesehatan', 'slug' => 'kesehatan']);

        $this->activeCampaign = Campaign::create([
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'title' => 'Kampanye Aktif Pengujian',
            'slug' => 'kampanye-aktif-pengujian',
            'story' => 'Cerita kampanye aktif.',
            'target_amount' => 10000000,
            'collected_amount' => 0,
            'donor_count' => 0,
            'end_date' => now()->addDays(15)->toDateString(),
            'organizer_name' => 'Yayasan Penguji',
            'status' => Campaign::STATUS_ACTIVE,
        ]);

        $this->inactiveCampaign = Campaign::create([
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'title' => 'Kampanye Nonaktif Pengujian',
            'slug' => 'kampanye-nonaktif-pengujian',
            'story' => 'Cerita kampanye nonaktif.',
            'target_amount' => 10000000,
            'collected_amount' => 0,
            'donor_count' => 0,
            'end_date' => now()->addDays(15)->toDateString(),
            'organizer_name' => 'Yayasan Penguji',
            'status' => Campaign::STATUS_INACTIVE,
        ]);
    }

    public function test_guest_can_donate_with_null_user_id_and_simulate_qris_payment(): void
    {
        $response = $this->postJson('/api/donations', [
            'campaign_id' => $this->activeCampaign->id,
            'user_id' => 999,
            'donor_name' => 'Guest Orang Baik',
            'donor_email' => 'guest@example.com',
            'amount' => 200000,
            'payment_method' => 'qris',
            'donor_message' => 'Semoga lekas sembuh.',
            'is_anonymous' => false,
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.donation.user_id', null)
            ->assertJsonPath('data.donation.is_guest', true)
            ->assertJsonPath('data.donation.status', Donation::STATUS_PENDING)
            ->assertJsonPath('data.payment.status', Payment::STATUS_PENDING)
            ->assertJsonPath('data.payment.payment_method', 'qris');

        $reference = $response->json('data.payment.reference');
        $accessToken = $response->json('data.transaction_access.access_token');

        $this->assertEquals(0, (float) $this->activeCampaign->fresh()->collected_amount);

        $this->getJson("/api/payments/{$reference}")
            ->assertForbidden();

        $this->withHeader('X-Transaction-Token', 'token-palsu')
            ->getJson("/api/payments/{$reference}")
            ->assertForbidden();

        // Token pada query string URL tidak boleh diterima.
        $this->getJson("/api/payments/{$reference}?access_token={$accessToken}")
            ->assertForbidden();

        $this->withHeader('X-Transaction-Token', $accessToken)
            ->getJson("/api/payments/{$reference}")
            ->assertOk()
            ->assertJsonPath('data.instruction.method_code', 'qris');

        $simResponse = $this->withHeader('X-Transaction-Token', $accessToken)
            ->postJson("/api/payments/{$reference}/simulate-success");

        $simResponse->assertOk()
            ->assertJsonPath('data.payment_status', Payment::STATUS_PAID)
            ->assertJsonPath('data.donation_status', Donation::STATUS_SUCCESS)
            ->assertJsonPath('data.campaign.collected_amount', 200000);

        // Pengiriman ulang pada transaksi paid harus ditolak tanpa menggandakan saldo.
        $doubleSubmit = $this->withHeader('X-Transaction-Token', $accessToken)
            ->postJson("/api/payments/{$reference}/simulate-success");

        $doubleSubmit->assertStatus(422);
        $this->assertEquals(200000, (float) $this->activeCampaign->fresh()->collected_amount);
        $this->assertEquals(1, $this->activeCampaign->fresh()->donor_count);
    }

    public function test_registered_donor_donation_links_user_id_and_protects_personal_history(): void
    {
        $donorA = User::factory()->create(['name' => 'Donatur A']);
        $donorB = User::factory()->create(['name' => 'Donatur B']);

        $response = $this->actingAs($donorA, 'sanctum')
            ->postJson('/api/donations', [
                'campaign_id' => $this->activeCampaign->id,
                'donor_name' => 'Donatur A',
                'amount' => 350000,
                'payment_method' => 'bank_transfer',
                'donor_message' => 'Doa dari Donatur A',
                'is_anonymous' => true,
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.donation.user_id', $donorA->id)
            ->assertJsonPath('data.payment.payment_method', 'bank_transfer');

        $donationId = $response->json('data.donation.id');
        $reference = $response->json('data.payment.reference');

        $this->actingAs($donorB, 'sanctum')
            ->getJson("/api/donations/{$donationId}")
            ->assertForbidden();

        $this->actingAs($donorB, 'sanctum')
            ->postJson("/api/payments/{$reference}/simulate-success")
            ->assertForbidden();

        $this->actingAs($donorA, 'sanctum')
            ->postJson("/api/payments/{$reference}/simulate-success")
            ->assertOk()
            ->assertJsonPath('data.payment_status', 'paid');

        $this->actingAs($donorA, 'sanctum')
            ->getJson('/api/donations')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->actingAs($donorB, 'sanctum')
            ->getJson('/api/donations')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_invalid_amount_invalid_method_and_inactive_campaign_are_rejected(): void
    {
        $this->postJson('/api/donations', [
            'donor_name' => 'Tester',
            'amount' => 50000,
            'payment_method' => 'qris',
        ])->assertStatus(422);

        $this->postJson('/api/donations', [
            'campaign_id' => $this->activeCampaign->id,
            'donor_name' => 'Tester',
            'amount' => -5000,
            'payment_method' => 'qris',
        ])->assertStatus(422);

        $this->postJson('/api/donations', [
            'campaign_id' => $this->activeCampaign->id,
            'donor_name' => 'Tester',
            'amount' => 50000,
            'payment_method' => 'crypto',
        ])->assertStatus(422);

        $this->postJson('/api/donations', [
            'campaign_id' => $this->inactiveCampaign->id,
            'donor_name' => 'Tester',
            'amount' => 50000,
            'payment_method' => 'qris',
        ])->assertStatus(422);
    }

    public function test_admin_can_view_payment_but_cannot_simulate_payment(): void
    {
        $response = $this->postJson('/api/donations', [
            'campaign_id' => $this->activeCampaign->id,
            'donor_name' => 'Guest Pengujian',
            'amount' => 100000,
            'payment_method' => 'qris',
        ])->assertCreated();

        $reference = $response->json('data.payment.reference');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin, 'sanctum')
            ->getJson("/api/payments/{$reference}")
            ->assertOk();

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/payments/{$reference}/simulate-success")
            ->assertForbidden()
            ->assertJsonPath('success', false);
    }
}
