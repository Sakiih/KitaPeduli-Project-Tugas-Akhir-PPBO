<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignUpdateRequest;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CampaignUpdateApiController extends Controller
{
    public function index(string $campaignId): JsonResponse
    {
        $campaign = ctype_digit($campaignId) ? Campaign::find((int) $campaignId) : null;
        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $updates = CampaignUpdate::with('author:id,name')
            ->where('campaign_id', $campaign->id)
            ->orderByDesc('published_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kabar terbaru kampanye berhasil diambil.',
            'data' => $updates,
        ]);
    }

    public function store(StoreCampaignUpdateRequest $request, string $campaignId): JsonResponse
    {
        $campaign = ctype_digit($campaignId) ? Campaign::find((int) $campaignId) : null;
        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validated();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaign_updates', 'public');
        }

        $update = CampaignUpdate::create([
            'campaign_id' => $campaign->id,
            'created_by' => $request->user()->id,
            'title' => trim($validated['title']),
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'published_at' => $validated['published_at'] ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kabar terbaru kampanye berhasil dipublikasikan.',
            'data' => $update,
        ], 201);
    }

    public function update(StoreCampaignUpdateRequest $request, string $id): JsonResponse
    {
        $update = ctype_digit($id) ? CampaignUpdate::find((int) $id) : null;
        if (! $update) {
            return response()->json([
                'success' => false,
                'message' => 'Kabar terbaru tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($update->image_path && Storage::disk('public')->exists($update->image_path)) {
                Storage::disk('public')->delete($update->image_path);
            }
            $update->image_path = $request->file('image')->store('campaign_updates', 'public');
        }

        $update->title = trim($validated['title']);
        $update->content = $validated['content'];
        if (! empty($validated['published_at'])) {
            $update->published_at = $validated['published_at'];
        }
        $update->save();

        return response()->json([
            'success' => true,
            'message' => 'Kabar terbaru berhasil diperbarui.',
            'data' => $update,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $update = ctype_digit($id) ? CampaignUpdate::find((int) $id) : null;
        if (! $update) {
            return response()->json([
                'success' => false,
                'message' => 'Kabar terbaru tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        if ($update->image_path && Storage::disk('public')->exists($update->image_path)) {
            Storage::disk('public')->delete($update->image_path);
        }

        $update->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kabar terbaru berhasil dihapus.',
            'data' => null,
        ]);
    }
}
