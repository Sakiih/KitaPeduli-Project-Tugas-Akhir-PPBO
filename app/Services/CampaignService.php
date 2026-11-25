<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignService
{
    /** @param array<string, mixed> $data */
    public function createCampaign(array $data, User $admin, ?UploadedFile $bannerFile = null): Campaign
    {
        $slug = $this->generateUniqueSlug((string) $data['title']);

        $bannerPath = $data['banner_image'] ?? null;
        if ($bannerFile instanceof UploadedFile) {
            $bannerPath = $bannerFile->store('campaigns', 'public');
        }

        return Campaign::create([
            'category_id' => (int) $data['category_id'],
            'created_by' => $admin->id,
            'title' => trim((string) $data['title']),
            'slug' => $slug,
            'short_description' => $data['short_description'] ?? Str::limit(strip_tags((string) $data['story']), 180),
            'story' => (string) $data['story'],
            'banner_image' => $bannerPath,
            'target_amount' => (float) $data['target_amount'],
            'collected_amount' => 0,
            'donor_count' => 0,
            'start_date' => $data['start_date'] ?? now()->toDateString(),
            'end_date' => $data['end_date'],
            'organizer_name' => trim((string) $data['organizer_name']),
            'organizer_type' => $data['organizer_type'] ?? 'Lembaga Sosial / Pengelola Kampanye',
            'organizer_description' => $data['organizer_description'] ?? null,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'is_verified_organizer' => (bool) ($data['is_verified_organizer'] ?? true),
            'status' => $data['status'] ?? Campaign::STATUS_ACTIVE,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function updateCampaign(Campaign $campaign, array $data, ?UploadedFile $bannerFile = null): Campaign
    {
        if (isset($data['title']) && $data['title'] !== $campaign->title) {
            $campaign->slug = $this->generateUniqueSlug((string) $data['title'], $campaign->id);
        }

        if ($bannerFile instanceof UploadedFile) {
            if ($campaign->banner_image && ! str_starts_with($campaign->banner_image, 'images/') && Storage::disk('public')->exists($campaign->banner_image)) {
                Storage::disk('public')->delete($campaign->banner_image);
            }
            $campaign->banner_image = $bannerFile->store('campaigns', 'public');
        }

        $campaign->fill([
            'category_id' => $data['category_id'] ?? $campaign->category_id,
            'title' => isset($data['title']) ? trim((string) $data['title']) : $campaign->title,
            'short_description' => $data['short_description'] ?? $campaign->short_description,
            'story' => $data['story'] ?? $campaign->story,
            'target_amount' => isset($data['target_amount']) ? (float) $data['target_amount'] : $campaign->target_amount,
            'start_date' => $data['start_date'] ?? $campaign->start_date,
            'end_date' => $data['end_date'] ?? $campaign->end_date,
            'organizer_name' => isset($data['organizer_name']) ? trim((string) $data['organizer_name']) : $campaign->organizer_name,
            'organizer_type' => $data['organizer_type'] ?? $campaign->organizer_type,
            'organizer_description' => array_key_exists('organizer_description', $data) ? $data['organizer_description'] : $campaign->organizer_description,
            'is_featured' => array_key_exists('is_featured', $data) ? (bool) $data['is_featured'] : $campaign->is_featured,
            'is_verified_organizer' => array_key_exists('is_verified_organizer', $data) ? (bool) $data['is_verified_organizer'] : $campaign->is_verified_organizer,
            'status' => $data['status'] ?? $campaign->status,
        ]);

        $campaign->save();

        return $campaign->fresh(['category', 'creator']);
    }

    /** @return array{action: string, message: string, campaign: ?Campaign} */
    public function deleteOrArchiveCampaign(Campaign $campaign): array
    {
        // Kampanye dengan transaksi donasi diarsipkan menjadi inactive agar histori tetap terjaga.
        try {
            return DB::transaction(function () use ($campaign): array {
                $lockedCampaign = Campaign::where('id', $campaign->id)->lockForUpdate()->firstOrFail();

                if ($lockedCampaign->donations()->exists()) {
                    $lockedCampaign->changeStatus(Campaign::STATUS_INACTIVE);

                    return [
                        'action' => 'archived',
                        'message' => 'Kampanye telah memiliki histori transaksi donasi sehingga dinonaktifkan (diarsipkan) agar histori tetap utuh.',
                        'campaign' => $lockedCampaign->fresh(),
                    ];
                }

                $bannerImage = $lockedCampaign->banner_image;
                $lockedCampaign->delete();

                if ($bannerImage && ! str_starts_with($bannerImage, 'images/') && Storage::disk('public')->exists($bannerImage)) {
                    Storage::disk('public')->delete($bannerImage);
                }

                return [
                    'action' => 'deleted',
                    'message' => 'Kampanye berhasil dihapus karena belum memiliki transaksi donasi.',
                    'campaign' => null,
                ];
            });
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? $e->getCode();
            $driverCode = (int) ($e->errorInfo[1] ?? 0);

            if ($sqlState === '23000' || in_array($driverCode, [1451, 1452], true)) {
                $campaign->changeStatus(Campaign::STATUS_INACTIVE);

                return [
                    'action' => 'archived',
                    'message' => 'Kampanye telah memiliki histori transaksi donasi sehingga dinonaktifkan (diarsipkan) agar histori tetap utuh.',
                    'campaign' => $campaign->fresh(),
                ];
            }

            throw $e;
        }
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'kampanye';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Campaign::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
