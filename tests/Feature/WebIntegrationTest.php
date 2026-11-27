<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_pages_and_guest_donation_simulation_flow_work_end_to_end(): void
    {
        $this->seed();

        $this->get('/')->assertOk()->assertSee('KitaPeduli');
        $this->get('/tentang-kami')->assertOk()->assertSee('Pedoman Kebaikan');
        $this->get('/kampanye')->assertOk()->assertSee('Jelajahi Kampanye KitaPeduli');

        $campaign = Campaign::where('status', Campaign::STATUS_ACTIVE)->firstOrFail();

        $this->get("/kampanye/{$campaign->slug}")
            ->assertOk()
            ->assertSee('1. Cerita Penggalangan Dana')
            ->assertSee('2. Kabar Terbaru')
            ->assertSee('3. Pencairan Dana / Laporan Penyaluran')
            ->assertSee('4. Informasi Pengelola Kampanye')
            ->assertSee('5. Ringkasan Donasi Kampanye')
            ->assertSee('6. Doa-doa Orang Baik')
            ->assertSee('7. Pedoman Kebaikan')
            ->assertSee('8. FAQ Singkat');

        $this->get("/kampanye/{$campaign->slug}/donasi")->assertOk();

        $initialCollected = (float) $campaign->collected_amount;

        $postDonation = $this->post("/kampanye/{$campaign->slug}/donasi", [
            'campaign_id' => $campaign->id,
            'donor_name' => 'Pengunjung Guest Web',
            'donor_email' => 'guest.web@example.com',
            'amount' => 125000,
            'payment_method' => 'qris',
            'donor_message' => 'Doa kebaikan dari Guest Web.',
            'is_anonymous' => '1',
        ]);

        $payment = Payment::latest('id')->firstOrFail();
        $postDonation->assertRedirect(route('payments.show', $payment->reference));

        $this->assertNull($payment->donation->user_id);
        $this->assertSame(Donation::STATUS_PENDING, $payment->donation->status);

        $this->get(route('payments.show', $payment->reference))
            ->assertOk()
            ->assertSee('SIMULASI / DEMO')
            ->assertSee($payment->reference);

        $this->post(route('payments.simulate', $payment->reference))
            ->assertRedirect(route('payments.show', $payment->reference));

        $this->assertSame(Payment::STATUS_PAID, $payment->fresh()->status);
        $this->assertSame(Donation::STATUS_SUCCESS, $payment->donation->fresh()->status);
        $this->assertEquals($initialCollected + 125000, (float) $campaign->fresh()->collected_amount);

        $admin = User::where('role', User::ROLE_ADMIN)->firstOrFail();
        $this->actingAs($admin)->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Total Dana Disalurkan');
        $this->actingAs($admin)->get('/admin/kampanye')->assertOk();
        $this->actingAs($admin)->get("/admin/kampanye/{$campaign->id}/edit")
            ->assertOk()
            ->assertSee('name="start_date"', false);
        $this->actingAs($admin)->get('/admin/kategori')->assertOk();
        $this->actingAs($admin)->get('/admin/kabar-terbaru')->assertOk();
        $this->actingAs($admin)->get('/admin/penyaluran-dana')->assertOk();
        $this->actingAs($admin)->get('/admin/transaksi-donasi')->assertOk();
        $this->actingAs($admin)->get('/admin/pengguna')->assertOk();
        $this->actingAs($admin)->get('/admin/laporan')
            ->assertOk()
            ->assertDontSee('MySQL');

        $donor = User::where('role', User::ROLE_DONOR)->firstOrFail();
        $this->actingAs($donor)->get('/donatur/dashboard')->assertOk();
        $this->actingAs($donor)->get('/donatur/riwayat-donasi')->assertOk();
    }
}
