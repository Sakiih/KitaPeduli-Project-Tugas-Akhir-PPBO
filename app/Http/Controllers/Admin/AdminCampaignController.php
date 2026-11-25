<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Models\Campaign;
use App\Models\Category;
use App\Services\CampaignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCampaignController extends Controller
{
    public function __construct(
        private readonly CampaignService $campaignService
    ) {}

    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
        $query = Campaign::with(['category', 'creator'])->withCount('donations');

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->query('category_id'));
        }

        $campaigns = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.campaigns.index', compact('campaigns', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.campaigns.create', compact('categories'));
    }

    public function store(StoreCampaignRequest $request): RedirectResponse
    {
        $this->campaignService->createCampaign(
            $request->validated(),
            $request->user(),
            $request->file('banner_image')
        );

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Kampanye baru berhasil dibuat.');
    }

    public function edit(Campaign $campaign): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.campaigns.edit', compact('campaign', 'categories'));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): RedirectResponse
    {
        $this->campaignService->updateCampaign(
            $campaign,
            $request->validated(),
            $request->file('banner_image')
        );

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Informasi kampanye berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in([Campaign::STATUS_ACTIVE, Campaign::STATUS_COMPLETED, Campaign::STATUS_INACTIVE]),
            ],
        ]);

        $campaign->changeStatus($validated['status']);

        return back()->with('success', 'Status kampanye berhasil diubah menjadi '.$campaign->status.'.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $result = $this->campaignService->deleteOrArchiveCampaign($campaign);

        return back()->with('success', $result['message']);
    }
}
