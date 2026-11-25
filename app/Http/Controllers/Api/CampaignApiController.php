<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CampaignApiController extends Controller
{
    public function __construct(
        private readonly CampaignService $campaignService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Campaign::with('category')
            ->where('status', Campaign::STATUS_ACTIVE);

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('organizer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->query('category_id'));
        }

        if ($request->filled('category')) {
            $categorySlug = (string) $request->query('category');
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        $sort = (string) $request->query('sort', 'latest');
        match ($sort) {
            'ending_soon' => $query->orderBy('end_date', 'asc'),
            'most_funded' => $query->orderByDesc('collected_amount'),
            'featured' => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
            default => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
        };

        $perPage = min(50, max(1, (int) $request->query('per_page', 9)));
        $campaigns = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar kampanye aktif berhasil diambil.',
            'data' => $campaigns->getCollection()->map(fn (Campaign $c) => $this->transformCampaign($c)),
            'meta' => [
                'current_page' => $campaigns->currentPage(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'total' => $campaigns->total(),
            ],
        ]);
    }

    public function show(string $idOrSlug): JsonResponse
    {
        $relations = ['category', 'updates', 'disbursementReports'];
        $campaignQuery = Campaign::with($relations);

        // Pisahkan pencarian ID numerik dan slug untuk menghindari type coercion MySQL.
        $campaign = ctype_digit($idOrSlug)
            ? $campaignQuery->where('id', (int) $idOrSlug)->first()
            : $campaignQuery->where('slug', $idOrSlug)->first();

        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail kampanye berhasil diambil.',
            'data' => array_merge($this->transformCampaign($campaign, true), [
                'updates_count' => $campaign->updates->count(),
                'disbursement_reports_count' => $campaign->disbursementReports->count(),
                'total_disbursed_amount' => (float) $campaign->disbursementReports->sum('amount'),
            ]),
        ]);
    }

    public function adminIndex(Request $request): JsonResponse
    {
        $query = Campaign::with(['category', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->query('category_id'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where('title', 'like', "%{$search}%");
        }

        $perPage = min(50, max(1, (int) $request->query('per_page', 15)));
        $campaigns = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar seluruh kampanye berhasil diambil.',
            'data' => $campaigns->getCollection()->map(fn (Campaign $c) => $this->transformCampaign($c, true)),
            'meta' => [
                'current_page' => $campaigns->currentPage(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'total' => $campaigns->total(),
            ],
        ]);
    }

    public function store(StoreCampaignRequest $request): JsonResponse
    {
        $campaign = $this->campaignService->createCampaign(
            $request->validated(),
            $request->user(),
            $request->file('banner_image')
        );

        $campaign->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Kampanye berhasil dibuat.',
            'data' => $this->transformCampaign($campaign, true),
        ], 201);
    }

    public function update(UpdateCampaignRequest $request, string $id): JsonResponse
    {
        $campaign = ctype_digit($id) ? Campaign::find((int) $id) : null;
        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $updated = $this->campaignService->updateCampaign(
            $campaign,
            $request->validated(),
            $request->file('banner_image')
        );

        return response()->json([
            'success' => true,
            'message' => 'Kampanye berhasil diperbarui.',
            'data' => $this->transformCampaign($updated, true),
        ]);
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $campaign = ctype_digit($id) ? Campaign::find((int) $id) : null;
        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in([Campaign::STATUS_ACTIVE, Campaign::STATUS_COMPLETED, Campaign::STATUS_INACTIVE]),
            ],
        ]);

        $campaign->changeStatus($validated['status']);

        return response()->json([
            'success' => true,
            'message' => 'Status kampanye berhasil diubah menjadi '.$campaign->status.'.',
            'data' => $this->transformCampaign($campaign->fresh('category'), true),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $campaign = ctype_digit($id) ? Campaign::find((int) $id) : null;
        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $result = $this->campaignService->deleteOrArchiveCampaign($campaign);

        return response()->json([
            'success' => true,
            'action' => $result['action'],
            'message' => $result['message'],
            'data' => $result['campaign'] ? $this->transformCampaign($result['campaign'], true) : null,
        ]);
    }

    /** @return array<string, mixed> */
    private function transformCampaign(Campaign $campaign, bool $includeStory = false): array
    {
        $data = [
            'id' => $campaign->id,
            'category_id' => $campaign->category_id,
            'category' => $campaign->category ? [
                'id' => $campaign->category->id,
                'name' => $campaign->category->name,
                'slug' => $campaign->category->slug,
            ] : null,
            'title' => $campaign->title,
            'slug' => $campaign->slug,
            'short_description' => $campaign->short_description,
            'banner_url' => $campaign->bannerUrl(),
            'target_amount' => (float) $campaign->target_amount,
            'collected_amount' => (float) $campaign->collected_amount,
            'progress_percentage' => $campaign->progressPercentage(),
            'donor_count' => (int) $campaign->donor_count,
            'start_date' => $campaign->start_date?->toDateString(),
            'end_date' => $campaign->end_date?->toDateString(),
            'remaining_days' => $campaign->remainingDays(),
            'organizer' => [
                'name' => $campaign->organizer_name,
                'type' => $campaign->organizer_type,
                'description' => $campaign->organizer_description,
                'is_verified' => (bool) $campaign->is_verified_organizer,
            ],
            'is_featured' => (bool) $campaign->is_featured,
            'is_accepting_donations' => $campaign->isAcceptingDonations(),
            'status' => $campaign->status,
            'created_at' => $campaign->created_at?->toIso8601String(),
        ];

        if ($includeStory) {
            $data['story'] = $campaign->story;
        }

        return $data;
    }
}
