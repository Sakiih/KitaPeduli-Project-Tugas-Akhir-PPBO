<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount(['campaigns' => function ($q) {
            $q->where('status', Campaign::STATUS_ACTIVE);
        }])->orderBy('name')->get();

        $totalActiveCampaigns = Campaign::where('status', Campaign::STATUS_ACTIVE)->count();

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

        if ($request->filled('category')) {
            $catSlug = (string) $request->query('category');
            $query->whereHas('category', fn ($q) => $q->where('slug', $catSlug));
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->query('category_id'));
        }

        $sort = (string) $request->query('sort', 'latest');
        match ($sort) {
            'ending_soon' => $query->orderBy('end_date', 'asc'),
            'most_funded' => $query->orderByDesc('collected_amount'),
            'featured' => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };

        $campaigns = $query->paginate(9)->withQueryString();

        return view('campaigns.index', compact(
            'categories',
            'totalActiveCampaigns',
            'campaigns',
            'sort'
        ));
    }

    public function show(Request $request, string $slug): View
    {
        $relations = ['category', 'updates.author', 'disbursementReports.author'];
        $campaignQuery = Campaign::with($relations);

        // Hindari type coercion MySQL pada slug yang diawali angka.
        $campaign = ctype_digit($slug)
            ? $campaignQuery->where('id', (int) $slug)->firstOrFail()
            : $campaignQuery->where('slug', $slug)->firstOrFail();

        $prayers = Donation::with('reactions')
            ->where('campaign_id', $campaign->id)
            ->where('status', Donation::STATUS_SUCCESS)
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'doa_page');

        $totalDisbursed = (float) $campaign->disbursementReports->sum('amount');

        $relatedCampaigns = Campaign::with('category')
            ->where('status', Campaign::STATUS_ACTIVE)
            ->where('id', '!=', $campaign->id)
            ->orderByDesc('is_featured')
            ->limit(3)
            ->get();

        return view('campaigns.show', compact(
            'campaign',
            'prayers',
            'totalDisbursed',
            'relatedCampaigns'
        ));
    }
}
