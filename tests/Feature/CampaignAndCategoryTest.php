<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignAndCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_browse_search_and_filter_active_campaigns(): void
    {
        $admin = User::factory()->admin()->create();
        $catEdu = Category::create(['name' => 'Pendidikan', 'slug' => 'pendidikan']);
        $catHealth = Category::create(['name' => 'Kesehatan', 'slug' => 'kesehatan']);

        Campaign::create([
            'category_id' => $catEdu->id,
            'created_by' => $admin->id,
            'title' => 'Beasiswa Anak Pesisir',
            'slug' => 'beasiswa-anak-pesisir',
            'story' => 'Cerita beasiswa.',
            'target_amount' => 10000000,
            'end_date' => now()->addDays(20)->toDateString(),
            'organizer_name' => 'Yayasan Pendidikan',
            'status' => Campaign::STATUS_ACTIVE,
        ]);

        Campaign::create([
            'category_id' => $catHealth->id,
            'created_by' => $admin->id,
            'title' => 'Kampanye Nonaktif',
            'slug' => 'kampanye-nonaktif',
            'story' => 'Tidak boleh tampil di publik.',
            'target_amount' => 10000000,
            'end_date' => now()->addDays(20)->toDateString(),
            'organizer_name' => 'Yayasan Kesehatan',
            'status' => Campaign::STATUS_INACTIVE,
        ]);

        $this->get('/')->assertOk()->assertSee('KitaPeduli')->assertSee('Beasiswa Anak Pesisir');

        $apiResponse = $this->getJson('/api/campaigns?search=Beasiswa&category_id='.$catEdu->id);
        $apiResponse->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Beasiswa Anak Pesisir');
    }

    public function test_admin_can_create_update_and_archive_campaign_with_donations(): void
    {
        $admin = User::factory()->admin()->create();
        $donor = User::factory()->create();
        $category = Category::create(['name' => 'Kemanusiaan', 'slug' => 'kemanusiaan']);

        $this->actingAs($donor, 'sanctum')
            ->postJson('/api/admin/campaigns', [
                'category_id' => $category->id,
                'title' => 'Kampanye Ilegal',
            ])->assertForbidden();

        $createResponse = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/campaigns', [
                'category_id' => $category->id,
                'title' => 'Bantuan Pangan Lansia',
                'story' => 'Cerita lengkap bantuan pangan lansia.',
                'target_amount' => 25000000,
                'end_date' => now()->addDays(30)->toDateString(),
                'organizer_name' => 'Komunitas Peduli Lansia',
            ]);

        $createResponse->assertCreated()
            ->assertJsonPath('data.title', 'Bantuan Pangan Lansia');

        $campaignId = $createResponse->json('data.id');

        Donation::create([
            'campaign_id' => $campaignId,
            'user_id' => null,
            'donor_name' => 'Guest Donatur',
            'amount' => 50000,
            'status' => Donation::STATUS_SUCCESS,
        ]);

        // Kampanye yang sudah memiliki transaksi harus diarsipkan, bukan dihapus permanen.
        $deleteResponse = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/admin/campaigns/{$campaignId}");

        $deleteResponse->assertOk()
            ->assertJsonPath('action', 'archived')
            ->assertJsonPath('data.status', Campaign::STATUS_INACTIVE);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaignId,
            'status' => Campaign::STATUS_INACTIVE,
        ]);

        $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/admin/categories/{$category->id}")
            ->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_numeric_prefixed_slug_does_not_collide_with_campaign_id(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Lingkungan', 'slug' => 'lingkungan']);

        $firstCampaign = Campaign::create([
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'title' => 'Kampanye Pertama',
            'slug' => 'kampanye-pertama',
            'story' => 'Cerita pertama.',
            'target_amount' => 10000000,
            'end_date' => now()->addDays(10)->toDateString(),
            'organizer_name' => 'Yayasan Hijau',
            'status' => Campaign::STATUS_ACTIVE,
        ]);

        $secondCampaign = Campaign::create([
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'title' => '1000 Pohon Pesisir',
            'slug' => $firstCampaign->id.'-pohon-pesisir',
            'story' => 'Cerita kedua.',
            'target_amount' => 10000000,
            'end_date' => now()->addDays(10)->toDateString(),
            'organizer_name' => 'Yayasan Hijau',
            'status' => Campaign::STATUS_ACTIVE,
        ]);

        $this->getJson('/api/campaigns/'.$secondCampaign->slug)
            ->assertOk()
            ->assertJsonPath('data.id', $secondCampaign->id)
            ->assertJsonPath('data.title', '1000 Pohon Pesisir');

        $this->actingAs($admin, 'sanctum')
            ->deleteJson('/api/admin/categories/bukan-angka')
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
