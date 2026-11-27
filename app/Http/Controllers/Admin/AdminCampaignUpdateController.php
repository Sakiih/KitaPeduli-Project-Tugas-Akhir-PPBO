<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignUpdateRequest;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCampaignUpdateController extends Controller
{
    public function index(Request $request): View
    {
        $campaigns = Campaign::orderBy('title')->get();
        $query = CampaignUpdate::with(['campaign', 'author']);

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', (int) $request->query('campaign_id'));
        }

        $updates = $query->orderByDesc('published_at')->paginate(10)->withQueryString();

        return view('admin.updates.index', compact('updates', 'campaigns'));
    }

    public function store(StoreCampaignUpdateRequest $request): RedirectResponse
    {
        $request->validate([
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
        ]);

        $validated = $request->validated();
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('campaign_updates', 'public')
            : null;

        CampaignUpdate::create([
            'campaign_id' => (int) $request->input('campaign_id'),
            'created_by' => $request->user()->id,
            'title' => trim($validated['title']),
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'published_at' => $validated['published_at'] ?? now(),
        ]);

        return back()->with('success', 'Kabar terbaru kampanye berhasil dipublikasikan.');
    }

    public function update(StoreCampaignUpdateRequest $request, CampaignUpdate $update): RedirectResponse
    {
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

        return back()->with('success', 'Kabar terbaru berhasil diperbarui.');
    }

    public function destroy(CampaignUpdate $update): RedirectResponse
    {
        if ($update->image_path && Storage::disk('public')->exists($update->image_path)) {
            Storage::disk('public')->delete($update->image_path);
        }

        $update->delete();

        return back()->with('success', 'Kabar terbaru berhasil dihapus.');
    }
}
